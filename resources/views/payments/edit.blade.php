@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h2 class="mb-4" style="color: #FDE5AF;">Edit Pembayaran</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Jumlah Pembayaran</label>
                    <input type="number" name="jumlah" class="form-control" value="{{ $payment->jumlah }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sisa Pembayaran</label>
                    <input type="number" name="sisa_pembayaran" class="form-control" value="{{ $payment->sisa_pembayaran }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="payment_status" class="form-control">
                        <option value="paid" {{ $payment->payment_status == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="pending" {{ $payment->payment_status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="failed" {{ $payment->payment_status == 'failed' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
