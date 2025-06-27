{{-- resources/views/permissions/show.blade.php --}}
@extends('layouts.admin.app')
@section('title', 'Show Permission')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Permission Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <strong>Permission Name:</strong>
                                <p class="form-control-static">
                                    <span class="badge bg-primary fs-6">{{ $permission->name }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <strong>Guard Name:</strong>
                                <p class="form-control-static">{{ $permission->guard_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <strong>Created At:</strong>
                                <p class="form-control-static">{{ $permission->created_at->format('d M Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <strong>Updated At:</strong>
                                <p class="form-control-static">{{ $permission->updated_at->format('d M Y H:i:s') }}</p>
                            </div>
                        </div>

                        {{-- Show roles that have this permission --}}
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <strong>Roles with this Permission:</strong>
                                <div class="mt-2">
                                    @if($permission->roles->count() > 0)
                                    @foreach($permission->roles as $role)
                                    <span class="badge bg-success me-1">{{ $role->name }}</span>
                                    @endforeach
                                    @else
                                    <span class="text-muted">No roles assigned to this permission</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Show users that have this permission directly --}}
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <strong>Users with Direct Permission:</strong>
                                <div class="mt-2">
                                    @if($permission->users->count() > 0)
                                    @foreach($permission->users as $user)
                                    <span class="badge bg-info me-1">{{ $user->name }}</span>
                                    @endforeach
                                    @else
                                    <span class="text-muted">No users have this permission directly</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-center">
                            @can('permission-edit')
                            <a class="btn btn-primary" href="{{ route('permissions.edit', $permission->id) }}">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @endcan
                            <a class="btn btn-secondary" href="{{ route('permissions.index') }}">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection