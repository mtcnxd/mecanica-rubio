@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Cotizaciones</span></h6>
    <div class="window-body shadow py-4">
        <div class="row m-1 mb-3 pb-3" id="filters">
            <div class="col-md-3">
                <label for="endDate" class="fw-bold">Cliente</label>
                <select class="form-select" id="client">
                    <option>Todos</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-4 mt-4">
                <button class="btn btn-success" id="applyFilter">
                    <x-feathericon-search class="table-icon" style="margin: -2px 5px 2px"/>
                    Filtrar
                </button>
            </div>
            <div class="col-md-2">
                <label for="searchBox" class="fw-bold">Automovil</label>
                <input type="text" class="form-control" id="searchBox">
            </div>
        </div>

        <table class="table table-hover table-borderless bg-white mb-4" id="quotes" style="width:100%;">
            <thead>
                <tr>
                    <th width="30px">ID</th>
                    <th width="250px">Cliente</th>
                    <th>Automovil</th>
                    <th width="330px">Servicio/Fallo</th>
                    <th width="100px" class="text-center">Estatus</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotes as $quote)
                <tr>
                    <td>{{ $quote->id }}</td>
                    <td>
                        <a href="{{ route('admin.service.quote.show', $quote->id) }}">
                            {{ $quote->client->name }}</td>
                        </a>
                    <td>{{ $quote->car->fullName }}
                    <td>{{ $quote->fault }}
                    <td>{{ $quote->status }}
                    </td>
                    <td class="text-end">{{ "$".number_format($quote->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
    const table = new DataTable('#quotes', {
        searching: false,
        lengthChange:false,
    });
</script>
@endsection
