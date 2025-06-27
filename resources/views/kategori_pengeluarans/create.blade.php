@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h2 class="mb-4" style="color: #FDE5AF;">Tambah Kategori Pengeluaran</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('kategori_pengeluarans.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
