@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">Tambah Pengelola Baru</h6>
                            <div class="ms-auto">
                                <a href="{{ route('pengelolas.index') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Kembali ke Daftar Pengelola
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="row">
            <div class="col-12">
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terdapat kesalahan pada form:</strong>
                        </div>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Main Form Card -->
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-tie me-2 text-primary"></i>
                            <h6 class="mb-0">Informasi Pengelola</h6>
                        </div>
                        <p class="text-sm mb-0 mt-1">Lengkapi informasi pengelola. Akun login akan dibuat otomatis.</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengelolas.store') }}" method="POST" enctype="multipart/form-data"
                            id="pengelolaForm">
                            @csrf

                            <div class="row">
                                <!-- Personal Information Section -->
                                <div class="col-md-8">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                                        <i class="fas fa-user me-1"></i>
                                        Informasi Pribadi
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">
                                                    Nama Lengkap <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="nama"
                                                    class="form-control @error('nama') is-invalid @enderror" id="nama"
                                                    value="{{ old('nama') }}" placeholder="Masukkan nama lengkap"
                                                    required>
                                                @error('nama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">
                                                    Email <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    value="{{ old('email') }}" placeholder="contoh@email.com" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Email ini akan digunakan untuk login
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="no_telp_pengelola" class="form-label">
                                                    No. Telepon <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="no_telp_pengelola"
                                                    class="form-control @error('no_telp_pengelola') is-invalid @enderror"
                                                    id="no_telp_pengelola" value="{{ old('no_telp_pengelola') }}"
                                                    placeholder="628xx-xxxx-xxxx" required>
                                                @error('no_telp_pengelola')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Awali nomor HP dengan <strong>62</strong> (bukan 0)
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status"
                                                    class="form-control @error('status') is-invalid @enderror"
                                                    id="status">
                                                    <option value="aktif"
                                                        {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="non-aktif"
                                                        {{ old('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif
                                                    </option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
>>>>>>> d926ed0a6ff670f876fb98f034c1c996afd3b854
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">
                                            Alamat <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" rows="3"
                                            placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                            rows="4" placeholder="Deskripsi tambahan tentang pengelola (opsional)">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Photo Upload Section -->
                                <div class="col-md-4">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                                        <i class="fas fa-camera me-1"></i>
                                        Foto Profil
                                    </h6>

                                    <div class="mb-3">
                                        <label for="foto" class="form-label">Upload Foto</label>
                                        <input type="file" name="foto"
                                            class="form-control @error('foto') is-invalid @enderror" id="foto"
                                            accept="image/jpeg,image/png,image/jpg">
                                        @error('foto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Format: JPG, JPEG, PNG. Maksimal 2MB
                                        </div>
                                    </div>

                                    <!-- Image Preview -->
                                    <div class="mb-3">
                                        <div class="avatar avatar-xxl position-relative" id="imagePreviewContainer"
                                            style="display: none;">
                                            <img src="" alt="Preview" class="w-100 border-radius-lg shadow-sm"
                                                id="imagePreview">
                                            <button type="button"
                                                class="btn btn-sm btn-icon-only bg-gradient-light position-absolute top-0 end-0 mb-n2 me-n2"
                                                onclick="removeImagePreview()">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Info Card -->
                                    <div
                                        class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                                        <img class="w-10 me-3 mb-0" src="{{ asset('assets/images/info.png') }}"
                                            alt="info">
                                        <div>
                                            <h6 class="mb-0">Info</h6>
                                            <p class="text-sm mb-0">Password default akan digenerate otomatis dan dikirim
                                                via email.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <hr class="horizontal dark">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('pengelolas.index') }}" class="btn btn-light me-2">
                                            <i class="fas fa-times me-1"></i>
                                            Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-1"></i>
                                            Simpan Pengelola
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Image preview functionality
                const fotoInput = document.getElementById('foto');
                const imagePreview = document.getElementById('imagePreview');
                const imagePreviewContainer = document.getElementById('imagePreviewContainer');

                fotoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreviewContainer.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });

<<<<<<< HEAD
    // Phone number formatting
    // const phoneInput = document.getElementById('no_telp_pengelola');
    // phoneInput.addEventListener('input', function(e) {
    //     let value = e.target.value.replace(/\D/g, '');
    //     if (value.length > 0) {
    //         if (value.startsWith('0')) {
    //             // Format: 08xx-xxxx-xxxx
    //             value = value.replace(/(\d{4})(\d{4})(\d{4})/, '$1-$2-$3');
    //         } else if (value.startsWith('62')) {
    //             // Format: +62xxx-xxxx-xxxx
    //             value = '+' + value.replace(/(\d{2})(\d{3})(\d{4})(\d{4})/, '$1$2-$3-$4');
    //         }
    //     }
    //     e.target.value = value;
    // });
=======
                // Phone number formatting
                const phoneInput = document.getElementById('no_telp_pengelola');
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        if (value.startsWith('0')) {
                            // Format: 08xx-xxxx-xxxx
                            value = value.replace(/(\d{4})(\d{4})(\d{4})/, '$1-$2-$3');
                        } else if (value.startsWith('62')) {
                            // Format: +62xxx-xxxx-xxxx
                            value = '+' + value.replace(/(\d{2})(\d{3})(\d{4})(\d{4})/, '$1$2-$3-$4');
                        }
                    }
                    e.target.value = value;
                });

                // Form validation
                const form = document.getElementById('pengelolaForm');
                const submitBtn = document.getElementById('submitBtn');

                form.addEventListener('submit', function(e) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
                });

                // Auto-generate email from name
                const namaInput = document.getElementById('nama');
                const emailInput = document.getElementById('email');

                namaInput.addEventListener('blur', function() {
                    if (this.value && !emailInput.value) {
                        const emailSuggestion = this.value.toLowerCase()
                            .replace(/\s+/g, '.')
                            .replace(/[^a-z0-9.]/g, '') + '@company.com';
                        emailInput.placeholder = 'Contoh: ' + emailSuggestion;
                    }
                });
            });

            function removeImagePreview() {
                document.getElementById('foto').value = '';
                document.getElementById('imagePreviewContainer').style.display = 'none';
            }
        </script>
    @endpush
>>>>>>> d926ed0a6ff670f876fb98f034c1c996afd3b854

    @push('styles')
        <style>
            .form-control:focus {
                border-color: #5e72e4;
                box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
            }

            .card-header {
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .avatar-xxl {
                width: 120px;
                height: 120px;
            }

            .form-text {
                font-size: 0.75rem;
                color: #8392ab;
            }

            .alert {
                border: none;
                border-radius: 0.5rem;
            }

            .btn-icon-only {
                width: 2rem;
                height: 2rem;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .is-invalid {
                border-color: #fd5c70;
            }

            .invalid-feedback {
                display: block;
                font-size: 0.875rem;
                color: #fd5c70;
            }
        </style>
    @endpush
@endsection
