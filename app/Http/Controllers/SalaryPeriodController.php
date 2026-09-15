<?php

namespace App\Http\Controllers;

use App\Models\SalaryPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SalaryPeriodController extends Controller
{
    public function index(): View
    {
        return view('salary-periods.index', [
            'periods' => SalaryPeriod::latest('starts_at')->get(),
        ]);
    }

    public function create(): View
    {
        return view('salary-periods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:salary_periods,name'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
        ]);

        DB::transaction(function () use ($validated): void {
            SalaryPeriod::query()->update(['is_active' => false]);
            SalaryPeriod::create([...$validated, 'is_active' => true]);
        });

        return to_route('salary-periods.index')->with('success', 'Periode gaji berhasil ditambahkan.');
    }

    public function edit(SalaryPeriod $salaryPeriod): View
    {
        return view('salary-periods.edit', ['period' => $salaryPeriod]);
    }

    public function update(Request $request, SalaryPeriod $salaryPeriod): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:salary_periods,name,' . $salaryPeriod->id],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
        ]);

        $salaryPeriod->update($validated);

        return to_route('salary-periods.index')->with('success', 'Periode gaji berhasil diperbarui.');
    }

    public function destroy(SalaryPeriod $salaryPeriod): RedirectResponse
    {
        if ($salaryPeriod->payrollSlips()->exists()) {
            return back()->with('error', 'Periode tidak dapat dihapus karena sudah digunakan oleh slip gaji.');
        }

        $salaryPeriod->delete();

        return to_route('salary-periods.index')->with('success', 'Periode gaji berhasil dihapus.');
    }
}
