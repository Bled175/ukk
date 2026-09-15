@csrf

<div class="grid gap-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    <input type="hidden" name="salary_period_id" value="{{ $period->id }}">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Data Karyawan</h2>
    </div>

    <label class="block text-sm font-medium text-slate-600">
        Nama
        <input
            class="mt-2 block w-full rounded-md border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
            name="employee_name" value="{{ old('employee_name', $slip->employee_name ?? '') }}" required>
        @error('employee_name')
            <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
        @enderror
    </label>

    <label class="block text-sm font-medium text-slate-600">
        NIK
        <input
            class="mt-2 block w-full rounded-md border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
            name="employee_nik" value="{{ old('employee_nik', $slip->employee_nik ?? '') }}" required>
        @error('employee_nik')
            <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
        @enderror
    </label>

    <label class="block text-sm font-medium text-slate-600">
        Jabatan
        <input
            class="mt-2 block w-full rounded-md border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
            name="position" value="{{ old('position', $slip->position ?? '') }}" required>
        @error('position')
            <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
        @enderror
    </label>

</div>

<div class="mt-6 grid gap-6 md:grid-cols-2">
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-xl font-semibold text-slate-800">Penghasilan</h2>
        <div class="space-y-4">
            <label class="block text-sm font-medium text-slate-600">Gaji Pokok<input
                    class="mt-2 block w-full rounded-md border-slate-300 text-sm" type="number" min="0"
                    step="5000" name="base_salary" value="{{ old('base_salary', $slip->base_salary ?? '') }}"
                    required></label>
            <label class="block text-sm font-medium text-slate-600">Lembur<input
                    class="mt-2 block w-full rounded-md border-slate-300 text-sm" type="number" min="0"
                    step="5000" name="overtime" value="{{ old('overtime', $slip->overtime ?? 0) }}"></label>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-xl font-semibold text-slate-800">Potongan</h2>
        <label class="block text-sm font-medium text-slate-600">Pinjaman Karyawan<input
                class="mt-2 block w-full rounded-md border-slate-300 text-sm" type="number" min="0"
                step="0.01" name="employee_loan"
                value="{{ old('employee_loan', $slip->employee_loan ?? 0) }}"></label>
    </div>
</div>

<div class="mt-6 flex items-center justify-between rounded-xl bg-[#e8effd] px-6 py-5">
    <span class="text-lg font-medium text-slate-700">Gaji Bersih</span>
    <output id="net-salary-display" class="text-2xl font-bold text-slate-900">Rp0</output>
</div>

<div class="mt-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold text-slate-800">Verifikasi Keamanan</h2>
    <label class="block max-w-sm text-sm font-medium text-slate-600" for="captcha">
        Berapa hasil {{ $captcha['first'] }} + {{ $captcha['second'] }}?
        <input
            class="mt-3 block w-full rounded-md border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
            id="captcha" name="captcha" type="number" min="0" required>
    </label>
    @error('captcha')
        <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
    @enderror
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a class="rounded-full border border-slate-300 px-5 py-2.5 text-sm text-slate-600"
        href="{{ route('dashboard') }}">Batal</a>
    <button class="rounded-full bg-[#7050ad] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#5f4697]"
        type="submit">{{ $submitLabel }}</button>
</div>

<script>
    (() => {
        const baseSalary = document.querySelector('[name="base_salary"]');
        const overtime = document.querySelector('[name="overtime"]');
        const employeeLoan = document.querySelector('[name="employee_loan"]');
        const netSalary = document.querySelector('#net-salary-display');

        const formatRupiah = (value) => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }).format(value);

        const updateNetSalary = () => {
            const income = Number(baseSalary?.value || 0) + Number(overtime?.value || 0);
            const deduction = Number(employeeLoan?.value || 0);
            netSalary.textContent = formatRupiah(Math.max(0, income - deduction));
        };

        [baseSalary, overtime, employeeLoan].forEach((input) => input?.addEventListener('input', updateNetSalary));
        updateNetSalary();
    })();
</script>
