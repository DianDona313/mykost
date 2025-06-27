@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Metode Pembayaran</h2>

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
                <form action="{{ route('metode_pembayarans.update', $metode_pembayaran->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        {{-- <label for="property_id" class="form-label">Property ID</label> --}}
                        <input type="hidden" name="property_id" value="{{ old('property_id', $metode_pembayaran->property_id) }}">
                    </div>
                    
                    

                    <div class="mb-3">
                        <label for="nama_bank" class="form-label">Nama Bank</label>
                        <input type="text" name="nama_bank" class="form-control" id="nama_bank" value="{{ old('nama_bank', $metode_pembayaran->nama_bank) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="no_rek" class="form-label">Nomor Rekening</label>
                        <input type="text" name="no_rek" class="form-control" id="no_rek" value="{{ old('no_rek', $metode_pembayaran->no_rek) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="atas_nama" class="form-label">Atas Nama</label>
                        <input type="text" name="atas_nama" class="form-control" id="atas_nama" value="{{ old('atas_nama', $metode_pembayaran->atas_nama) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('metode_pembayarans.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
