@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Detail Pengeluaran</h1>

        <div class="card">
            <div class="card-body">
                <h5>Nama: {{ $historyPengeluaran->nama_pengeluaran }}</h5>
                <p><strong>Jumlah:</strong> Rp {{ number_format($historyPengeluaran->jumlah_pengeluaran, 0, ',', '.') }}</p>
                <p><strong>Tanggal:</strong> {{ $historyPengeluaran->tanggal_pengeluaran }}</p>
                <p><strong>Deskripsi:</strong> {{ $historyPengeluaran->deskripsi ?? '-' }}</p>
                <p><strong>Penanggung Jawab:</strong> {{ $historyPengeluaran->penanggung_jawab }}</p>

                @if ($historyPengeluaran->foto)
                    <p><strong>Foto:</strong></p>
                    <img src="{{ asset('storage/' . $historyPengeluaran->foto) }}" alt="Foto Pengeluaran" class="img-fluid"
                        style="max-width: 300px;">
                @endif

                <a href="{{ route('history_pengeluarans.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>
@endsection
