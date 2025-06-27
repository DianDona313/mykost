@extends('layouts.admin.app')
@section('content')
    <div class="container">
        <h2 class="mb-4">Detail Fasilitas</h2>

        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h5 class="card-title">Nama Fasilitas</h5>
                    <p class="card-text">{{ $fasilita->nama }}</p>
                </div>
                <div class="mb-3">
                    <h5 class="card-title">Deskripsi</h5>
                    <p class="card-text">{{ $fasilita->deskripsi }}</p>
                </div>
                <a href="{{ route('fasilitas.index') }}" class="btn btn-secondary">Kembali</a>
                @can('fasilitas-edit')
                <a href="{{ route('fasilitas.edit', $fasilita->id) }}" class="btn btn-warning">Edit</a>
                @endcan
                @can('fasilitas-delete')
                <form action="{{ route('fasilitas.destroy', $fasilita->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
                @endcan
            </div>
        </div>
    </div>
@endsection
