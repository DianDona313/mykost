<!DOCTYPE html>
<html>
<head>
    <title>Data Pengeluaran Kost</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Data Pengeluaran Kost</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Properti</th>
                <th>Kategori</th>
                <th>Keperluan</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Dibuat Oleh</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengeluarans as $index => $pengeluaran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pengeluaran->property->nama ?? '-' }}</td>
                    <td>{{ $pengeluaran->kategoriPengeluaran->nama ?? '-' }}</td>
                    <td>{{ $pengeluaran->keperluan ?? '-' }}</td>
                    <td>{{ $pengeluaran->jumlah_format ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $pengeluaran->status ?? '-' }}</td>
                    <td>{{ $pengeluaran->creator->name ?? '-' }}</td>
                    <td>{{ $pengeluaran->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>