@extends('layouts.admin.app')

@section('title', 'Ubah Password')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-key mr-2"></i>Ubah Password
                    </h3>
                    <a href="{{ route('profile.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Form di sebelah kiri -->
                        <div class="col-md-6">
                            <form action="{{ route('profile.change-password') }}" method="POST">
                                @csrf
                                @method('PUT')

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="current_password">Password Saat Ini <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" name="current_password" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                                <i class="fas fa-eye" id="current_password_icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('current_password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="new_password">Password Baru <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                               id="new_password" name="new_password" required minlength="8">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                                <i class="fas fa-eye" id="new_password_icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('new_password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="text-muted">Password minimal 8 karakter</small>
                                </div>

                                <div class="form-group">
                                    <label for="new_password_confirmation">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                               id="new_password_confirmation" name="new_password_confirmation" required minlength="8">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                                <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('new_password_confirmation')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="text-muted">Masukkan kembali password baru untuk konfirmasi</small>
                                </div>

                                <div class="text-right mt-4">
                                    <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tips keamanan di sebelah kanan -->
                        <div class="col-md-6">
                            <div class="alert alert-info h-100">
                                <h5><i class="fas fa-shield-alt mr-2"></i>Tips Keamanan</h5>
                                <ul class="mt-3 mb-0">
                                    <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                                    <li>Hindari menggunakan informasi pribadi yang mudah ditebak</li>
                                    <li>Jangan gunakan password yang sama dengan akun lain</li>
                                    <li>Ganti password secara berkala untuk keamanan tambahan</li>
                                    <li>Gunakan pengelola kata sandi (password manager) jika perlu</li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- /.row -->
                </div> <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.getElementById('new_password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('new_password').value;
        const confirmation = this.value;

        if (confirmation && password !== confirmation) {
            this.setCustomValidity('Password tidak cocok');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endsection
