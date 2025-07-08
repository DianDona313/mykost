@extends('layouts.admin.app')

@section('title', 'Tambah Properti')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Tambah Properti Baru</h2>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Properti <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nama"
                                            class="form-control @error('nama') is-invalid @enderror" id="nama"
                                            value="{{ old('nama') }}" required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kota" class="form-label">Kota <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="kota"
                                            class="form-control @error('kota') is-invalid @enderror" id="kota"
                                            value="{{ old('kota') }}" required>
                                        @error('kota')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" rows="2"
                                    required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Input Titik Lokasi di Peta -->
                            <div class="mb-3">
                                <label class="form-label">Tentukan Lokasi di Peta</label>
                                <div id="map" style="height: 300px; border-radius: 10px; overflow: hidden;"></div>
                            </div>

                            <!-- Input tersembunyi untuk dikirim ke controller -->
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                            @error('latitude')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            @error('longitude')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jeniskost_id" class="form-label">Jenis Kost <span
                                                class="text-danger">*</span></label>
                                        <select name="jeniskost_id"
                                            class="form-select @error('jeniskost_id') is-invalid @enderror"
                                            id="jeniskost_id" required>
                                            <option value="">Pilih Jenis Kost</option>
                                            @foreach ($jenisKosts as $jenis)
                                                <option value="{{ $jenis->id }}"
                                                    {{ old('jeniskost_id') == $jenis->id ? 'selected' : '' }}>
                                                    {{ $jenis->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jeniskost_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="foto" class="form-label">Foto <span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="foto"
                                            class="form-control @error('foto') is-invalid @enderror" id="foto"
                                            accept="image/*" required>
                                        <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                        <img id="foto-preview" class="img-thumbnail mt-2 d-none" style="max-width: 200px;">
                                        @error('foto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Metode Pembayaran Section -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-success btn-sm" id="add-payment-method">
                                        <i class="fas fa-plus"></i> Tambah Metode Pembayaran
                                    </button>
                                </div>
                                <small class="form-text text-muted d-block mb-3">
                                    Tambahkan minimal satu metode pembayaran untuk properti ini.
                                </small>

                                <div id="payment-methods-container">
                                    @php
                                        $oldPaymentMethods = old('metode_pembayaran', []);
                                        $paymentCount = count($oldPaymentMethods) > 0 ? count($oldPaymentMethods) : 1;
                                    @endphp

                                    @for ($i = 0; $i < $paymentCount; $i++)
                                        <div class="payment-method-item border rounded p-3 mb-3"
                                            data-index="{{ $i }}">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">Metode Pembayaran {{ $i + 1 }}</h6>
                                                @if ($i > 0)
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove-payment-method">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Bank <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            name="metode_pembayaran[{{ $i }}][nama_bank]"
                                                            class="form-control @error('metode_pembayaran.' . $i . '.nama_bank') is-invalid @enderror"
                                                            value="{{ old('metode_pembayaran.' . $i . '.nama_bank') }}"
                                                            required>
                                                        @error('metode_pembayaran.' . $i . '.nama_bank')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nomor Rekening <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            name="metode_pembayaran[{{ $i }}][no_rek]"
                                                            class="form-control @error('metode_pembayaran.' . $i . '.no_rek') is-invalid @enderror"
                                                            value="{{ old('metode_pembayaran.' . $i . '.no_rek') }}" required>
                                                        @error('metode_pembayaran.' . $i . '.no_rek')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Atas Nama <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            name="metode_pembayaran[{{ $i }}][atas_nama]"
                                                            class="form-control @error('metode_pembayaran.' . $i . '.atas_nama') is-invalid @enderror"
                                                            value="{{ old('metode_pembayaran.' . $i . '.atas_nama') }}"
                                                            required>
                                                        @error('metode_pembayaran.' . $i . '.atas_nama')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                @error('metode_pembayaran')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Peraturan <span class="text-danger">*</span></label>
                                <small class="form-text text-muted d-block mb-2">
                                    Pilih peraturan yang berlaku untuk properti ini.
                                </small>
                                <div class="row">
                                    @foreach ($peraturans as $p)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="peraturan_id[]"
                                                    value="{{ $p->id }}" id="peraturan{{ $p->id }}"
                                                    {{ in_array($p->id, old('peraturan_id', [])) ? 'checked' : '' }}
                                                    {{ $loop->first ? 'required' : '' }}>
                                                <label class="form-check-label" for="peraturan{{ $p->id }}">
                                                    {{ $p->nama }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('peraturan_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('properties.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@endsection

@push('scripts')
    <script>
         document.addEventListener('DOMContentLoaded', function () {
        var defaultLat = {{ old('latitude', -2.9761) }};   // Palembang default
        var defaultLng = {{ old('longitude', 104.7754) }};

        var map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        marker.on('dragend', function (e) {
            var latlng = marker.getLatLng();
            document.getElementById('latitude').value = latlng.lat;
            document.getElementById('longitude').value = latlng.lng;
        });

        // Set input awal
        document.getElementById('latitude').value = defaultLat;
        document.getElementById('longitude').value = defaultLng;
    });
        // Preview foto
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('foto-preview');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Metode Pembayaran Dynamic Form
        let paymentMethodIndex = {{ $paymentCount }};

        // Add payment method
        document.getElementById('add-payment-method').addEventListener('click', function() {
            const container = document.getElementById('payment-methods-container');
            const newPaymentMethod = createPaymentMethodHTML(paymentMethodIndex);
            container.insertAdjacentHTML('beforeend', newPaymentMethod);
            paymentMethodIndex++;
            updatePaymentMethodTitles();
        });

        // Remove payment method
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-payment-method') || e.target.closest(
                '.remove-payment-method')) {
                const paymentItem = e.target.closest('.payment-method-item');
                paymentItem.remove();
                updatePaymentMethodTitles();
            }
        });

        function createPaymentMethodHTML(index) {
            return `
            <div class="payment-method-item border rounded p-3 mb-3" data-index="${index}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Metode Pembayaran ${index + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm remove-payment-method">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Nama Bank <span class="text-danger">*</span></label>
                            <input type="text" name="metode_pembayaran[${index}][nama_bank]"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="text" name="metode_pembayaran[${index}][no_rek]"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Atas Nama <span class="text-danger">*</span></label>
                            <input type="text" name="metode_pembayaran[${index}][atas_nama]"
                                class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        `;
        }

        function updatePaymentMethodTitles() {
            const paymentItems = document.querySelectorAll('.payment-method-item');
            paymentItems.forEach((item, index) => {
                const title = item.querySelector('h6');
                title.textContent = `Metode Pembayaran ${index + 1}`;

                // Update input names to maintain proper indexing
                const inputs = item.querySelectorAll('input');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        input.setAttribute('name', newName);
                    }
                });

                // Hide remove button for first item
                const removeBtn = item.querySelector('.remove-payment-method');
                if (removeBtn) {
                    if (index === 0) {
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'inline-block';
                    }
                }
            });
        }

        // Initialize titles on page load
        updatePaymentMethodTitles();
    </script>
@endpush
