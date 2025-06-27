{{-- resources/views/roles/edit.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role: ' . $role->name)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Role: {{ $role->name }}</h5>
            </div>
            <div class="card-body">
                {!! html()->form('PUT', route('roles.update', $role))->open() !!}
                
                <div class="mb-3">
                    {!! html()->label('Role Name', 'name')->class('form-label') !!}
                    {!! html()->text('name', $role->name)->class('form-control')->placeholder('Enter role name') !!}
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    {!! html()->label('Permissions', 'permissions')->class('form-label') !!}
                    <div class="row">
                        @foreach($permissions as $permission)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    {!! html()->checkbox('permissions[]', in_array($permission->id, $rolePermissions), $permission->name)->class('form-check-input')->id('permission_'.$permission->id) !!}
                                    {!! html()->label($permission->name, 'permission_'.$permission->id)->class('form-check-label') !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    {!! html()->submit('Update Role')->class('btn btn-primary') !!}
                </div>

                {!! html()->form()->close() !!}
            </div>
        </div>
    </div>
</div>
@endsection