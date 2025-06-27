@extends('layouts.admin.app')
@section('title', 'Role Details')
@section('page-title', 'Role Details: ' . $role->name)
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Role Information</h5>
                <div class="btn-group">
                    @can('role-edit')
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endcan
                    @can('role-delete')
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this role?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Role Name:</th>
                                <td>{{ $role->name }}</td>
                            </tr>
                            <tr>
                                <th>Guard Name:</th>
                                <td>
                                    <span class="badge bg-secondary">{{ $role->guard_name }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Total Permissions:</th>
                                <td>
                                    <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Users with this Role:</th>
                                <td>
                                    <span class="badge bg-primary">{{ $role->users->count() }} users</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $role->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>{{ $role->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Permissions List:</h6>
                        @if($role->permissions->isEmpty())
                            <p><em>No permissions assigned to this role.</em></p>
                        @else
                            <ul class="list-group">
                                @foreach($role->permissions as $permission)
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success"></i> {{ $permission->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Roles List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
