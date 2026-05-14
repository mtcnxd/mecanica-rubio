@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <div class="col-md-7">
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Usuarios</span></h6>
        <div class="window-body shadow p-4">
            <form action="{{ route('users.update', '$user') }}" method="POST">
                <div class="form-container border">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="name" value="{{ isset($user) ? $user->name : '' }}" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Correo</label>
                            <input type="text" class="form-control" name="email" value="{{ isset($user) ? $user->email : '' }}" disabled>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Teléfono</label>
                            <input type="number" class="form-control" name="phone" value="{{ isset($user) ? $user->phone : '' }}" disabled>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Rol</label>
                            <select class="form-select" name="status">
                                <option value="admin">Administrador</option>
                                <option value="client">Cliente</option>
                                <option value="user">Usuario</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Estatus</label>
                            <select class="form-select" name="status">
                                <option>Activo</option>
                                <option>Cancelado</option>
                                <option>Inactivo</option>
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
                        <a href="#" class="btn btn-sm btn-danger">Borrar</a>
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
</div>
@endsection

@section('js')
@endsection