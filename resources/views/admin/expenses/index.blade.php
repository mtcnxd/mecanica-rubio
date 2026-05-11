@extends('includes.body')

@section('content')
@include('includes.alert')
<div class="window-container shadow">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Listado de egresos</span></h6>
    <div class="window-body shadow py-4">
        <div class="row m-1 mb-3 pb-3" id="filters">
            <div class="col-md-2">
                <label class="fw-bold">Inicio</label>
                <input type="date" class="form-control" value="{{ \Carbon\Carbon::now()->subDays(45)->format('Y-m-d') }}" id="startDate">
            </div>

            <div class="col-md-2">
                <label class="fw-bold">Final</label>
                <input type="date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="endDate">
            </div>

            <div class="col-md-2">
                <label class="fw-bold">Responsable</label>
                <select class="form-select" name="responsible" id="responsible">
                    <option value="0"> - Filtrar por responsable - </option>
                    <option value="3">Alexander Xix Ortiz</option>
                    <option value="2">Javier Rubio Magaña</option>
                    <option value="1">Marcos Tzuc Cen</option>
                </select>
            </div>

            <div class="col-md-2 mt-4">
                <button class="btn btn-success" id="applyFilter">
                    <x-feathericon-search class="table-icon" style="margin: -2px 5px 2px"/>
                    Buscar
                </button>
            </div>
        </div>
        
        <table class="table table-hover table-borderless bg-white mb-4" id="expenses" style="width:100%;">
            <thead>
                <tr>
                    <th width="40px">ID</th>
                    <th width="250px">Egreso</th>
                    <th width="500px">Descripción</th>
                    <th width="100px">Estatus</th>
                    <th width="100px">Fecha</th>
                    <th width="100px" class="text-end">Total</th>
                    <th width="105px">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td>
                            {{ $expense->id }}
                        </td>
                        <td>
                            <a href="{{ route('expenses.edit', $expense->id) }}">{{ $expense->name }}</a>
                        </td>
                        <td>{{ Str::limit($expense->description, 60) }}</td>
                        <td>
                            <span class="badge text-bg-success">{{ $expense->status }}</span>
                        </td>
                        <td>{{ Carbon\Carbon::parse($expense->expense_date)->format('d-m-Y') }}</td>
                        <td class="text-end">{{ "$".number_format($expense->amount * $expense->price, 2) }}</td>
                        <td>
                            <button class="btn" id="{{ $expense->id }}" onclick="removeItemExpense(this.id)">
                                <x-feathericon-trash-2 class="table-icon" style="margin: -2px 5px 0 0"/>
                            </button>
                            @if ($expense->attach)
                                <button class="btn attach" data-bs-target="#attached" data-bs-toggle="modal" id="{{ $expense->id }}" onclick="getImageAttached(this.id)">
                                    <x-feathericon-paperclip class="table-icon" style="margin: -2px 5px 0 0"/>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade" id="attached" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Archivo adjunto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <img id="modal-image" src="{{ asset('image.gif') }}" alt="Image">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>      
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

<script>
const startDate   = document.querySelector("#startDate");
const endDate     = document.querySelector("#endDate");
const responsible = document.querySelector("#responsible");
const applyFilter = document.querySelector('#applyFilter');

const table = new DataTable('#expenses', {
    searching: false,
    lengthChange:false,
    pageLength: 10,
    order: [0, 'desc'],
    columnDefs: [{
        orderable: false, 
        targets: [0, 2, 5, 6]
    }]
});

function removeItemExpense(buttonPressed){
    if (confirm('¿Confirma querer eliminar el registro?')){
        $.ajax({
            url:"{{ route('api.expenses-items.destroy',':id') }}".replace(':id', buttonPressed),
            method: 'DELETE',
            success:function(response){
                console.log('remove image');
            }
        }).then ((response) => {
            showMessageAlert(response.message);
        });
    }
}

function getImageAttached(buttonPressed){
    $.ajax({
        url:"{{ route('api.finance.expenses.image') }}",
        method: 'POST',
        data: {
            id:buttonPressed
        },
        success:function(response){
            let image = '/public/uploads/expenses/' + response.attach;
            $("#modal-image").attr('src', image);
        }
    });
}

function showMessageAlert(message){
    Swal.fire({
        text: message,
        icon: 'success',
        confirmButtonText: 'Aceptar'
    }).then(() => {
        history.go();
    });
}
</script>
@endsection