@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Buscar</span></h6>
    <div class="window-body shadow py-4">
        <div class="row m-1 mb-3 pb-3" id="filters">
            <div class="col-md-3">
                <label for="endDate" class="fw-bold">Cliente</label>
                <select class="form-select" id="client">
                    <option>Todos</option>
                    @foreach (App\Models\Client::where('status','Activo')->orderBy('name')->get() as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="endDate" class="fw-bold">Estatus</label>
                <select class="form-select" id="status">
                    <option>Todos</option>
                    <option>Cancelado</option>
                    <option>Pendiente</option>
                    <option>Esperando cliente</option>
                    <option>Esperando refaccion</option>
                    <option>Finalizado</option>
                    <option>Entregado</option>
                </select>
            </div>
            <div class="col-md-1">
                <label for="folio" class="fw-bold">Folio</label>
                <input type="text" class="form-control" id="folio">
            </div>
            <div class="col-md-4 mt-4">
                <button class="btn btn-success" id="applyFilter">
                    <x-feathericon-search class="table-icon" style="margin: -2px 5px 2px"/>
                    Buscar
                </button>
            </div>
            <div class="col-md-2">
                <label for="searchBox" class="fw-bold">Automovil</label>
                <input type="text" class="form-control" id="searchBox">
            </div>
        </div>

        <table class="table table-hover table-borderless bg-white mb-4" id="services" style="width:100%;">
            <thead>
                <tr>
                    <th width="30px">Folio</th>
                    <th width="330px">Servicio/Fallo</th>
                    <th width="250px">Cliente</th>
                    <th>Automovil</th>
                    <th>Entrada</th>
                    <th width="100px" class="text-center">Salida</th>
                    <th width="100px" class="text-center">Estatus</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="widget-simple shadow" style="solid 2px var(--blue-gray-100);">
                <div class="widget-simple-body fs-3" style="min-height: 40px;">
                    <div style="display: flex;justify-content: space-between;">
                        <div>
                            <span>{{ $service->where('status', 'Entregado')->where('finished_date','>=', Carbon\Carbon::now()->startOfMonth())->count(); }}</span>
                            <div class="fs-6 text-uppercase fw-bold fs-7">Servicios Entregados</div>
                        </div>
                        <div style="display: flex;align-items: center;">
                            <x-feathericon-check-circle class="window-title-icon" style="height: 36px; width: 36px;"/>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget-simple shadow" style="solid 2px var(--blue-gray-100);">
                <div class="widget-simple-body fs-3" style="min-height: 40px;">
                    <div style="display: flex;justify-content: space-between;">
                        <div>
                            <span>{{ $service->where('quote', false)->where('status', 'Pendiente')->count(); }}</span>
                            <div class="fs-6 text-uppercase fw-bold fs-7">Servicios Pendientes</div>
                        </div>
                        <div style="display: flex;align-items: center;">
                            <x-feathericon-circle class="window-title-icon" style="height: 36px; width: 36px;"/>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget-simple shadow" style="solid 2px var(--blue-gray-100);">
                <div class="widget-simple-body fs-3" style="min-height: 40px;">
                    <div style="display: flex;justify-content: space-between;">
                        <div>
                            <span>{{ $service->whereNotIn('status', ['Entregado','Pendiente','Cancelado'])->count(); }}</span>
                            <div class="fs-6 text-uppercase fw-bold fs-7">Servicios en espera</div>
                        </div>
                        <div style="display: flex;align-items: center;">
                            <x-feathericon-clock class="window-title-icon" style="height: 36px; width: 36px;"/>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>

$('#client').select2();

const client      = document.querySelector("#client");
const status      = document.querySelector("#status");
const folio       = document.querySelector("#folio");
const applyFilter = document.querySelector('#applyFilter');

const table = new DataTable('#services', 
{
    processing: true,
    serverSide: true,
    searching: true,
    lengthChange:false,
    pageLength: 15,
    order: [5, 'asc'],
    ajax: {
        url: "{{ route('getDataTableServices') }}",
        data: function(data) {
            data.client = client.value;
            data.status = status.value;
            data.folio = folio.value;
        }
    },
    columns:[
        {
            data:'service_id',
            orderable: true
        },{
            data:'fault',
            orderable: false
        },{
            data:'name'
        },{
            data:'car'
        },{
            data:'entry_date'
        },{
            data:'finished_date'
        },{
            data:'status'
        },{
            data:'total',
            className: 'text-end',
            orderable: false
        }
    ]
});

$("#searchBox").on('keyup', function(){
    table.columns(3).search(this.value).draw();
})

applyFilter.addEventListener('click', function(){
    table.draw();
}); 
</script>
@endsection
