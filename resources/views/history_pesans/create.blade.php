@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah History Pesan</h1>

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
            <form action="{{route('history_pesans.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="penyewa_id" class="form-label">Penyewa</label>
                    <select name="penyewa_id" class="form-select" id="penyewa_id" required>
                        <option value="">Pilih Penyewa</option>
                        @foreach ($penyewas as $penyewa)
                            <option value="{{ $penyewa->id }}">{{ $penyewa->nama }}</option>
                        @endforeach
                    </select>
                </div>
                

                <div class="mb-3">
                    <label class="form-label">Pesan</label>
                    <textarea name="pesan" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    {{-- <input type="text" name="pesan" class="form-control"> --}}
                    <textarea name="deskripsi" class="form-control"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control">
                    </div>
                
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" required>
                    </div>
                </div>
                

                <div class="mb-3">
                    <label class="form-label">Foto (Opsional)</label>
                    <input type="file" name="foto" class="form-control">
                </div>

                <input type="hidden" name="created_by" value="1"> <!-- Gantilah dengan user yang sedang login -->

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('history_pesans.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
