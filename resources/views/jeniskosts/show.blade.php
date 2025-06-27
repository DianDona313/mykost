@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Detail Jenis Kost</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Nama Jenis Kost</h6>
                    <p class="mb-0">{{ $jeniskost->nama }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Deskripsi</h6>
                    <p class="mb-0">{{ $jeniskost->deskripsi }}</p>
                </div>

                <a href="{{ route('jeniskosts.index') }}" class="btn btn-secondary">Kembali</a>
                @can('jeniskost-edit')
                    <a href="{{ route('jeniskosts.edit', $jeniskost->id) }}" class="btn btn-warning">Edit</a>
                @endcan
                @can('jeniskost-delete')
                    <form action="{{ route('jeniskosts.destroy', $jeniskost->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
@endsection
