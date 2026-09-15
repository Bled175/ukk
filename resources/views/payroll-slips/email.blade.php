<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
</head>

<body>
    <h2>Slip Gaji Gajiku</h2>
    <p>Halo {{ $slip->employee_name }}, berikut ringkasan slip gaji periode {{ $slip->salaryPeriod->name }}.</p>
    <p><strong>Gaji Bersih: Rp{{ number_format($slip->net_salary, 0, ',', '.') }}</strong></p>
    <p>Silakan hubungi administrator jika ada data yang perlu diperbaiki.</p>
</body>

</html>
