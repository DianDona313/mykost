@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Detail Peraturan Kost</h2>

        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h5 class="card-title">Nama Jenis Kost</h5>
                    <p class="card-text">{{ $peraturan->nama }}</p>
                </div>

                <div class="mb-3">
                    <h5 class="card-title">Deskripsi</h5>
                    <p class="card-text">{{ $peraturan->deskripsi }}</p>
                </div>

                <a href="{{ route('peraturans.index') }}" class="btn btn-secondary">Kembali</a>
                @can('peraturan-edit')
                    <a href="{{ route('peraturans.edit', $peraturan->id) }}" class="btn btn-warning">Edit</a>
                @endcan
                @can('peraturan-delete')
                    <form action="{{ route('peraturans.destroy', $peraturan->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
@endsection
