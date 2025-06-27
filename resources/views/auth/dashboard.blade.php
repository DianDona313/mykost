{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- User Stats -->
    @can('user-list')
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ \App\Models\User::count() }}</h4>
                        <p class="mb-0">Total Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <!-- Role Stats -->
    @can('role-list')
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ \Spatie\Permission\Models\Role::count() }}</h4>
                        <p class="mb-0">Total Roles</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-tag fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <!-- Permission Stats -->
    @can('permission-list')
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ \Spatie\Permission\Models\Permission::count() }}</h4>
                        <p class="mb-0">Total Permissions</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-key fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <!-- Current User Info -->
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>{{ Auth::user()->name }}</h6>
                        <p class="mb-0">
                            @foreach(Auth::user()->roles as $role)
                                {{ $role->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @can('user-create')
                    <div class="col-md-4 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('users.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus"></i> Add New User
                            </a>
                        </div>
                    </div>
                    @endcan
                    @can('properti-create')
                    <div class="col-md-4 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('properties.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus"></i> Tambah Properti Baru
                            </a>
                        </div>
                    </div>
                    @endcan
                    @can('room-list')
                    <div class="col-md-4 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('rooms.index') }}" class="btn btn-outline-success">
                                <i class="fas fa-home"></i> Lihat Kamar
                            </a>
                        </div>
                    </div>
                    @endcan
                    @can('booking-list')
                    <div class="col-md-4 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list"></i> Lihat Pemesanan Kamar
                            </a>
                        </div>
                    </div>
                    @endcan

                    @can('role-create')
                    <div class="col-md-4 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('roles.create') }}" class="btn btn-outline-success">
                                <i class="fas fa-plus-circle"></i> Add New Role
                            </a>
                        </div>
                    </div>
                    @endcan

                    @can('permission-create')
                    <div class="col-md-4 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('permissions.create') }}" class="btn btn-outline-warning">
                                <i class="fas fa-key"></i> Add New Permission
                            </a>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">System Information</h5>
            </div>
            <div class="card-body">
                {{-- <div class="row"> --}}
                    <div class="col-md-6">
                        <h6>Your Roles:</h6>
                        <div class="mb-3">
                            @forelse(Auth::user()->roles as $role)
                                <span class="badge bg-primary me-1 mb-1">{{ $role->name }}</span>
                            @empty
                                <span class="text-muted">No roles assigned</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-md-">
                        <h6>Your Permissions:</h6>
                        <div class="mb-3">
                            @forelse(Auth::user()->getAllPermissions() as $permission)
                                <span class="badge bg-secondary me-1 mb-1">{{ $permission->name }}</span>
                            @empty
                                <span class="text-muted">No direct permissions assigned</span>
                            @endforelse
                        </div>
                    </div>
                    
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection