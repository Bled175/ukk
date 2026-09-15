@csrf
<div class="grid gap-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm md:grid-cols-2">
    <label class="block text-sm font-medium text-slate-600 md:col-span-2">Nama Periode
        <input
            class="mt-2 block w-full rounded-md border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
            name="name" placeholder="Contoh: September 2026" value="{{ old('name', $period->name ?? '') }}" required>
        @error('name')
            <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
        @enderror
    </label>

    <label class="block text-sm font-medium text-slate-600">Tanggal Mulai
        <input class="mt-2 block w-full rounded-md border-slate-300 text-sm" type="date" name="starts_at"
            value="{{ old('starts_at', isset($period) ? $period->starts_at->format('Y-m-d') : '') }}" required>
    </label>

    <label class="block text-sm font-medium text-slate-600">Tanggal Selesai
        <input class="mt-2 block w-full rounded-md border-slate-300 text-sm" type="date" name="ends_at"
            value="{{ old('ends_at', isset($period) ? $period->ends_at->format('Y-m-d') : '') }}" required>
    </label>
</div>
<div class="mt-6 flex justify-end gap-3">
    <a class="rounded-full border border-slate-300 px-5 py-2.5 text-sm text-slate-600"
        href="{{ route('salary-periods.index') }}">Batal</a>
    <button class="rounded-full bg-[#7050ad] px-5 py-2.5 text-sm font-medium text-white"
        type="submit">{{ $submitLabel }}
    </button>
</div>
