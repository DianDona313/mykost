@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail History Pesan</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Pesan:</strong> {{ $historyPesan->pesan }}</p>
            <p><strong>Deskripsi:</strong> {{ $historyPesan->deskripsi }}</p>
            <p><strong>Tanggal Mulai:</strong> {{ $historyPesan->tanggal_mulai }}</p>
            <p><strong>Tanggal Selesai:</strong> {{ $historyPesan->tanggal_selesai }}</p>

            @if ($historyPesan->foto)
                <div class="mb-3">
                    <strong>Foto:</strong><br>
                    <img src="{{ asset('storage/' . $historyPesan->foto) }}" alt="Foto" class="img-thumbnail" width="150px">
                </div>
            @endif

            <a href="{{ route('history_pesans.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
