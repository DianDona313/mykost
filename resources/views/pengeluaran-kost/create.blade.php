@extends('layouts.admin.app')

@section('title', 'Tambah Pengeluaran Kost')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Pengeluaran Kost</h3>
                    <div class="card-tools">
                        <a href="{{ route('pengeluaran-kost.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('pengeluaran-kost.store') }}" method="POST" enctype="multipart/form-data"
                    id="pengeluaranForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="properti_id">Properti <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('properti_id') is-invalid @enderror"
                                        id="properti_id" name="properti_id" required>
                                        <option value="">-- Pilih Properti --</option>
                                        @foreach($properties as $property)
                                        <option value="{{ $property->id }}" {{ old('properti_id')==$property->id ?
                                            'selected' : '' }}>
                                            {{ $property->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('properti_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kategori_pengeluaran_id">Kategori Pengeluaran <span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 @error('kategori_pengeluaran_id') is-invalid @enderror"
                                        id="kategori_pengeluaran_id" name="kategori_pengeluaran_id" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($kategoriPengeluarans as $kategori)
                                        <option value="{{ $kategori->id }}" {{
                                            old('kategori_pengeluaran_id')==$kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_pengeluaran_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keperluan">Keperluan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('keperluan') is-invalid @enderror"
                                        id="keperluan" name="keperluan" value="{{ old('keperluan') }}"
                                        placeholder="Masukkan keperluan pengeluaran" required>
                                    @error('keperluan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                        id="jumlah" name="jumlah" value="{{ old('jumlah') }}" placeholder="0" min="0"
                                        step="0.01" required>
                                    @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_pengeluaran">Tanggal Pengeluaran <span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control @error('tanggal_pengeluaran') is-invalid @enderror"
                                        id="tanggal_pengeluaran" name="tanggal_pengeluaran"
                                        value="{{ old('tanggal_pengeluaran', date('Y-m-d')) }}" required>
                                    @error('tanggal_pengeluaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="pending" {{ old('status')=='pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="approved" {{ old('status')=='approved' ? 'selected' : '' }}>
                                            Approved</option>
                                        <option value="rejected" {{ old('status')=='rejected' ? 'selected' : '' }}>
                                            Rejected</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bukti_pengeluaran">Bukti Pengeluaran</label>
                                    <input type="file"
                                        class="form-control-file @error('bukti_pengeluaran') is-invalid @enderror"
                                        id="bukti_pengeluaran" name="bukti_pengeluaran" accept=".jpg,.jpeg,.png,.pdf">
                                    <small class="form-text text-muted">
                                        Format: JPG, JPEG, PNG, PDF. Maksimal 5MB.
                                    </small>
                                    @error('bukti_pengeluaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                        id="keterangan" name="keterangan" rows="3"
                                        placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Preview Jumlah</h5>
                                        <p class="card-text">
                                            <span class="h4" id="jumlahPreview">Rp 0</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <a href="{{ route('pengeluaran-kost.index') }}" class="btn btn-warning">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder');
        }
    });

    // Format currency preview
    $('#jumlah').on('input', function() {
        var value = $(this).val();
        if (value) {
            var formatted = formatRupiah(value);
            $('#jumlahPreview').text(formatted);
        } else {
            $('#jumlahPreview').text('Rp 0');
        }
    });

    // File preview
    $('#bukti_pengeluaran').change(function() {
        var file = this.files[0];
        if (file) {
            var fileSize = (file.size / 1024 / 1024).toFixed(2);
            if (fileSize > 5) {
                Swal.fire('Peringatan', 'Ukuran file maksimal 5MB', 'warning');
                $(this).val('');
                return;
            }
        }
    });

    // Form validation
    $('#pengeluaranForm').submit(function(e) {
        var isValid = true;
        var errorMessages = [];

        // Check required fields
        if (!$('#properti_id').val()) {
            errorMessages.push('Properti harus dipilih');
            isValid = false;
        }

        if (!$('#kategori_pengeluaran_id').val()) {
            errorMessages.push('Kategori pengeluaran harus dipilih');
            isValid = false;
        }

        if (!$('#keperluan').val().trim()) {
            errorMessages.push('Keperluan harus diisi');
            isValid = false;
        }

        if (!$('#jumlah').val() || parseFloat($('#jumlah').val()) <= 0) {
            errorMessages.push('Jumlah harus diisi dan lebih dari 0');
            isValid = false;
        }

        if (!$('#tanggal_pengeluaran').val()) {
            errorMessages.push('Tanggal pengeluaran harus diisi');
            isValid = false;
        }

        if (!$('#status').val()) {
            errorMessages.push('Status harus dipilih');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            Swal.fire('Validasi Error', errorMessages.join('<br>'), 'error');
        }
    });
});

function formatRupiah(angka) {
    var number_string = angka.toString().replace(/[^,\d]/g, '');
    var split = number_string.split(',');
    var sisa = split[0].length % 3;
    var rupiah = split[0].substr(0, sisa);
    var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        var separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return 'Rp ' + rupiah;
}
</script>
@endpush