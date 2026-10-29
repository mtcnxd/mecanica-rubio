@extends('includes.body')

@section('content')
@include('includes.alert')

<div class="window-container">
    <div class="row">
        <div class="col-md-7">
            <h6 class="window-title text-uppercase fw-bold"><span class="ms-3">Editar Usuario</span></h6>
            <div class="window-body p-4">
                <form action="{{ route('admin.user.update', $user) }}" method="POST">
                    <div class="form-container">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Nombre</label>
                                <input type="text" class="form-control" name="name" value="{{ isset($user) ? $user->name : '' }}" required>
                            </div>
                        </div>
    
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Correo</label>
                                <input type="text" class="form-control" name="email" value="{{ isset($user) ? $user->email : '' }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Teléfono</label>
                                <input type="number" class="form-control" name="phone" value="{{ isset($user) ? $user->phone : '' }}" disabled>
                            </div>
                        </div>
    
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Rol</label>
                                <select class="form-select" name="rol">
                                    <option value="admin">Administrador</option>
                                    <option value="limit">Limitado</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Estatus</label>
                                <select class="form-select" name="status">
                                    <option>Activo</option>
                                    <option>Inactivo</option>
                                    <option>Cancelado</option>
                                </select>
                            </div>
                        </div>
    
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Comentarios</label>
                                <textarea class="form-control" cols="30" rows="4" name="comments">{{ isset($user) ? $user->comments : '' }}</textarea>
                            </div>
                        </div>
                    </div>
    
                    <div class="row mt-3">
                        <div class="col-md-12 text-end">
                            <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-secondary">Atras</a>
                            <button type="submit" class="btn btn-sm btn-success">
                                <x-feathericon-save class="table-icon" style="margin: -2px 5px 2px"/>
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>            
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-none p-4">
                <div class="row col-md-12">
                    <strong>Ultimo login</strong>
                    <span>{{ $user->last_login_at ?? '-' }}</span>
                </div>
                <div class="row col-md-12 mt-3">
                    <strong>Ultima edición</strong>
                    <span>{{ $user->updated_at }}</span>
                </div>
                <div class="row col-md-12 mt-3">
                    <strong>Fecha de creacion</strong>
                    <span>{{ $user->created_at }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection