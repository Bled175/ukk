<?php

namespace App\Http\Controllers;

use App\Models\PayrollSlip;
use App\Models\SalaryPeriod;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollSlipController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'slips' => PayrollSlip::with('salaryPeriod')->latest()->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $captcha = $this->createCaptcha();
        $month = $request->validate(['month' => ['required', 'date_format:Y-m']])['month'] ?? now()->format('Y-m');
        $period = $this->getOrCreatePeriod($month);

        return view('payroll-slips.create', [
            'period' => $period,
            'captcha' => $captcha,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['total_income'] = $validated['base_salary'] + $validated['overtime'];
        $validated['net_salary'] = $validated['total_income'] - $validated['employee_loan'];

        PayrollSlip::create($validated);

        return to_route('dashboard')->with('success', 'Slip gaji berhasil ditambahkan.');
    }

    public function edit(PayrollSlip $payrollSlip): View
    {
        $captcha = $this->createCaptcha();

        return view('payroll-slips.edit', [
            'slip' => $payrollSlip,
            'period' => $payrollSlip->salaryPeriod,
            'captcha' => $captcha,
        ]);
    }

    public function update(Request $request, PayrollSlip $payrollSlip): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['total_income'] = $validated['base_salary'] + $validated['overtime'];
        $validated['net_salary'] = $validated['total_income'] - $validated['employee_loan'];

        $payrollSlip->update($validated);

        return to_route('dashboard')->with('success', 'Slip gaji berhasil diperbarui.');
    }

    public function destroy(PayrollSlip $payrollSlip): RedirectResponse
    {
        $payrollSlip->delete();

        return to_route('dashboard')->with('success', 'Slip gaji berhasil dihapus.');
    }

    public function pdf(PayrollSlip $payrollSlip)
    {
        $payrollSlip->load('salaryPeriod');

        return response()->view('payroll-slips.pdf', ['slip' => $payrollSlip]);
    }

    public function sharedPdf(PayrollSlip $payrollSlip)
    {
        $payrollSlip->load('salaryPeriod');

        return Pdf::loadView('payroll-slips.pdf', ['slip' => $payrollSlip])
            ->setPaper('a4')
            ->stream('slip-gaji-' . $payrollSlip->employee_nik . '.pdf');
    }

    public function sendEmail(Request $request, PayrollSlip $payrollSlip): RedirectResponse
    {
        $validated = $request->validate(['email' => ['required', 'email']]);

        if (blank(config('mail.mailers.smtp.password'))) {
            return back()->with('error', 'Password SMTP belum diisi. Gunakan App Password Gmail, lalu bersihkan cache konfigurasi.');
        }

        try {
            $payrollSlip->load('salaryPeriod');
            Mail::send('payroll-slips.email', ['slip' => $payrollSlip], function ($message) use ($validated, $payrollSlip): void {
                $message->to($validated['email'])
                    ->subject('Slip Gaji ' . $payrollSlip->salaryPeriod->name)
                    ->attachData(
                        Pdf::loadView('payroll-slips.pdf', ['slip' => $payrollSlip])->output(),
                        'slip-gaji-' . $payrollSlip->employee_nik . '.pdf',
                        ['mime' => 'application/pdf'],
                    );
            });
        } catch (\Throwable $exception) {
            Log::error('Pengiriman slip gaji gagal.', [
                'recipient' => $validated['email'],
                'exception' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Email gagal dikirim. Periksa App Password Gmail dan konfigurasi SMTP.');
        }

        return back()->with('success', 'Slip gaji berhasil dikirim ke email.');
    }

    public function whatsapp(Request $request, PayrollSlip $payrollSlip): RedirectResponse
    {
        $validated = $request->validate(['phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,15}$/']]);
        $phone = ltrim($validated['phone'], '+');
        $phone = str_starts_with($phone, '0') ? '62' . substr($phone, 1) : $phone;
        $pdfUrl = URL::signedRoute('payroll-slips.shared-pdf', ['payrollSlip' => $payrollSlip]);
        $message = rawurlencode('Halo ' . $payrollSlip->employee_name . ', slip gaji periode ' . $payrollSlip->salaryPeriod()->value('name') . " dapat diunduh di sini: $pdfUrl");

        return redirect()->away('https://wa.me/' . $phone . '?text=' . $message);
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'captcha' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ((int) $value !== (int) session('payroll_captcha.answer')) {
                        $fail('Jawaban CAPTCHA tidak tepat.');
                    }
                },
            ],
            'salary_period_id' => ['required', 'exists:salary_periods,id'],
            'employee_name' => ['required', 'string', 'max:100'],
            'employee_nik' => ['required', 'string', 'max:50'],
            'position' => ['required', 'string', 'max:100'],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'overtime' => ['nullable', 'numeric', 'min:0'],
            'employee_loan' => ['nullable', 'numeric', 'min:0'],
        ]);

        unset($validated['captcha']);
        session()->forget('payroll_captcha');

        return $validated;
    }

    private function createCaptcha(): array
    {
        $first = random_int(1, 9);
        $second = random_int(1, 9);
        $captcha = ['first' => $first, 'second' => $second, 'answer' => $first + $second];

        session(['payroll_captcha' => $captcha]);

        return $captcha;
    }

    private function getOrCreatePeriod(string $month): SalaryPeriod
    {
        Carbon::setLocale('id');
        $end = Carbon::createFromFormat('Y-m-d', $month . '-25');
        $start = $end->copy()->subMonth()->day(25);

        return DB::transaction(function () use ($start, $end): SalaryPeriod {
            SalaryPeriod::query()->update(['is_active' => false]);

            $period = SalaryPeriod::firstOrCreate(
                ['name' => $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y')],
                [
                    'starts_at' => $start->toDateString(),
                    'ends_at' => $end->toDateString(),
                    'is_active' => true,
                ],
            );

            $period->update(['is_active' => true]);

            return $period;
        });
    }
}
