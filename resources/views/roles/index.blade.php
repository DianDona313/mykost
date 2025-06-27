@extends('layouts.admin.app')
@section('title', 'Roles Management')
@section('page-title', 'Roles Management')
@section('content')
<div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Daftar Role</h2>
        @can('role-create')
            <a href="{{ route('roles.create') }}" class="btn btn-custom-orange mb-3">
                <i class="fas fa-plus"></i> Add New Role
            </a>
        @endcan
        <div class="col-12">
            
            <div class="card">
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Permissions</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                                            <div class="mt-1">
                                                @foreach ($role->permissions->take(3) as $permission)
                                                    <span class="badge bg-secondary me-1">{{ $permission->name }}</span>
                                                @endforeach
                                                @if ($role->permissions->count() > 3)
                                                    <span
                                                        class="badge bg-light text-dark">+{{ $role->permissions->count() - 3 }}
                                                        more</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $role->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('roles.show', $role) }}" class="btn btn-sm text-white"
                                                    style="background-color: #60a5fa; border-radius: 0.5rem;">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @can('role-edit')
                                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm text-white"
                                                        style="background-color: #3b82f6; border-radius: 0.5rem;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('role-delete')
                                                    <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                                        class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm text-white"
                                                            style="background-color: #ef4444; border-radius: 0.5rem;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No roles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
