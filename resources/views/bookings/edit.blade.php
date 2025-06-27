@extends('layouts.admin.app')
@section('content')
    <div class="container">
        
        <h2 class="mb-4" style="color: #FDE5AF;">Edit Booking</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="property_id" class="form-label">Properti</label>
                        <select name="property_id" class="form-control">
                            @foreach ($properties as $property)
                                <option value="{{ $property->id }}" {{ $booking->property_id == $property->id ? 'selected' : '' }}>
                                    {{ $property->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="penyewa_id" class="form-label">Penyewa</label>
                        <select name="penyewa_id" class="form-control">
                            @foreach ($penyewas as $penyewa)
                                <option value="{{ $penyewa->id }}" {{ $booking->penyewa_id == $penyewa->id ? 'selected' : '' }}>
                                    {{ $penyewa->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="room_id" class="form-label">Kamar</label>
                        <select name="room_id" class="form-control" required>
                            <option value="">Pilih Kamar</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}" {{ $booking->room_id == $room->id ? 'selected' : '' }}>
                                    {{ $room->room_name }} - Rp {{ number_format($room->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $booking->start_date }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $booking->end_date }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="canceled" {{ $booking->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Booking</button>
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
