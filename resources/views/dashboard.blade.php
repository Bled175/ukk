<x-app-layout>
    <section class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 md:text-4xl">Daftar Menu Gaji</h1>
                <p class="mt-2 text-sm text-slate-400 md:text-base">18 terkirim · 6 belum terkirim</p>
            </div>
            <button
                class="rounded-full bg-[#7050ad] px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-[#5f4697]"
                type="button" data-open-period-modal>+ Tambah Slip Gaji</button>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-5 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        @if (session('whatsapp_url'))
            <div class="mb-5 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">WhatsApp siap dibuka. <a
                    class="font-semibold underline" href="{{ session('whatsapp_url') }}" target="_blank"
                    rel="noopener">Buka WhatsApp</a></div>
        @endif

        <div class="overflow-x-auto rounded-lg shadow-[0_5px_18px_rgba(68,80,103,0.14)]">
            <table class="min-w-[900px] w-full border-collapse text-base">
                <thead class="bg-[#e8effd] text-left text-sm font-bold text-slate-700 md:text-base">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-5 py-4">NIK</th>
                        <th class="px-5 py-4">Nama</th>
                        <th class="px-5 py-4">Jabatan</th>
                        <th class="px-5 py-4">Gaji Bersih</th>
                        <th class="px-5 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-slate-600">
                    @forelse ($slips as $slip)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-5">{{ $loop->iteration }}</td>
                            <td class="px-5 py-5">{{ $slip->employee_nik }}</td>
                            <td class="px-5 py-5 font-medium text-slate-800">{{ $slip->employee_name }}</td>
                            <td class="px-5 py-5">{{ $slip->position }}</td>
                            <td class="px-5 py-5">Rp{{ number_format($slip->net_salary, 0, ',', '.') }}</td>
                            <td class="flex items-center gap-4 px-5 py-5 text-lg">
                                <a class="text-blue-600 hover:text-blue-800"
                                    href="{{ route('payroll-slips.edit', $slip) }}" title="Edit slip gaji"
                                    aria-label="Edit slip gaji"><i class="bi bi-pencil-square"></i></a>
                                <form method="POST" action="{{ route('payroll-slips.destroy', $slip) }}"
                                    onsubmit="return confirm('Hapus slip gaji ini?')">@csrf @method('DELETE')<button
                                        class="text-red-600 hover:text-red-800" type="submit" title="Hapus slip gaji"
                                        aria-label="Hapus slip gaji"><i class="bi bi-trash3"></i></button></form>
                                <a class="text-slate-600 hover:text-slate-900"
                                    href="{{ route('payroll-slips.pdf', $slip) }}" target="_blank"
                                    title="Buka atau cetak PDF" aria-label="Buka atau cetak PDF"><i
                                        class="bi bi-printer"></i></a>
                                <button class="text-violet-600 hover:text-violet-800" type="button"
                                    title="Kirim slip gaji" aria-label="Kirim slip gaji" data-send-slip
                                    data-email-action="{{ route('payroll-slips.email', $slip) }}"
                                    data-whatsapp-action="{{ route('payroll-slips.whatsapp', $slip) }}"><i
                                        class="bi bi-send"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-10 text-center text-slate-400" colspan="6">Belum ada slip gaji.
                                Silakan tambahkan data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="fixed inset-0 z-30 hidden items-center justify-center bg-slate-900/40 p-4" data-period-modal>
        <div class="w-full max-w-sm rounded-2xl bg-white p-5 shadow-2xl">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">Pilih Periode Slip Gaji</h2>
                <button class="text-2xl text-slate-500" type="button" data-close-period-modal
                    aria-label="Tutup">&times;</button>
            </div>
            <p class="mt-2 text-xs leading-5 text-slate-500">Periode dihitung dari tanggal 25 bulan sebelumnya sampai
                tanggal 25 bulan pilihan.</p>
            <form class="mt-5" method="GET" action="{{ route('payroll-slips.create') }}">
                <label class="block text-sm font-medium text-slate-600" for="salary_month">Bulan Gaji</label>
                <select
                    class="mt-2 block w-full rounded-md border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
                    id="salary_month" name="month" required>
                    @foreach (range(1, 12) as $month)
                        <option value="{{ now()->year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                            @selected($month === now()->month)>
                            {{ now()->locale('id')->setMonth($month)->translatedFormat('F Y') }}</option>
                    @endforeach
                </select>
                <button class="mt-5 w-full rounded-full bg-[#7050ad] px-5 py-2.5 text-sm font-medium text-white"
                    type="submit">Lanjutkan ke Form</button>
            </form>
        </div>
    </div>

    <div class="fixed inset-0 z-30 hidden items-center justify-center bg-slate-900/40 p-4" data-send-modal>
        <div class="w-full max-w-lg rounded-2xl bg-white p-7 shadow-2xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800">Kirim Slip Gaji</h2><button class="text-2xl text-slate-500"
                    type="button" data-close-modal aria-label="Tutup">&times;</button>
            </div>
            <p class="mt-2 text-sm text-slate-500">Pilih metode pengiriman slip gaji.</p>
            <div class="mt-6 grid grid-cols-2 gap-3">
                <button class="rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium hover:border-violet-500"
                    type="button" data-method="email"><i class="bi bi-envelope mr-2"></i>Email</button>
                <button class="rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium hover:border-violet-500"
                    type="button" data-method="whatsapp"><i class="bi bi-whatsapp mr-2"></i>WhatsApp</button>
            </div>
            <form class="mt-5 hidden" method="POST" data-email-form>@csrf
                <label class="block text-sm font-medium text-slate-600">Email penerima<input
                        class="mt-2 block w-full rounded-md border-slate-300 text-sm" type="email" name="email"
                        required></label>
                <button
                    class="mt-5 rounded-full bg-[#7050ad] px-5 py-2.5 text-sm font-medium text-white disabled:cursor-not-allowed disabled:opacity-60"
                    type="submit" data-email-submit><i class="bi bi-send mr-2"></i><span>Kirim Email</span></button>
            </form>
            <form class="mt-5 hidden" method="POST" data-whatsapp-form>@csrf
                <label class="block text-sm font-medium text-slate-600">Nomor WhatsApp<input
                        class="mt-2 block w-full rounded-md border-slate-300 text-sm" type="tel" name="phone"
                        placeholder="08xxxxxxxxxx" pattern="\+?[0-9]{10,15}" required></label>
                <button class="mt-5 rounded-full bg-[#25D366] px-5 py-2.5 text-sm font-medium text-white"
                    type="submit"><i class="bi bi-whatsapp mr-2"></i>Buka WhatsApp</button>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.querySelector('[data-send-modal]');
            const emailForm = document.querySelector('[data-email-form]');
            const whatsappForm = document.querySelector('[data-whatsapp-form]');
            const openModal = (button) => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                emailForm.action = button.dataset.emailAction;
                whatsappForm.action = button.dataset.whatsappAction;
            };
            document.querySelectorAll('[data-send-slip]').forEach((button) => button.addEventListener('click', () =>
                openModal(button)));
            document.querySelector('[data-close-modal]')?.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                emailForm.classList.add('hidden');
                whatsappForm.classList.add('hidden');
            });
            document.querySelectorAll('[data-method]').forEach((button) => button.addEventListener('click', () => {
                const isEmail = button.dataset.method === 'email';
                emailForm.classList.toggle('hidden', !isEmail);
                whatsappForm.classList.toggle('hidden', isEmail);
            }));

            const periodModal = document.querySelector('[data-period-modal]');
            document.querySelector('[data-open-period-modal]')?.addEventListener('click', () => {
                periodModal.classList.remove('hidden');
                periodModal.classList.add('flex');
            });
            document.querySelector('[data-close-period-modal]')?.addEventListener('click', () => {
                periodModal.classList.add('hidden');
                periodModal.classList.remove('flex');
            });
            emailForm?.addEventListener('submit', () => {
                const submit = emailForm.querySelector('[data-email-submit]');
                submit.disabled = true;
                submit.querySelector('i').className = 'bi bi-hourglass-split mr-2';
                submit.querySelector('span').textContent = 'Mengirim...';
            });
        })();
    </script>
</x-app-layout>
