<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Okupansi Properti</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 30px;
        }

        h2, h3, h4 {
            text-align: center;
            margin: 10px 0;
        }

        p {
            margin: 4px 0;
        }

        .summary {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .chart {
            text-align: center;
            margin: 15px 0;
        }

        img {
            max-width: 80%;
            height: auto;
        }
    </style>
</head>

<body>
    <h2>Laporan Okupansi Properti</h2>

    <div class="summary">
        <p><strong>Total Properti:</strong> {{ count($data) }}</p>
        <p><strong>Total Kamar:</strong> {{ $data->sum('total_kamar') }}</p>
        <p><strong>Kamar Terisi:</strong> {{ $data->sum('kamar_terisi') }}</p>
        <p><strong>Rata-rata Okupansi:</strong>
            @php
                $avg = count($data) > 0
                    ? $data->sum(fn($item) => floatval(str_replace('%', '', $item['tingkat_okupansi']))) / count($data)
                    : 0;
            @endphp
            {{ number_format($avg, 2) }}%
        </p>
    </div>

    <h4>Grafik Okupansi per Properti</h4>
    <div class="chart">
        <img src="{{ $barChart }}" alt="Bar Chart">
    </div>

    <h4>Distribusi Okupansi</h4>
    <div class="chart">
        <img src="{{ $pieChart }}" alt="Pie Chart">
    </div>

    <h4>Detail Okupansi</h4>
    <table>
        <thead>
            <tr>
                <th>Nama Properti</th>
                <th>Total Kamar</th>
                <th>Kamar Terisi</th>
                <th>Kamar Kosong</th>
                <th>Tingkat Okupansi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row['nama_properti'] }}</td>
                    <td>{{ $row['total_kamar'] }}</td>
                    <td>{{ $row['kamar_terisi'] }}</td>
                    <td>{{ $row['kamar_kosong'] }}</td>
                    <td>{{ $row['tingkat_okupansi'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
