@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah History Pengeluaran</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('history_pengeluarans.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                
            <!-- Properti -->
            <div class="mb-3">
                <label class="form-label">Properti</label>
                <select name="property_id" class="form-control" required>
                    <option value="">-- Pilih Properti --</option>
                    @foreach ($properties as $properti)
                        <option value="{{ $properti->id }}">{{ $properti->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kategori Pengeluaran -->
            <div class="mb-3">
                <label class="form-label">Kategori Pengeluaran</label>
                <select name="kategori_pengeluaran_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategori_pengeluarans as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Nama Pengeluaran -->
            <div class="mb-3">
                <label class="form-label">Nama Pengeluaran</label>
                <input type="text" name="nama_pengeluaran" class="form-control" required>
            </div>

            <!-- Jumlah -->
            <div class="mb-3">
                <label class="form-label">Jumlah Pengeluaran</label>
                <input type="number" name="jumlah_pengeluaran" class="form-control" required>
            </div>

            <!-- Tanggal -->
            <div class="mb-3">
                <label class="form-label">Tanggal Pengeluaran</label>
                <input type="date" name="tanggal_pengeluaran" class="form-control" required>
            </div>

            <!-- Penanggung Jawab -->
            <div class="mb-3">
                <label class="form-label">Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab" class="form-control" required>
                {{-- <select name="penanggung_jawab" class="form-control" required>
                    <option value="">-- Pilih Penanggung Jawab --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select> --}}
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control"></textarea>
            </div>

            <!-- Foto -->
            <div class="mb-3">
                <label class="form-label">Foto (Opsional)</label>
                <input type="file" name="foto" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('history_pengeluarans.index') }}" class="btn btn-secondary">Batal</a>
            </div>

               
            </form>
        </div>
    </div>
</div>
@endsection
