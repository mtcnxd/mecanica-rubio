@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Nominas</span></h6>
    <div class="window-body shadow p-4">
        <div class="form-container border mb-0">
            <form action="{{ route('admin.finance.payroll.update', $payroll->id) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="row pt-0 pb-0">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="text-uppercase fs-8 fw-bold">Empleado</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" value="{{ $payroll->employee->name }}" disabled>
                                    <span class="input-group-text" id="basic-addon2">
                                        <a href="{{ route('admin.employee.show', $payroll->employee) }}">
                                            <x-feathericon-user class="table-icon" style="margin: -2px 5px 2px"/>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-uppercase fs-8 fw-bold">Movimiento</label>
                                <input type="text" class="form-control" value="{{ $payroll->type }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="text-uppercase fs-8 fw-bold">Fecha de Pago</label>
                                <input type="date" class="form-control" value="{{ ($payroll->paid_date) ? $payroll->paid_date->format('Y-m-d') : null }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-uppercase fs-8 fw-bold">Correo</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="email" id="email" value="{{ $payroll->employee->email }}" disabled>
                                    <span class="input-group-text" id="basic-addon2">
                                        <x-feathericon-mail class="table-icon" style="margin: -2px 5px 2px"/>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-uppercase fs-8 fw-bold">Status</label>
                                @php
                                    $style = ($payroll->status == 'Pagado') ? $style = 'success' : $style = 'warning';
                                @endphp
                                <input type="text" class="form-control border border-{{$style}}-subtle text-{{$style}}-emphasis bg-{{$style}}-subtle" value="{{ $payroll->status }}" disabled/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-uppercase fs-8 fw-bold">Inicio</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $payroll->start_date->format('Y-m-d') }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="text-uppercase fs-8 fw-bold">Final</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $payroll->end_date->format('Y-m-d') }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 bg-white border mt-4 mb-4" style="height: 300px; overflow-y: scroll">
                <table class="table table-hover table-borderless dataTable no-footer">
                    <thead>
                        <tr>
                            <th width="30px">#</th>
                            <th>Concepto</th>
                            <th class="text-end">Importe</th>
                            <th width="30px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payroll->payrollItems as $count => $item)
                        <tr>
                            <td>{{ $count +1 }}</td>
                            <td>{{ $item->concept }}</td>
                            <td class="text-end">{{ Number::currency($item->amount) }}</td>
                            <td>
                                @if ($payroll->status != 'Pagado')
                                    <a href="#" class="removeItem" id="{{ $item->id }}">
                                        <x-feathericon-trash-2 class="table-icon"/>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                @if ($payroll->status != 'Pagado')
                                    <a href="#" id="addConcept">
                                        Agregar
                                        <x-feathericon-plus-circle class="table-icon" style="margin: 0 0 2px 5px"/>
                                    </a>
                                @endif
                            </td>
                            <td class="text-end">
                                {{ Number::currency($payroll->payrollItems->sum('amount')) }}
                                <input type="hidden" name="total" value="{{ $payroll->payrollItems->sum('amount') }}" id="total">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="col-md-12 text-end">
                <button type="button" onclick="location.href='{{ route('admin.finance.payroll.index') }}'" class="btn btn-sm btn-secondary">
                    <x-feathericon-arrow-left class="table-icon" style="margin: -2px 5px 2px"/>
                    Atras
                </button>
                <button type="button" onclick="print()" class="btn btn-sm btn-secondary">
                    Imprimir
                    <x-feathericon-printer class="table-icon" style="margin: -2px 5px 2px"/>
                </button>
                @if ($payroll->status != 'Pagado')
                    <button type="submit" class="btn btn-sm btn-success">
                        Pagar
                        <x-feathericon-dollar-sign class="table-icon" style="margin: -2px 5px 2px"/>
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
$("addConcept").on('click', function(event){
    event.preventDefault();
    $('#popup').fadeIn();
    $('#overlay').fadeIn();
});

$(".removeItem").on('click', function(event) {
    const itemId = this.id;

    $.ajax({
        url: "{{ route('api.finance.payroll-item.destroy', ':id') }}".replace(':id', itemId),
        data: {itemId:itemId},
        method:'DELETE',
        success: function (response) {
            console.log(response)
        }
    })
    .then(() => {
        history.go();
    });
});

$('#acceptPopup').click(function(event) {
    event.preventDefault();
    
    $.ajax({
        url: "{{ route('api.finance.payroll-item.store') }}",
        method: 'POST',
        data: {
            concept: $("#concept").val(),
            amount: $("#amount").val()
        },
        success: function(response){
            console.log(response);
        }
    })
    .then(() => {
        history.go();
    });

    closePopup();
});

$('#overlay').click(function() {
    closePopup();
});

function closePopup(){
    $('#popup').fadeOut();
    $('#overlay').fadeOut();
}
</script>
@endsection