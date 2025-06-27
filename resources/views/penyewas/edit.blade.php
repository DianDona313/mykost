@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Edit Penyewa</h2>
        <div class="card">
            <div class="card-body">
                {{-- Jangan lupa enctype untuk file upload --}}
                <form action="{{ route('penyewas.update', $penyewa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" id="nama"
                            value="{{ $penyewa->nama }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email"
                            value="{{ $penyewa->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="nohp" class="form-label">No HP</label>
                        <input type="text" name="nohp" class="form-control" id="nohp"
                            value="{{ $penyewa->nohp }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" id="alamat"
                            value="{{ $penyewa->alamat }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" id="jenis_kelamin" required>
                            <option value="Laki-laki" {{ $penyewa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="Perempuan" {{ $penyewa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                    </div>

                    {{-- Foto --}}
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Baru (opsional)</label>
                        <input type="file" name="foto" class="form-control" id="foto" accept="image/*">
                    </div>

                    {{-- Preview foto lama jika ada --}}
                    @if ($penyewa->foto)
                        <div class="mb-3">
                            <label class="form-label">Foto Sebelumnya:</label><br>
                            <img src="{{ asset('storage/' . $penyewa->foto) }}" alt="Foto Penyewa" width="120"
                                class="img-thumbnail">
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
