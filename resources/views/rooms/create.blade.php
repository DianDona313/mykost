@extends('layouts.admin.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h2 class="" style="color: #FDE5AF;">Tambah Kamar</h2>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Properti</label>
                        <select name="properti_id" class="form-control" required>
                            <option value="">-- Pilih Properti --</option>
                            @foreach ($propertis as $properti)
                                <option value="{{ $properti->id }}">{{ $properti->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kamar</label>
                        <input type="text" name="room_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="room_deskription" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ketersediaan</label>
                        <select name="is_available" class="form-control">
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fasilitas</label><br>
                        @foreach ($fasilitas as $f)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                    value="{{ $f->id }}" id="fasilitas{{ $f->id }}">
                                <label class="form-check-label"
                                    for="fasilitas{{ $f->id }}">{{ $f->nama }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Kamar</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
