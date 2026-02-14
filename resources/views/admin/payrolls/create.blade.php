@extends('includes.body')

@section('content')
@include('includes.alert')
<div class="window-container">
    <x-window_main title="Nominas" class="p-4">
        <form action="{{ route('payroll.store') }}" method="POST">
            <div class="form-container border mb-0">
                @csrf
                <div class="row pt-0 p-4 pb-0">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Empleado</label>
                                <div class="input-group mb-3">
                                    <select class="form-select" name="employee" id="employee" required>
                                        <option value="0"> - Seleccione empleado - </option>
                                        @foreach (App\Models\Employee::all() as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->user->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text" id="basic-addon2">
                                        <x-feathericon-user class="table-icon" style="margin: -2px 5px 2px"/>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Movimiento</label>
                                <select class="form-select" name="type" id="type">
                                    <option value="0"> - Seleccione movimiento - </option>
                                    <option>Nomina</option>
                                    <option>Aguinaldo</option>
                                    <option>Finiquito</option>
                                    <option>Liquidacion</option>
                                    <option>Otras percepciones</option>
                                </select>
                            </div> 
                            <div class="col-md-6">
                                <label>Salario</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control" name="salary" id="salary" value="" style="text-align: right" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Correo</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="email" id="email" disabled>
                                    <span class="input-group-text" id="basic-addon2">
                                        <x-feathericon-mail class="table-icon" style="margin: -2px 5px 2px"/>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Inicio</label>
                                <input type="date" name="start_date" class="form-control" value="{{ \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Final</label>
                                <input type="date" name="end_date" class="form-control" value="{{ \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 bg-white border" style="height: 300px; overflow-y: scroll">
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
                        @foreach ($items as $count => $item)
                        <tr>
                            <td>{{ $count +1 }}</td>
                            <td>{{ $item->concept }}</td>
                            <td class="text-end">{{ Number::currency($item->amount) }}</td>
                            <td>
                                <a href="#" class="removeButton" id="{{ $item->id }}">
                                    <x-feathericon-trash-2 class="table-icon"/>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top">
                        <tr>
                            <td colspan="2">
                                <a href="#" id="openModal">
                                    Agregar
                                    <x-feathericon-plus-circle class="table-icon" style="margin: 0 0 2px 5px"/>
                                </a>
                            </td>
                            <td class="text-end fw-bold">
                                {{ '$'.number_format($items->sum('amount'), 2) }}
                                <input type="hidden" name="total" value="{{ $items->sum('amount') }}" id="total">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            
            <div class="row mt-3">
                <div class="col-md-12 text-end">
                    <a href="{{ route('payroll.index') }}" class="btn btn-sm btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-sm btn-success">
                        <x-feathericon-save class="table-icon" style="margin: -2px 5px 2px"/>
                        Guardar
                    </button>
                </div>
            </div>

        </form>
    </x-window_main>
</div>
@endsection

@section('modal')
<div id="popup">
    <div class="row">
        <div class="col">
            <label for="concept">Concepto</label>
            <select name="concept" id="concept" class="form-select">
                <optgroup label="Prestaciones">
                    <option>Aguinaldo</option>
                    <option>Caja de ahorro</option>
                    <option>Salario</option>
                    <option>Prestamo</option>
                    <option>Hora extra</option>
                    <option>Bono</option>
                    <option>Comision</option>
                    <option>Prima vacacional</option>
                </optgroup>
                <optgroup label="Descuentos">
                    <option>Descuento por prestamo</option>
                    <option>Otro descuento</option>
                </optgroup>
            </select>
        </div>
        <div class="col">
            <label for="amount">Importe</label>
            <input type="text" class="form-control" id="amount" name="amount">
        </div>
    </div>
    <div class="row mt-3">
        <button class="col btn btn-secondary m-1" id="closeButton">Cancelar</button>
        <button class="col btn btn-success m-1" id="acceptButton">Agregar</button>
    </div>
</div>
<div id="overlay"></div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script>
$("#employee").on('change', function(){
    $.ajax({
        url: "{{ route('employees.load.all') }}",
        method: 'GET',
        data: {
            employee: $(this).val()
        },
        success: function(response){
            console.log(response);
            $("#salary").val(response.data.salary);
            $("#email").val(response.data.email);
        }
    })
});

$("#openModal").on('click', function(btn){
    btn.preventDefault();
    $('#popup').fadeIn();
    $('#overlay').fadeIn();
});

$(".removeButton").on('click', function() {
    $.ajax({
        url: "{{ route('payroll.removeItem') }}",
        data: {
            itemId:this.id
        },
        method:'POST',
        success: function (response) {
            console.log(response)
        }
    })
    .then(() => {
        history.go();
    });
});

$('#acceptButton').click(function() {
    $.ajax({
        url: "{{ route('payroll.addItem') }}",
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
        // history.go();
    });

    closePopup();
});

$("#closeButton").on('click', function(){
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

@section('css')
<style>
    #popup {
        display: none; /* Oculta el popup inicialmente */
        width: 600px !important;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 450px;
        padding: 30px;
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        z-index: 1000;
        border-radius: 7px;
    }

    #overlay {
        display: none; /* Oculta el fondo inicialmente */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }
</style>
@endsection
