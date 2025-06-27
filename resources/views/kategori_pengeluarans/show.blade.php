@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1>Detail Kategori Pengeluaran</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $kategori_pengeluaran->id }}</p>
            <p><strong>Nama:</strong> {{ $kategori_pengeluaran->nama }}</p>
            <p><strong>Deskripsi:</strong> {{ $kategori_pengeluaran->deskripsi }}</p>
            <p><strong>Dibuat oleh:</strong> {{ $kategori_pengeluaran->created_by }}</p>
            <p><strong>Diperbarui oleh:</strong> {{ $kategori_pengeluaran->updated_by }}</p>
        </div>
    </div>
    <a href="{{ route('kategori_pengeluarans.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
