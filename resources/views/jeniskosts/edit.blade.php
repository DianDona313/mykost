@extends('layouts.admin.app')

@section('content')
    <div class="container">
         <h2 class="mb-4" style="color: #FDE5AF;">Edit Jenis Kost</h2>
        <div class="card shadow-sm">
            
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

                <form action="{{ route('jeniskosts.update', $jeniskost->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Jenis Kost</label>
                        <input type="text" name="nama" class="form-control" id="nama" value="{{ $jeniskost->nama }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3" required>{{ $jeniskost->deskripsi }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('jeniskosts.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
