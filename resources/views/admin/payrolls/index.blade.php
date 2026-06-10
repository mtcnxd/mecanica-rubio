@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Nominas</span></h6>
    <div class="window-body shadow py-4">
        <form action="{{ route('admin.finance.payroll.index') }}" method="GET">
            <div class="row m-1 mb-3 pb-3">
                <div class="col-md-3">
                    <select class="form-select" name="employee" id="employee">
                        <option disabled selected>Filtrar por nombre</option>
                        @foreach (\App\Models\Employee::all() as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }} </option>    
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-success" id="applyFilter">
                        <x-feathericon-search class="table-icon" style="margin: -2px 5px 2px"/>
                        Buscar
                    </button>
                </div>
            </div>
        </form>
        
        <table class="table table-hover table-borderless bg-white border" id="expenses" style="width:100%;">
            <thead>
                <tr>
                    <th width="40px">ID</th>
                    <th width="350px">Empleado</th>
                    <th width="250px">Tipo</th>
                    <th width="300px">Periodo</th>
                    <th width="200px">Fecha de pago</th>
                    <th>Estatus</th>
                    <th class="text-end">Total</th>
                    <th width="30px">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->id }}</td>
                    <td>
                        <span class="material-symbols-outlined" style="position:relative; top:5px; margin-right:6px;">badge</span>
                        <a href="{{ route('admin.finance.payroll.show', $payroll->id) }}">
                            {{ $payroll->employee->name }}
                        </a>
                    </td>
                    <td>
                        {{ $payroll->type }}
                    </td>
                    <td>
                        <span class="badge text-bg-secondary">
                            {{ Carbon\Carbon::parse($payroll->start_date)->format('d-m-Y') }}
                        </span>
                        |
                        <span class="badge text-bg-secondary">
                            {{ Carbon\Carbon::parse($payroll->end_date)->format('d-m-Y') }}
                        </span>
                    </td>
                    <td>
                        {{ isset($payroll->paid_date) ? \Carbon\Carbon::parse($payroll->paid_date)->format('d-m-Y') : null }}
                    </td>
                    <td>
                        @if ($payroll->status == 'Pagado')
                            <span class="badge rounded-pill text-bg-success">{{ $payroll->status }}</span>
                        @else
                            @if ($payroll->status == 'Cancelado')
                                <span class="badge rounded-pill text-bg-secondary">{{ $payroll->status }}</span>    
                            @else
                                <span class="badge rounded-pill text-bg-warning">{{ $payroll->status }}</span>
                            @endif
                        @endif
                    </td>
                    <td class="text-end">{{ Number::currency($payroll->total) }}</td>
                    <td>
                        <div class="dropdown">
                            @if ($payroll->status != 'Pagado')
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" style="margin-top:-3px;">
                                <x-feathericon-more-vertical style="height:20px;"/>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-action="Pagado" data-id="{{ $payroll->id }}">Pagar</a></li>
                                <li><a class="dropdown-item" href="#" data-action="Cancelado" data-id="{{ $payroll->id }}">Cancelar</a></li>
                                <li><a class="dropdown-item" href="#" data-action="Borrado" data-id="{{ $payroll->id }}">Eliminar</a></li>
                            </ul>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script>
$(".dropdown-item").on('click', function(){
    const buttonGroup = $(this);

    var data = {
        'id': buttonGroup.data('id'),
        'action': buttonGroup.data('action')
    }
    
    $.ajax({
        url: "{{ route('api.finance.payroll.update', ':payroll') }}".replace(':payroll', data.id),
        method: 'PATCH',
        dataType: 'JSON',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response){
            console.log(response);

            if (!response.success){
                showMessageAlert(response.message, 'error');
                return false;
            }

            showMessageAlert(response.message).then(() => {
                location.reload();
            });
        }
    });
});

function showMessageAlert(message, type = 'success'){
    return Swal.fire({
        text: message,
        icon: type,
        confirmButtonText: 'Aceptar'
    });
}
</script>
@endsection