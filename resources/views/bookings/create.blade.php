@extends('layouts.admin.app')
@section('content')
    <div class="container">
        <h2>Tambah Booking</h2>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf

                    {{-- <div class="mb-3">
                        <label for="property_id" class="form-label">Kost</label>
                        <select name="property_id" id="property_id" class="form-control">
                            <option value="">Pilih Kost</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->nama }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="mb-3">
                        <label for="property_id" class="form-label">Kost</label>
                        <select name="property_id" id="property_id" class="form-control">
                            <option value="">Pilih Kost</option>
                            @if ($properties && count($properties) > 0)
                                @foreach ($properties as $property)
                                    <option value="{{ $property->id }}">{{ $property->nama }}</option>
                                @endforeach
                            @else
                                <option value="">Tidak ada data properti</option>
                            @endif
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="penyewa_id" class="form-label">Penyewa</label>
                        <select name="penyewa_id" class="form-control">
                            <option value="">Pilih Penyewa</option>
                            @foreach ($penyewas as $penyewa)
                                <option value="{{ $penyewa->id }}">{{ $penyewa->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="room_id" class="form-label">Kamar</label>
                        <select name="room_id" id="room_id" class="form-control" required>
                            <option value="">Pilih Kamar</option>
                            {{-- Akan diisi lewat JavaScript --}}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="duration" class="form-label">Durasi Sewa</label>
                        <select name="duration" id="duration" class="form-control" required>
                            <option value="">Pilih Durasi Sewa</option>
                            <option value="12">1 Tahun</option>
                            <option value="6">6 Bulan</option>
                            <option value="3">3 Bulan</option>
                            <option value="1">1 Bulan</option>
                        </select>
                    </div>

                    <div class="d-flex gap-3 mb-3">
                        <div class="flex-fill">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>
                        <div class="flex-fill">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="pending">Tertunda</option>
                            <option value="confirmed">Terkonfirmasi</option>
                            <option value="canceled">Batalkan</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Booking</button>
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk load kamar berdasarkan properti dan otomatis hitung tanggal akhir --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const propertySelect = document.getElementById('property_id');
            const roomSelect = document.getElementById('room_id');
            const durationSelect = document.getElementById('duration');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // Load kamar berdasarkan kost
            propertySelect.addEventListener('change', function() {
                const propertyId = this.value;
                roomSelect.innerHTML = '<option value="">Pilih Kamar</option>';

                if (propertyId) {
                    fetch(`/kamar-by-kost/${propertyId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length === 0) {
                                roomSelect.innerHTML =
                                    '<option value="">Tidak ada kamar tersedia</option>';
                            } else {
                                data.forEach(room => {
                                    const option = document.createElement('option');
                                    option.value = room.id;
                                    option.textContent =
                                        `${room.room_name} - Rp ${new Intl.NumberFormat('id-ID').format(room.harga)}`;
                                    roomSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Gagal memuat data kamar.');
                        });
                }
            });

            // Fungsi hitung tanggal akhir berdasarkan durasi dan tanggal mulai
            function updateEndDate() {
                const startDateValue = startDateInput.value;
                const durationValue = durationSelect.value;

                if (startDateValue && durationValue) {
                    const startDate = new Date(startDateValue);
                    // Tambahkan bulan sesuai durasi
                    const durationMonths = parseInt(durationValue);

                    // Tambah bulan ke startDate
                    let endDate = new Date(startDate);
                    endDate.setMonth(endDate.getMonth() + durationMonths);

                    // Kurangi 1 hari supaya tanggal akhir itu tanggal terakhir masa sewa
                    endDate.setDate(endDate.getDate() - 1);

                    // Format tanggal yyyy-mm-dd untuk input date
                    const yyyy = endDate.getFullYear();
                    const mm = String(endDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(endDate.getDate()).padStart(2, '0');
                    const formattedEndDate = `${yyyy}-${mm}-${dd}`;

                    endDateInput.value = formattedEndDate;
                } else {
                    endDateInput.value = '';
                }
            }

            startDateInput.addEventListener('change', updateEndDate);
            durationSelect.addEventListener('change', updateEndDate);
        });
    </script>
@endsection
