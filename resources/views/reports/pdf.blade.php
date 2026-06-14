<!DOCTYPE html>
<html>

<head>
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0;
            padding: 0;
            color: #1e3a8a;
        }

        .info {
            margin-bottom: 20px;
            font-weight: bold;
        }

        table {
            w-full: 100%;
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
        }

        th {
            bg-color: #f3f4f6;
            background-color: #f3f4f6;
        }

        .income {
            color: #10b981;
            font-weight: bold;
        }

        .expense {
            color: #000000;
        }

        .total-section {
            margin-top: 30px;
            border-top: 2px solid #333;
            pt: 10px;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>LAPORAN MUTASI KEUANGAN</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>Dompet</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $trx)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($trx->date)->format('d/m/Y') }}</td>
                    <td>{{ $trx->description }}</td>
                    <td>{{ $trx->category->name }}</td>
                    <td>{{ $trx->wallet->name }}</td>
                    <td class="{{ $trx->type == 'income' ? 'income' : 'expense' }}">
                        {{ $trx->type == 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <p><strong>Total Pemasukan:</strong> <span class="income">Rp
                {{ number_format($totalIncome, 0, ',', '.') }}</span></p>
        <p><strong>Total Pengeluaran:</strong> <span style="color: #ef4444; font-weight: bold;">Rp
                {{ number_format($totalExpense, 0, ',', '.') }}</span></p>
        <p><strong>Sisa Saldo Bersih:</strong> <strong>Rp
                {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</strong></p>
    </div>

</body>

</html>
