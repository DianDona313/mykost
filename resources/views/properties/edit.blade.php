@extends('layouts.admin.app')
@section('title', 'Edit Properti')
@section('content')

    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Edit Properti</h2>
        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('properties.update', $property->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nama">Nama Properti</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $property->nama) }}"
                            required>
                        @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" class="form-control" required>{{ old('alamat', $property->alamat) }}</textarea>
                        @error('alamat')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Peta untuk Pilih Lokasi -->
                    <div class="form-group mt-3">
                        <label for="map">Tentukan Lokasi di Peta</label>
                        <div id="map" style="height: 300px; border-radius: 10px;"></div>
                    </div>

                    <!-- Hidden input untuk simpan koordinat -->
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $property->latitude) }}">
                    <input type="hidden" id="longitude" name="longitude"
                        value="{{ old('longitude', $property->longitude) }}">

                    @error('latitude')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @error('longitude')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="kota">Kota</label>
                        <input type="text" name="kota" class="form-control" value="{{ old('kota', $property->kota) }}"
                            required>
                        @error('kota')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jeniskost_id">Jenis Kost</label>
                        <select name="jeniskost_id" class="form-control" required>
                            <option value="">-- Pilih Jenis Kost --</option>
                            @foreach ($jeniskosts as $jenis)
                                <option value="{{ $jenis->id }}"
                                    {{ old('jeniskost_id', $property->jeniskost_id) == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jeniskost_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="foto">Foto Properti</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        @if ($property->foto)
                            <div class="mt-2">
                                <img src="{{ asset('storage/properti_foto/' . $property->foto) }}" alt="Foto Properti"
                                    width="100" class="img-thumbnail">
                                <small class="text-muted">Foto saat ini</small>
                            </div>
                        @endif
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                        @error('foto')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $property->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="peraturan_id">Peraturan (bisa pilih lebih dari satu)</label>
                        <select name="peraturan_id[]" id="peraturan_id" class="form-control" multiple required>
                            @foreach ($peraturans as $peraturan)
                                <option value="{{ $peraturan->id }}"
                                    {{ in_array($peraturan->id, old('peraturan_id', $selectedPeraturans)) ? 'selected' : '' }}>
                                    {{ $peraturan->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('peraturan_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <div id="metode-pembayaran-container">
                            @if (old('metode_pembayaran'))
                                @foreach (old('metode_pembayaran') as $index => $metode)
                                    <div class="metode-pembayaran-item border p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="metode_pembayaran[{{ $index }}][nama_bank]">Nama
                                                    Bank</label>
                                                <input type="text"
                                                    name="metode_pembayaran[{{ $index }}][nama_bank]"
                                                    class="form-control" value="{{ $metode['nama_bank'] ?? '' }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="metode_pembayaran[{{ $index }}][no_rek]">No.
                                                    Rekening</label>
                                                <input type="text" name="metode_pembayaran[{{ $index }}][no_rek]"
                                                    class="form-control" value="{{ $metode['no_rek'] ?? '' }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="metode_pembayaran[{{ $index }}][atas_nama]">Atas
                                                    Nama</label>
                                                <input type="text"
                                                    name="metode_pembayaran[{{ $index }}][atas_nama]"
                                                    class="form-control" value="{{ $metode['atas_nama'] ?? '' }}" required>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-metode">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach ($property->metode_pembayaran as $index => $metode)
                                    <div class="metode-pembayaran-item border p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="metode_pembayaran[{{ $index }}][nama_bank]">Nama
                                                    Bank</label>
                                                <input type="text"
                                                    name="metode_pembayaran[{{ $index }}][nama_bank]"
                                                    class="form-control" value="{{ $metode->nama_bank }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="metode_pembayaran[{{ $index }}][no_rek]">No.
                                                    Rekening</label>
                                                <input type="text"
                                                    name="metode_pembayaran[{{ $index }}][no_rek]"
                                                    class="form-control" value="{{ $metode->no_rek }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="metode_pembayaran[{{ $index }}][atas_nama]">Atas
                                                    Nama</label>
                                                <input type="text"
                                                    name="metode_pembayaran[{{ $index }}][atas_nama]"
                                                    class="form-control" value="{{ $metode->atas_nama }}" required>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-metode">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-metode" class="btn btn-info btn-sm">Tambah Metode
                            Pembayaran</button>
                        @error('metode_pembayaran')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    <a href="{{ route('properties.index') }}" class="btn btn-secondary">Batal</a>
                </form>

            </div>
        </div>
    </div>
@endsection


@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const defaultLat = parseFloat(document.getElementById('latitude').value) || -2.9761; // fallback: Palembang
        const defaultLng = parseFloat(document.getElementById('longitude').value) || 104.7754;

        const map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        marker.on('dragend', function (e) {
            const latlng = marker.getLatLng();
            document.getElementById('latitude').value = latlng.lat;
            document.getElementById('longitude').value = latlng.lng;
        });
    });
        $(document).ready(function() {
            // Initialize Select2 for peraturan
            $('#peraturan_id').select2({
                placeholder: "Pilih Peraturan",
                allowClear: true
            });

            let metodeIndex = {{ count($property->metode_pembayaran ?? []) }};

            // Add new metode pembayaran
            $('#add-metode').click(function() {
                const html = `
            <div class="metode-pembayaran-item border p-3 mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label for="metode_pembayaran[${metodeIndex}][nama_bank]">Nama Bank</label>
                        <input type="text" name="metode_pembayaran[${metodeIndex}][nama_bank]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="metode_pembayaran[${metodeIndex}][no_rek]">No. Rekening</label>
                        <input type="text" name="metode_pembayaran[${metodeIndex}][no_rek]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="metode_pembayaran[${metodeIndex}][atas_nama]">Atas Nama</label>
                        <input type="text" name="metode_pembayaran[${metodeIndex}][atas_nama]" class="form-control" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <button type="button" class="btn btn-danger btn-sm remove-metode">Hapus</button>
                    </div>
                </div>
            </div>
        `;
                $('#metode-pembayaran-container').append(html);
                metodeIndex++;
            });

            // Remove metode pembayaran
            $(document).on('click', '.remove-metode', function() {
                if ($('.metode-pembayaran-item').length > 1) {
                    $(this).closest('.metode-pembayaran-item').remove();
                } else {
                    alert('Minimal harus ada satu metode pembayaran');
                }
            });
        });
    </script>
@endpush
