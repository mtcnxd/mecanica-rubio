@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Empleados</span></h6>
    <div class="window-body shadow">
        <table class="table table-hover table-borderless bg-white" id="services">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Estatus</th>
                    <th>Fecha de inicio</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody class="border-bottom">
                @foreach ($employees as $employee) 
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>
                            <a href="{{ route('employees.show', $employee->id) }}">
                                {{ $employee->name }}
                            </a>
                        </td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->status }}</td>
                        <td>{{ \Carbon\Carbon::parse($employee->start_date)->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('employees.edit', $employee->id) }}">
                                <x-feathericon-edit class="table-icon"/>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="m-3" style="display: flex; justify-content: space-between;">
            <a href="{{ route('employees.create') }}" class="btn btn-sm btn-success">
                Crear nuevo
            </a>

            <span>
                {{ $employee->count() }} registros encontrados
            </span>
        </div>
    </div>
</div>
@endsection