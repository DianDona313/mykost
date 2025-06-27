{{-- resources/views/permissions/index.blade.php --}}
@extends('layouts.admin.app')
@section('title', 'Permissions Management')
@section('page-title', 'Permissions Management')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Daftar Permision</h2>
        @can('permission-create')
            <a href="{{ route('permissions.create') }}" class="btn btn-custom-orange mb-3">
                <i class="fas fa-plus"></i> Add New Permission
            </a>
        @endcan
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Guard Name</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($permissions as $permission)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $permission->name }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $permission->guard_name }}</span>
                                            </td>
                                            <td>{{ $permission->created_at->format('d M Y') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('permissions.show', $permission) }}"
                                                        class="btn btn-sm text-white"
                                                        style="background-color: #3b82f6; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 0.375rem;">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @can('permission-edit')
                                                        <a href="{{ route('permissions.edit', $permission) }}"
                                                            class="btn btn-sm text-white"
                                                            style="background-color: #2563eb; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 0.375rem;">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    @can('permission-delete')
                                                        <form action="{{ route('permissions.destroy', $permission) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm text-white"
                                                                style="background-color: #ef4444; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 0.375rem;"
                                                                onclick="return confirm('Are you sure?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>


                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No permissions found.</td>
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
