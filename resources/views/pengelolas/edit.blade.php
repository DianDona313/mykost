@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Pengelola</h2>

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
                <form action="{{ route('pengelolas.update', $pengelola->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" id="nama" value="{{ $pengelola->nama }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="no_telp_pengelola" class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp_pengelola" class="form-control" id="no_telp_pengelola" value="{{ $pengelola->no_telp_pengelola }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" id="alamat" value="{{ $pengelola->alamat }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control" id="foto">
                        <img src="{{ asset('storage/' . $pengelola->foto) }}" width="100">
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3">{{ $pengelola->deskripsi }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
