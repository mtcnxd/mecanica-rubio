@extends('includes.body')

@section('content')
@include('includes.alert')
<div class="window-container">
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Empleado</span></h6>
    <div class="window-body shadow p-4">
        <div class="form-container border">
            <p class="fs-5 fw-bold">Detalles empleado</p>
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"> #{{ $employee->id }}</span>
                                <input type="text" class="form-control" name="name" value="{{ $employee->name }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label>CURP</label>
                            <input type="text" class="form-control" name="curp" value="{{ $employee->curp }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label>RFC</label>
                            <input type="text" class="form-control" name="rfc" value="{{ $employee->rfc }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label>NSS</label>
                            <input type="text" class="form-control" name="nss" value="{{ $employee->nss }}" disabled>
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
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Correo</label>
                            <input type="text" class="form-control" name="email" value="{{ $employee->email }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label>Teléfono</label>
                            <input type="number" class="form-control" name="phone" value="{{ $employee->phone }}" disabled>
                        </div>                            
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Comentarios</label>
                            <textarea class="form-control" cols="30" rows="4" name="comments">{{ $employee->comments }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="form-container border">
            <form id="vacations">
                <p class="fs-5 fw-bold">Historial de solicitudes</p>
                <div class="row">
                    <table class="table table-hover">
                    @foreach ($employee->vacationsDaysTaken() as $row => $vacation)
                        <tr>
                            <td>{{ $row + 1 }}</td>
                            <td>{{ $vacation->type }}</td>
                            <td>{{ $vacation->comment }}</td>
                            <td class="text-center">{{ $vacation->date }}</td>
                            <td class="text-end">
                                @switch($vacation->status)
                                    @case('Pendiente')
                                        <span class="badge rounded-pill text-bg-warning">{{ $vacation->status }}</span>    
                                        @break
                                    @case('Autorizado')
                                        <span class="badge rounded-pill text-bg-success">{{ $vacation->status }}</span>    
                                        @break
                                    @case('Cancelado')
                                        <span class="badge rounded-pill text-bg-secondary">{{ $vacation->status }}</span>    
                                        @break
                                @endswitch
                            </td>
                            <td class="text-end">
                                @if ($vacation->status == 'Pendiente')
                                    <a href="#" data-id="{{ $vacation->id }}" class="btn btn-sm cancellVacationDate">Autorizar</a>
                                @endif

                                @if ($vacation->status != 'Cancelado')
                                    <a href="#" data-id="{{ $vacation->id }}" class="btn btn-sm cancellVacationDate">Cancelar</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-container border">
                    <form id="vacations">
                        <p class="fs-5 fw-bold">Solicitudes de ausencia</p>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="date">Fecha de ausencia</label>
                                <input type="date" class="form-control" name="date" id="date">
                            </div>
                            <div class="col-md-6">
                                <label for="date">Motivo de ausencia</label>
                                <select class="form-select" name="type" id="type">
                                    <option>Permiso</option>
                                    <option>Salud</option>
                                    <option>Vacaciones</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="comment">Comentario</label>
                            <textarea class="form-control" name="comment" id="comment"></textarea>
                        </div>
                        <div class="col-md-12 mt-3 text-end">
                            <button type="button" class="btn btn-sm btn-success" onclick="createRow()">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-container border">
                    
                    <div class="row">
                        <p class="fs-5 fw-bold">Antiguedad</p>
                        <div class="col-md-6">
                            <label>Fecha de inicio</label> 
                            <input type="date" value="{{ Carbon\Carbon::parse($employee->created_at)->format('Y-m-d') }}" class="form-control" disabled>
                        </div>
                        <div class="col-md-6">
                            <label>Antiguedad</label>
                            <input type="text" value="{{ Carbon\Carbon::parse($employee->created_at)->diffInMonths() }} meses" class="form-control" disabled>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <p class="fs-5 fw-bold">Vacaciones</p>
                        <div class="col-md-6">
                            <label>Dias tomados</label> 
                            <input type="text" value="" class="form-control" disabled>
                        </div>

                        <div class="col-md-6">
                            <label>Dias pendientes</label>
                            <input type="text" value="" class="form-control" disabled>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="form-container border">
            <form id="savings">
                <p class="fs-5 fw-bold">Caja de ahorro</p>
                <div class="row">
                    <table class="table table-hover">
                        @foreach ($employee->payrollItems as $payrollItem)
                            <tr>
                                <td>{{ $payrollItem->salary_id }}</td>
                                <td>{{ $payrollItem->concept }}</td>
                                <td class="text-end">{{ Number::currency($payrollItem->amount) }}</td>
                            </tr>
                        @endforeach
                        <tfoot>
                            <td colspan="2">&nbsp;</td>
                            <td class="text-end">{{ Number::currency($employee->payrollItems()->sum('amount')) }}</td>
                        </tfoot>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function createRow(){
        var employee = {{ $employee->id }};
        var type     = $('#type');
        var date     = $('#date');
        var comment  = $('#comment');

        $.ajax({
            url: "",
            method: 'POST',
            data:{
                employee:employee,
                type:type.val(),
                date:date.val(),
                comment:comment.val()
            },
            success:function(response){
                console.log(response);
                $("#vacations").trigger('reset');
                showMessageAlert(response.type, response.message);
            }
        });
    }

    function showMessageAlert(type, message){
        Swal.fire({
            text: message,
            icon: type,
            confirmButtonText: 'Aceptar'
        })
        .then(() => {
            location.reload();
        });
    }

    $(".cancellVacationDate").on('click', function(event){
        event.preventDefault();
        $.ajax({
            url:"",
            method: 'DELETE',
            data:{
                id:this.dataset.id
            },
            success:function(response){
                console.log(response.message);
                showMessageAlert(response.type, response.message);
            }
        });
        
    });
</script>
@endsection