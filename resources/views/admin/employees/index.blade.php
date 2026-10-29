@extends('includes.body')

@section('content')
@include('includes.alert')

<div class="window-container">
    <h6 class="window-title text-uppercase fw-bold"><span class="ms-3">Empleados</span></h6>
    <div class="window-body">
        <table class="table table-hover table-borderless bg-white" id="services">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Curp</th>
                    <th>Estatus</th>
                    <th>Departamento</th>
                    <th>Fecha de inicio</th>
                </tr>
            </thead>
            <tbody class="border-bottom">
                @foreach ($employees as $employee) 
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>
                            <a href="{{ route('admin.employee.show', $employee->id) }}">
                                {{ $employee->name }}
                            </a>
                        </td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->curp }}</td>
                        <td>{{ $employee->status }}</td>
                        <td>{{ $employee->depto }}</td>
                        <td>{{ $employee->start_date->format('d/M/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="m-3" style="display: flex; justify-content: space-between;">
            <a href="{{ route('admin.employee.create') }}" class="btn btn-sm btn-success">
                Crear nuevo
            </a>

            <span>
                {{ count($employees) }} registros encontrados
            </span>
        </div>
    </div>
</div>
@endsection