@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h2 class="mb-4" style="color: #FDE5AF;">Edit Kamar</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Properti --}}
                <div class="mb-3">
                    <label class="form-label">Properti</label>
                    <select name="properti_id" class="form-control" required>
                        @foreach($propertis as $properti)
                            <option value="{{ $properti->id }}" {{ $room->properti_id == $properti->id ? 'selected' : '' }}>
                                {{ $properti->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Kamar --}}
                <div class="mb-3">
                    <label class="form-label">Nama Kamar</label>
                    <input type="text" name="room_name" class="form-control" value="{{ old('room_name', $room->room_name) }}" required>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="room_deskription" class="form-control" rows="3">{{ old('room_deskription', $room->room_deskription) }}</textarea>
                </div>

                {{-- Harga --}}
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control" value="{{ old('harga', $room->harga) }}" required>
                </div>

                {{-- Ketersediaan --}}
                <div class="mb-3">
                    <label class="form-label">Ketersediaan</label>
                    <select name="is_available" class="form-control">
                        <option value="Ya" {{ $room->is_available ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ !$room->is_available ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                {{-- Fasilitas --}}
                <div class="mb-3">
                    <label class="form-label">Fasilitas</label>
                    <div class="row">
                        @foreach($fasilitas as $item)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $item->id }}"
                                        id="fasilitas_{{ $item->id }}"
                                        {{ in_array($item->id, $room->fasilitas->pluck('id')->toArray()) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_{{ $item->id }}">{{ $item->nama }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Foto --}}
                <div class="mb-3">
                    <label class="form-label">Foto (opsional, untuk ganti)</label>
                    <input type="file" name="foto" class="form-control">
                    @if($room->foto)
                        <div class="mt-2">
                            <p>Foto Saat Ini:</p>
                            <img src="{{ asset('storage/' . $room->foto) }}" width="150" class="rounded shadow-sm" alt="Foto Kamar">
                        </div>
                    @endif
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
