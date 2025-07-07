@extends('layouts.admin.app')
@section('title', 'Edit Profil')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-2"></i>Edit Profil
                            @if (auth()->user()->hasRole('admin'))
                                <span class="badge badge-primary ml-2">Admin</span>
                            @elseif(auth()->user()->hasRole('Pengelola'))
                                <span class="badge badge-info ml-2">Pengelola</span>
                            @endif
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('profile.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group text-center">
                                        <label>Foto Profil</label>
                                        <div class="mb-3">
                                            @if ($profileData->foto)
                                                <img id="preview-image" src="{{ asset('storage/' . $profileData->foto) }}"
                                                    alt="Foto Profil"
                                                    style="width: 200px; height: 200px; object-fit: cover; border-radius: 50%;">
                                            @else
                                                <img id="preview-image" src="{{ asset('images/default-avatar.png') }}"
                                                    alt="Default Avatar"
                                                    style="width: 200px; height: 200px; object-fit: cover; border-radius: 50%;">
                                            @endif
                                        </div>

                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('foto') is-invalid @enderror" id="foto"
                                                name="foto" accept="image/*" onchange="previewImage(event)">
                                            <label class="custom-file-label" for="foto">Pilih foto...</label>
                                        </div>

                                        @error('foto')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        <small class="text-muted">Format: JPEG, PNG, JPG. Maksimal 2MB</small>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nama">Nama <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('nama') is-invalid @enderror" id="nama"
                                                    name="nama"
                                                    value="{{ old('nama', $profileData->nama ?? $profileData->name) }}"
                                                    required>
                                                @error('nama')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_hp">No. HP <span class="text-danger">*</span></label>

                                                @if (auth()->user()->hasRole('Pengelola'))
                                                    <input type="text"
                                                        class="form-control @error('no_telp_pengelola') is-invalid @enderror"
                                                        id="no_telp_pengelola" name="no_telp_pengelola"
                                                        value="{{ old('no_telp_pengelola', $profileData->no_telp_pengelola) }}"
                                                        required>
                                                    @error('no_telp_pengelola')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @elseif(auth()->user()->hasRole('Penyewa'))
                                                    <input type="text"
                                                        class="form-control @error('no_hp') is-invalid @enderror"
                                                        id="no_hp" name="no_hp"
                                                        value="{{ old('no_hp', $profileData->no_hp) }}" required>
                                                    @error('no_hp')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    @if (auth()->user()->hasRole('Penyewa'))
                                        <div class="form-group">
                                            <label for="jenis_kelamin">Jenis Kelamin <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                                id="jenis_kelamin" name="jenis_kelamin" required>
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="Laki-laki"
                                                    {{ old('jenis_kelamin', $profileData->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                                    Laki-laki</option>
                                                <option value="Perempuan"
                                                    {{ old('jenis_kelamin', $profileData->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                                    Perempuan</option>
                                            </select>
                                            @error('jenis_kelamin')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <!-- Tampilkan tapi tidak bisa diubah -->
                                            <input type="email" class="form-control" id="email_display"
                                                value="{{ auth()->user()->email }}" disabled>

                                            <!-- Hidden input agar tetap dikirim -->
                                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                        </div>

                                    <div class="form-group">
                                        <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                            required>{{ old('alamat', $profileData->alamat) }}</textarea>
                                        @error('alamat')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi"
                                            rows="4" placeholder="Masukkan deskripsi tentang diri Anda...">{{ old('deskripsi', $profileData->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview-image');
            const label = document.querySelector('.custom-file-label');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                };

                reader.readAsDataURL(input.files[0]);
                label.textContent = input.files[0].name;
            }
        }

        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
@endsection
