@extends('includes.body')

@section('content')
<div class="window-container">
    <div class="col-md-7">
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Empleados</span></h6>
        <div class="window-body shadow p-4">
            <form action="" method="POST">
                <div class="form-container border">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">#{{ $employee->id }}</span>
                                <input type="text" class="form-control" name="name" value="{{ $employee->user->name }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Correo</label>
                            <input type="text" class="form-control" name="email" value="{{ $employee->user->email }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Teléfono</label>
                            <input type="number" class="form-control" name="phone" value="{{ $employee->user->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label>RFC</label>
                            <input type="text" class="form-control" name="rfc" value="{{ $employee->rfc }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>CURP</label>
                            <input type="text" class="form-control" name="curp" value="{{ $employee->curp }}">
                        </div>
                        <div class="col-md-6">
                            <label>NSS</label>
                            <input type="text" class="form-control" name="nss" value="{{ $employee->nss }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Salario</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="salary" value="{{ $employee->salary }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Periodo</label>
                            <select class="form-select" name="periodicity">
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
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" name="extra" value="{{ $employee->extra }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Estatus</label>
                            <select class="form-select" name="status">
                                <option value="">Activo</option>
                                <option value="">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Comentarios</label>
                            <textarea class="form-control" cols="30" rows="4" name="comments">{{ $employee->comments }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-end">
                        <img src="{{ asset('image.gif') }}" width="20px" height="20px" id="loader" style="margin-right: 20px; display:none;">
                        <a href="{{ route('admin.employee.index') }}" class="btn btn-sm btn-secondary">Atras</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    btnDelete = document.getElementById('btn-delete');
    btnDelete.addEventListener('click', function(event){
        event.preventDefault();
        $("#loader").show();

        $.ajax({
            type: "POST",
            url:  "",
            data: {
                user: {{ $employee->id }}
            },
            success: function (response) {
                console.log(response);
                showMessageAlert('success', response.message);
            }
        }).then(() => {
            $("#loader").hide();
        });
    })

    function showMessageAlert(type, message){
        Swal.fire({
            text: message,
            icon: type,
            confirmButtonText: 'Aceptar'
        }).then(() => {
            location.replace('/admin/employees');
        });
    }
</script>
@endsection