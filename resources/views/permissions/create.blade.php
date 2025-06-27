{{-- resources/views/permissions/create.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Create Permission')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Permission</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('permissions.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label"><strong>Permission Name:</strong></label>
                                    <input type="text" name="name" id="name" placeholder="Enter permission name (e.g., user-create, post-edit)" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                    <small class="form-text text-muted">
                                        Use kebab-case format (e.g., user-create, post-edit, admin-delete)
                                    </small>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Permission Naming Convention:</h6>
                                    <ul class="mb-0">
                                        <li><strong>CRUD Operations:</strong> resource-action (e.g., user-create, user-edit, user-delete)</li>
                                        <li><strong>Special Actions:</strong> descriptive names (e.g., manage-settings, view-reports)</li>
                                        <li><strong>Module Access:</strong> module-access (e.g., admin-access, dashboard-access)</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Permission
                                </button>
                                <a class="btn btn-secondary" href="{{ route('permissions.index') }}">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection