<x-app-layout>
    <section class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Periode Gaji</h1>
                <p class="mt-2 text-sm text-slate-400">Kelola periode yang tersedia untuk slip gaji.</p>
            </div>
            <a class="rounded-full bg-[#7050ad] px-5 py-3 text-sm font-medium text-white"
                href="{{ route('salary-periods.create') }}">+ Tambah Periode</a>
        </div>
        @if (session('success'))
            <div class="mb-5 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-5 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        <div class="overflow-x-auto rounded-lg shadow-[0_5px_18px_rgba(68,80,103,0.14)]">
            <table class="w-full min-w-[700px] border-collapse text-base">
                <thead class="bg-[#e8effd] text-left text-sm font-bold text-slate-700">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-5 py-4">Nama Periode</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-slate-600">
                    @forelse ($periods as $period)
                        <tr>
                            <td class="px-5 py-5">{{ $loop->iteration }}</td>
                            <td class="px-5 py-5 font-medium text-slate-800">{{ $period->name }}</td>
                            <td class="px-5 py-5">{{ $period->starts_at->format('d M Y') }} -
                                {{ $period->ends_at->format('d M Y') }}</td>
                            <td class="px-5 py-5">
                                @if ($period->is_active)
                                    <span
                                        class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Aktif</span>
                                @else
                                    <span
                                        class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="flex gap-4 px-5 py-5"><a class="text-blue-600"
                                    href="{{ route('salary-periods.edit', $period) }}">Edit</a>
                                <form method="POST" action="{{ route('salary-periods.destroy', $period) }}"
                                    onsubmit="return confirm('Hapus periode ini?')">@csrf @method('DELETE')<button
                                        class="text-red-600" type="submit">Hapus</button></form>
                            </td>
                    </tr>@empty<tr>
                            <td class="px-5 py-8 text-center text-slate-400" colspan="5">Belum ada periode gaji.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
