{{-- resources/views/permissions/edit.blade.php --}}
@extends('layouts.admin.app')


@section('title', 'Edit Permission')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Permission</h4>
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

                    <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label"><strong>Permission Name:</strong></label>
                                    <input type="text" name="name" id="name" placeholder="Enter permission name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $permission->name) }}">
                                    <small class="form-text text-muted">
                                        Use kebab-case format (e.g., user-create, post-edit, admin-delete)
                                    </small>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Important Note:</h6>
                                    <p class="mb-0">
                                        Changing the permission name will affect all roles and users that currently have
                                        this permission.
                                        Make sure to update your code and middleware accordingly.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Current Usage:</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Roles:</strong>
                                                @if($permission->roles->count() > 0)
                                                <ul class="mb-0">
                                                    @foreach($permission->roles as $role)
                                                    <li>{{ $role->name }}</li>
                                                    @endforeach
                                                </ul>
                                                @else
                                                <p class="mb-0 text-muted">None</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Direct Users:</strong>
                                                @if($permission->users->count() > 0)
                                                <ul class="mb-0">
                                                    @foreach($permission->users as $user)
                                                    <li>{{ $user->name }}</li>
                                                    @endforeach
                                                </ul>
                                                @else
                                                <p class="mb-0 text-muted">None</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Permission
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