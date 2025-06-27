@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit History Pesan</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header">
                <strong>Form Edit History Pesan</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('history_pesans.update', $historyPesan->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Penyewa ID</label>
                        <input type="number" name="penyewa_id" class="form-control" value="{{ $historyPesan->penyewa_id }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pesan</label>
                        <input type="text" name="pesan" class="form-control" value="{{ $historyPesan->pesan }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ $historyPesan->deskripsi }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control"
                            value="{{ $historyPesan->tanggal_mulai }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control"
                            value="{{ $historyPesan->tanggal_selesai }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto (Opsional)</label>
                        <input type="file" name="foto" class="form-control">
                        @if ($historyPesan->foto)
                            <p class="mt-2 mb-0">Foto saat ini:</p>
                            <img src="{{ asset('storage/' . $historyPesan->foto) }}" alt="Foto"
                                class="img-thumbnail mt-1" style="max-width: 150px;">
                        @endif
                    </div>

                    <input type="hidden" name="updated_by" value="1"> <!-- Gantilah dengan user yang sedang login -->

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('history_pesans.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
