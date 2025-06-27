@extends('layouts.admin.app')

@section('title', 'Edit Pengeluaran Kost')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Pengeluaran Kost</h3>
                    <div class="card-tools">
                        <a href="{{ route('pengeluaran-kost.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('pengeluaran-kost.update', $pengeluaranKost->id) }}" method="POST" enctype="multipart/form-data" id="pengeluaranForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="properti_id">Properti <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('properti_id') is-invalid @enderror" 
                                            id="properti_id" name="properti_id" required>
                                        <option value="">-- Pilih Properti --</option>
                                        @foreach($properties as $property)
                                            <option value="{{ $property->id }}" 
                                                {{ (old('properti_id') ?? $pengeluaranKost->properti_id) == $property->id ? 'selected' : '' }}>
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
                                    <label for="kategori_pengeluaran_id">Kategori Pengeluaran <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('kategori_pengeluaran_id') is-invalid @enderror" 
                                            id="kategori_pengeluaran_id" name="kategori_pengeluaran_id" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($kategoriPengeluarans as $kategori)
                                            <option value="{{ $kategori->id }}" 
                                                {{ (old('kategori_pengeluaran_id') ?? $pengeluaranKost->kategori_pengeluaran_id) == $kategori->id ? 'selected' : '' }}>
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
                                           id="keperluan" name="keperluan" 
                                           value="{{ old('keperluan') ?? $pengeluaranKost->keperluan }}" 
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
                                           id="jumlah" name="jumlah" 
                                           value="{{ old('jumlah') ?? $pengeluaranKost->jumlah }}" 
                                           placeholder="0" min="0" step="0.01" required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_pengeluaran">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_pengeluaran') is-invalid @enderror" 
                                           id="tanggal_pengeluaran" name="tanggal_pengeluaran" 
                                           value="{{ old('tanggal_pengeluaran') ?? $pengeluaranKost->tanggal_pengeluaran }}" required>
                                    @error('tanggal_pengeluaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="pending" {{ (old('status') ?? $pengeluaranKost->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ (old('status') ?? $pengeluaranKost->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ (old('status') ?? $pengeluaranKost->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                                    @if($pengeluaranKost->bukti_pengeluaran)
                                        <div class="mb-2">
                                            <small class="text-muted">File saat ini:</small>
                                            <a href="{{ asset('storage/' . $pengeluaranKost->bukti_pengeluaran) }}" 
                                               target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat File
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control-file @error('bukti_pengeluaran') is-invalid @enderror" 
                                           id="bukti_pengeluaran" name="bukti_pengeluaran" 
                                           accept=".jpg,.jpeg,.png,.pdf">
                                    <small class="form-text text-muted">
                                        Format: JPG, JPEG, PNG, PDF. Maksimal 5MB. Kosongkan jika tidak ingin mengubah.
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
                                              placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan') ?? $pengeluaranKost->keterangan }}</textarea>
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
                                            <span class="h4" id="jumlahPreview">{{ $pengeluaranKost->jumlah_format }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-info">
                                    <div class="card-body">
                                        <h5 class="card-title">Info Pembuatan</h5>
                                        <p class="card-text">
                                            <strong>Dibuat oleh:</strong> {{ $pengeluaranKost->creator->name ?? '-' }}<br>
                                            <strong>Tanggal dibuat:</strong> {{ $pengeluaranKost->created_at }}<br>
                                            @if($pengeluaranKost->updated_at != $pengeluaranKost->created_at)
                                                <strong>Terakhir diubah:</strong> {{ $pengeluaranKost->updated_at }}
                                                @if($pengeluaranKost->updater)
                                                    oleh {{ $pengeluaranKost->updater->name }}
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
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