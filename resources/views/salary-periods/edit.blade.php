<x-app-layout>
    <section class="mx-auto w-full max-w-4xl">
        <h1 class="mb-8 text-3xl font-bold text-slate-800">Edit Periode Gaji</h1>
        <form method="POST" action="{{ route('salary-periods.update', $period) }}">
            @method('PUT')@include('salary-periods._form', ['submitLabel' => 'Perbarui Periode'])</form>
    </section>
</x-app-layout>
