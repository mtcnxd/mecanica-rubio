@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Usuarios del sistema</span></h6>
    <div class="window-body shadow">
        <table class="table table-hover table-borderless bg-white" id="services">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Status</th>
                    <th style="width: 150px;">Fecha de alta</th>
                    <th style="width: 20px;">&nbsp;</th>
                </tr>
            </thead>
            <tbody class="border-bottom">
                @foreach ($users as $user) 
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><a href="{{ route('admin.user.show', $user->id) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->status }}</td>
                        <td>{{ Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('admin.user.edit', $user) }}">
                                <x-feathericon-edit class="table-icon"/>
                            </a>
                        </td>
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