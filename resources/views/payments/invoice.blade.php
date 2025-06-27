<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice Pembayaran #{{ $payment->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
        }

        .margin-top {
            margin-top: 50px;
        }

        .justify-center {
            text-align: center;
        }

        .invoice-info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        table td,
        table th {
            padding: 10px;
            vertical-align: top;
        }

        table tr.top table td {
            padding-bottom: 20px;
        }

        table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        table tr.details td {
            padding-bottom: 20px;
        }

        table tr.item td {
            border-bottom: 1px solid #eee;
        }

        table tr.total td {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .status {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .status-paid {
            background: #c8e6c9;
            color: #256029;
        }

        .status-review {
            background: #fff0c2;
            color: #a68b00;
        }

        .status-failed {
            background: #ffcdd2;
            color: #c63737;
        }

        .logo {
            max-width: 150px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ public_path('images/logo.png') }}" class="logo">
                            </td>
                            <td>
                                Invoice #: {{ $payment->id }}<br>
                                Tanggal: {{ $payment->created_at->format('d F Y') }}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                {{ config('app.name') }}<br>
                                Alamat Perusahaan<br>
                                Kota, Kode Pos<br>
                                Telp: 021-xxx-xxx
                            </td>
                            <td>
                                <strong>Kepada:</strong><br>
                                {{ $payment->penyewa->nama ?? 'Pelanggan' }}<br>
                                {{ $payment->penyewa->alamat ?? 'Alamat tidak tersedia' }}<br>
                                {{ $payment->penyewa->nohp ?? 'Telp tidak tersedia' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Item</td>
                <td>Jumlah</td>
            </tr>

            <tr class="item">
                <td>
                    Pembayaran untuk properti: {{ $payment->booking->property->nama }}<br>
                    Periode: {{ $payment->booking->start_date }} - {{
                    $payment->booking->end_date }}
                </td>
                <td>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
            </tr>

            <tr class="item">
                <td>Sisa Pembayaran</td>
                <td>Rp {{ number_format($payment->sisa_pembayaran, 0, ',', '.') }}</td>
            </tr>
            <?php
            $telah_dibayar = intval($payment->jumlah) - intval($payment->sisa_pembayaran)
            ?>
              <tr class="item">
                <td>Telah Dibayar</td>
                <td>Rp {{ number_format($telah_dibayar, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td></td>
                <td>Lama Sewa {{ number_format($payment->booking->durasisewa, 0, ',', '.') }} Bulan</td>
            </tr>
        </table>

        <div class="margin-top invoice-info">
            <table>
                <tr>
                    <td><strong>Metode Pembayaran:</strong></td>
                    <td>{{ ucfirst($payment->metode_pembayaran->nama_bank) }}</td>
                </tr>
                <tr>
                    <td><strong>Status Pembayaran:</strong></td>
                    <td>
                        <span class="status status-{{ $payment->payment_status }}">
                            @if($payment->payment_status == 'paid')
                            LUNAS
                            @elseif($payment->payment_status == 'review')
                            REVIEW
                            @else
                            GAGAL
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Tanggal Pembayaran:</strong></td>
                    <td>{{ $payment->created_at->format('d F Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <div class="justify-center margin-top">
            <p>Terima kasih telah menggunakan layanan kami.</p>
            <p>Invoice ini sah dan diproses oleh komputer.</p>
        </div>
    </div>
</body>

</html>