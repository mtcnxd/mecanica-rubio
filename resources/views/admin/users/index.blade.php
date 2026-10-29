@extends('includes.body')

@section('content')
@include('includes.alert')

<div class="window-container">
    <h6 class="window-title text-uppercase fw-bold">
        <span class="ms-3">Usuarios del sistema</span>
    </h6>
    <div class="window-body">
        <table class="table table-hover table-borderless bg-white" id="services">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Ultimo login</th>
                    <th>Status</th>
                    <th style="width: 150px;">Fecha de alta</th>
                </tr>
            </thead>
            <tbody class="border-bottom">
                @foreach ($users as $user) 
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><a href="{{ route('admin.user.show', $user->id) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->last_login_at?->format('d/m/Y - H:i') }}</td>
                        <td>
                            <span class="badge bg-success">
                                {{ $user->status }}
                            </span>
                        </td>
                        <td>{{ $user->created_at?->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="m-3" style="display: flex; justify-content: space-between;">
            <a href="{{ route('admin.user.create') }}" class="btn btn-sm btn-success">
                Crear nuevo
            </a>

            <span>
                {{ $users->count() }} registros encontrados
            </span>
        </div>
    </div>
</div>
@endsection