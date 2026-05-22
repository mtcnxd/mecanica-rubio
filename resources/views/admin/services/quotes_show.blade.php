@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Cotizacion</span></h6>
    <div class="window-body shadow p-4">
        <form action="{{ route('admin.service.update', $service->id) }}" method="POST">
            <div class="form-container border mb-0">
                @csrf
                @method('PATCH')
                <div class="row pt-0 pb-0">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Cliente</label>    
                                <input type="text" class="form-control" name="client" value="#{{ $service->client->id }} - {{ $service->client->name }}" disabled>
                                <input type="hidden" value="{{ $service->id }}" id="service">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Servicio/Fallo reportado</label>
                                <textarea class="form-control" cols="30" rows="4" name="fault" disabled>{{ $service->fault }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Automovil</label>
                                <input type="text" class="form-control" id="car" name="car" value="{{ $service->car->carName() }} - {{ $service->car->year }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Fecha cotizacion</label>    
                                <input type="date" class="form-control" name="entry" value="{{ $service->created_at->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Comentarios</label>
                                <textarea class="form-control" cols="30" rows="4" name="comments" disabled>{{ $service->comments }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 bg-white border border-top-0 border-bottom-0" style="height: 350px; overflow-y: scroll">
                <table class="table table-hover table-borderless dataTable no-footer">
                    <thead>
                        <th width="30px">#</th>
                        <th>Descripción</th>
                        <th class="text-end">P.Unitario</th>
                        <th class="text-end">Importe</th>
                        <th width="30px"></th>
                    </thead>
                    <tbody>
                        @foreach ($service->serviceItems as $item)
                        <tr>
                            <td>{{ $item->amount }}</td>
                            <td>{{ $item->item }}</td>
                            <td class="text-end">{{ Number::currency($item->price) }}</td>
                            <td class="text-end">{{ Number::currency($item->amount * $item->price) }}</td>
                            <td>
                                <a href="#" class="removeItem" id="{{ $item->id }}">
                                    <x-feathericon-trash-2 class="table-icon"/>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#createItem" id="addItem">
                                    Agregar
                                    <x-feathericon-plus-circle class="table-icon" style="margin: 0 0 2px 5px"/>
                                </a>
                            </td>
                            <td class="text-end fw-bold">
                                <input type="hidden" name="total" value="{{ $service->total }}">
                                {{ Number::currency($service->total) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="form-container border">
                <div class="row">
                    <div class="col-md-6">
                        <label>Comentarios</label>
                        <textarea name="notes" class="form-control" cols="30" rows="3">{{ $service->notes }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-end">
                    <button class="btn btn-sm btn-secondary" id="getPdf">
                        <x-feathericon-file-text class="table-icon" style="margin: -2px 5px 2px"/>
                        Descargar
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" id="chgService">
                        Servicio
                    </button>
                    <button type="submit" class="btn btn-sm btn-success">
                        <x-feathericon-save class="table-icon" style="margin: -2px 5px 2px"/>
                        Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('modal')
<div class="modal modal-xl fade" id="createItem" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Agregar elemento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                        <label for="amount">Cantidad</label>
                        <input type="text" class="form-control" id="amount">
                    </div>
                    <div class="col-md-5">
                        <label for="item">Descripción</label>
                        <input type="text" class="form-control" id="item" autocomplete="off">
                        <ul id="resultListItems" style="display:none; z-index:10;" class="float-suggestions"></ul>
                    </div>        
                    <div class="col-md-3">
                        <label for="supplier">Proveedor</label>
                        <input type="text" class="form-control" id="supplier">
                    </div>                    
                    <div class="col-md-2">
                        <label for="price">Precio</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="price">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input class="form-check-input" type="checkbox" id="labour">
                        <label class="form-check-label" for="labour">
                            Mano de obra
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="addItemInvoice">Agregar</button>
            </div>
        </div>
    </div>
</div>    
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const rutes = {
        serviceItemsStore : "{{ route('api.service.service-item.store') }}",
        serviceItemsIndex : "{{ route('api.service.service-item.index') }}",
        serviceItemsDestroy : "{{ route('api.service.service-item.destroy', ':id') }}",
        serviceUpdate : "{{ route('api.service.update', ':id') }}",
        servicePdf : "{{ route('api.service.pdf', ':id') }}"
    }
</script>
<script src="{{ asset('js/services.js')}}"></script>
@endsection