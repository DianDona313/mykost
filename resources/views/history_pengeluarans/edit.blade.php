@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit History Pengeluaran</h1>

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
            <form action="{{ route('history_pengeluarans.update', $historyPengeluaran->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Properti -->
                <div class="mb-3">
                    <label class="form-label">Properti</label>
                    <select name="property_id" class="form-control" required>
                        <option value="">-- Pilih Properti --</option>
                        @foreach ($properties as $properti)
                            <option value="{{ $properti->id }}" 
                                @if($properti->id == $historyPengeluaran->property_id) selected @endif>
                                {{ $properti->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kategori Pengeluaran -->
                <div class="mb-3">
                    <label class="form-label">Kategori Pengeluaran</label>
                    <select name="kategori_pengeluaran_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori_pengeluarans as $kategori)
                            <option value="{{ $kategori->id }}" 
                                @if($kategori->id == $historyPengeluaran->kategori_pengeluaran_id) selected @endif>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Pengeluaran -->
                <div class="mb-3">
                    <label class="form-label">Nama Pengeluaran</label>
                    <input type="text" name="nama_pengeluaran" class="form-control" value="{{ $historyPengeluaran->nama_pengeluaran }}" required>
                </div>

                <!-- Jumlah -->
                <div class="mb-3">
                    <label class="form-label">Jumlah Pengeluaran</label>
                    <input type="number" name="jumlah_pengeluaran" class="form-control" value="{{ $historyPengeluaran->jumlah_pengeluaran }}" required>
                </div>

                <!-- Tanggal -->
                <div class="mb-3">
                    <label class="form-label">Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal_pengeluaran" class="form-control" value="{{ $historyPengeluaran->tanggal_pengeluaran }}" required>
                </div>

                <!-- Penanggung Jawab -->
                <div class="mb-3">
                    <label class="form-label">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" class="form-control" value="{{ $historyPengeluaran->penanggung_jawab }}" required>
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control">{{ $historyPengeluaran->deskripsi }}</textarea>
                </div>

                <!-- Foto -->
                <div class="mb-3">
                    <label class="form-label">Foto (Opsional)</label>
                    <input type="file" name="foto" class="form-control">
                    @if ($historyPengeluaran->foto)
                        <p class="mt-2">Foto saat ini:</p>
                        <img src="{{ asset('uploads/' . $historyPengeluaran->foto) }}" class="img-fluid" style="max-width: 150px;">
                    @endif
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
