@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <div class="col-md-7">    
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Nuevo Empleado</span></h6>
        <div class="window-body shadow p-4">
            <form action="{{ route('admin.employee.store') }}" method="POST">
                <div class="form-container border">
                    @method('POST')
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label>Teléfono</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>RFC</label>
                            <input type="text" class="form-control" name="rfc">
                        </div>
                        <div class="col-md-6">
                            <label>NSS</label>
                            <input type="text" class="form-control" name="nss">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Salario</label>
                            <input type="number" class="form-control" name="salary" required>
                        </div>
                        <div class="col-md-6">
                            <label>Periodo</label>
                            <select class="form-select" name="periodicity" required>
                                <option>Semanal</option>
                                <option>Quincenal</option>
                                <option>Mensual</option>
                                <option>Comisionista</option>
                                <option>Sin definir</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Hora extra</label>
                            <input type="text" class="form-control" name="extra">
                        </div>
                        <div class="col-md-6">
                            <label>Estatus</label>
                            <select class="form-select" name="status">
                                <option>Activo</option>
                                <option>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Puesto</label>
                            <input type="text" class="form-control" name="level">
                        </div>
                        <div class="col-md-6">
                            <label>Departamento</label>
                            <input type="text" class="form-control" name="depto">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Comentarios</label>
                            <textarea class="form-control" cols="30" rows="4" name="comments"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('admin.employee.index') }}" class="btn btn-sm btn-secondary">Cancelar</a>
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