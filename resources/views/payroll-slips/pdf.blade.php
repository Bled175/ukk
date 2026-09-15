<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji {{ $slip->employee_name }}</title>
    <style>
        @page {
            margin: 18mm;
        }

        body {
            color: #202b40;
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            margin: 0 auto;
            max-width: 780px;
        }

        .slip-header {
            margin: 4px 0 24px;
            text-align: center;
        }

        .slip-header h1 {
            color: #202b40;
            font-size: 16px;
            margin: 0;
        }

        .slip-header .muted {
            color: #64748b;
            font-size: 13px;
            margin-top: 4px;
        }

        .box {
            border: 1px solid #dce3ed;
            border-radius: 8px;
            margin-top: 22px;
            padding: 18px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 4px;
        }

        td:first-child {
            color: #64748b;
            width: 42%;
        }

        .total {
            background: #e8effd;
            font-size: 18px;
            font-weight: bold;
            margin-top: 22px;
            padding: 16px;
        }

    </style>
</head>

<body>
    <header class="slip-header">
        <h1>SLIP GAJI KARYAWAN</h1>
        <div class="muted">PERIODE {{ $slip->salaryPeriod->name }}</div>
    </header>
    <div class="box">
        <table>
            <tr>
                <td>Nama</td>
                <td>{{ $slip->employee_name }}</td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>{{ $slip->employee_nik }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>{{ $slip->position }}</td>
            </tr>
            <tr>
                <td>Gaji Pokok</td>
                <td>Rp{{ number_format($slip->base_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Lembur</td>
                <td>Rp{{ number_format($slip->overtime, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pinjaman Karyawan</td>
                <td>Rp{{ number_format($slip->employee_loan, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    <div class="total">Gaji Bersih: Rp{{ number_format($slip->net_salary, 0, ',', '.') }}</div>
</body>
@if (request()->routeIs('payroll-slips.pdf'))
    <script>
        window.addEventListener('load', () => window.print());
    </script>
@endif

</html>
