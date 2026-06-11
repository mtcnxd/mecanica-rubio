@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Buscar</span></h6>
    <div class="window-body shadow py-4">
        <form action="{{ route('admin.service.index') }}" method="GET">
            <div class="row m-1 mb-3 pb-3" id="filters">
                <div class="col-md-3">
                    <label for="endDate" class="fw-bold">Cliente</label>
                    <select class="form-select" name="client" id="client">
                        <option value="" disabled selected>Filtrar por cliente</option>
                        @foreach (App\Models\Client::where('status','Activo')->orderBy('name')->get() as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="endDate" class="fw-bold">Estatus</label>
                    <select class="form-select" name="status" id="status">
                        <option value="" disabled selected>Filtrar por estatus</option>
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
                    <input type="text" class="form-control" name="id" id="id">
                </div>
                <div class="col-md-4 mt-4">
                    <button type="submit" class="btn btn-success">
                        <x-feathericon-search class="table-icon" style="margin: -2px 5px 2px"/>
                        Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <label for="searchBox" class="fw-bold">Automovil</label>
                    <input type="text" class="form-control" id="searchBox">
                </div>
            </div>
        </form>

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
                @foreach ($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td>
                        <a href="{{ route('admin.service.show', $service->id) }}">{{ $service->fault }}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.client.show', $service->client->id) }}">{{ $service->client->name }}</a>
                    </td>
                    <td>{{ $service->car->brand }} {{ $service->car->model }}</td>
                    <td>{{ $service->entry_date->format('d/m/Y') ?? '' }}</td>
                    <td>{{ $service->finished_date ? $service->finished_date->format('d/m/Y') : '' }}</td>
                    <td><x-badge-simple :status="$service->status"/></td>
                    <td class="text-end">{{ $service->total ? Number::currency($service->total) : '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end mt-3">
            <span class="pe-3">{{ $services->count() }} servicios encontrados</span>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="widget-simple shadow" style="solid 2px var(--blue-gray-100);">
                <div class="widget-simple-body fs-3" style="min-height: 40px;">
                    <div style="display: flex;justify-content: space-between;">
                        <div>
                            <span>{{ $services->where('status', 'Entregado')->where('finished_date','>=', Carbon\Carbon::now()->startOfMonth())->count(); }}</span>
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
                            <span>{{ $services->where('quote', false)->where('status', 'Pendiente')->count(); }}</span>
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
                            <span>{{ $services->whereNotIn('status', ['Entregado','Pendiente','Cancelado'])->count(); }}</span>
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

    const table = new DataTable('#services', 
    {
        processing: true,
        serverSide: false,
        searching: true,
        lengthChange:false,
        pageLength: 20,
        order: [5, 'asc'],
    });

</script>
@endsection
