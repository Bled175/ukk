<x-app-layout>
    <section class="mx-auto w-full max-w-6xl">
        <header class="mb-8 text-center">
            <h1 class="text-lg font-bold uppercase text-slate-800">Slip Gaji Karyawan</h1>
            <p class="mt-1 text-sm font-medium uppercase text-slate-700">Periode {{ $period->name }}</p>
        </header>
        <form method="POST" action="{{ route('payroll-slips.update', $slip) }}">
            @method('PUT')
            @include('payroll-slips._form', ['submitLabel' => 'Perbarui Data'])
        </form>
    </section>
</x-app-layout>
