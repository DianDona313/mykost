@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Tambah Pembayaran</h2>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Booking</label>
                        <select name="booking_id" id="booking_id" class="form-select" required>
                            <option value="">Pilih Booking</option>
                            @foreach ($bookings as $booking)
                                <option value="{{ $booking->id }}">
                                    {{ $booking->kode_booking ?? 'Booking #' . $booking->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penyewa</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Pilih Penyewa</option>
                            @foreach ($penyewas as $penyewa)
                                <option value="{{ $penyewa->id }}">{{ $penyewa->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Pembayaran</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sisa Pembayaran</label>
                        <input type="number" name="sisa_pembayaran" id="sisa_pembayaran" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="" disabled selected>Pilih metode pembayaran</option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->nama_bank }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="payment_status" class="form-control">
                            <option value="paid">Lunas</option>
                            <option value="pending">Menunggu</option>
                            <option value="failed">Gagal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Pembayaran</label>
                        <input type="file" name="foto" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#booking_id').on('change', function() {
                console.log("select");
                
                const bookingId = $(this).val();
                const penyewaSelect = $('#user_id');
                const jumlahInput = $('#jumlah');
                const sisaInput = $('#sisa_pembayaran');

                if (!bookingId) return;

                $.ajax({
                    url: `/booking-detail/${bookingId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        
                        // Kosongkan opsi penyewa
                        penyewaSelect.empty();

                        if (data.penyewa) {
                            penyewaSelect.append(
                                $('<option>', {
                                    value: data.penyewa.id,
                                    text: data.penyewa.nama,
                                    selected: true
                                })
                            );
                        } else {
                            penyewaSelect.append(
                                '<option value="">Penyewa tidak ditemukan</option>');
                        }

                        // Isi jumlah dan sisa pembayaran
                        if (data.harga_kamar) {
                            jumlahInput.val(data.harga_kamar);
                            sisaInput.val(0);
                        } else {
                            jumlahInput.val('');
                            sisaInput.val('');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal mengambil data:', error);
                        alert('Terjadi kesalahan saat mengambil data booking.');
                    }
                });
            });
        });
    </script>
@endpush
