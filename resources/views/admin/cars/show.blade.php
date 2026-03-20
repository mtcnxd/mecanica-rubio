@extends('includes.body')

@section('content')
<div class="window-container">
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">automovil</span></h6>
    <div class="window-body shadow p-4">
        <div class="form-container border">
            <form method="POST">
                <p class="fw-bold fs-5">Cliente</p>
                <div class="row pt-0">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Nombre</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" value="{{ $car->client->name }}" disabled>
                                    <span class="input-group-text">
                                        <a href="#">
                                            <x-feathericon-edit class="table-icon"/>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Correo</label>
                                <input type="text" class="form-control" name="email" value="{{ $car->client->email }}" disabled>
                            </div>                
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Telefono</label>
                                <input type="text" class="form-control" name="phone" value="{{ $car->client->phone }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Dirección</label>
                                <input type="text" class="form-control" name="street" value="{{ $car->client->street }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Colónia</label>
                                <input type="text" class="form-control" name="street" value="{{ $car->client->address }}" disabled>
                            </div>
                        </div>
                        <div class="row mt-3"> 
                            <div class="col-md-6">
                                <label>Ciudad</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ $car->client->city }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Estado</label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ $car->client->state }}" disabled>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Comentarios</label>
                                <textarea class="form-control" disabled>{{ $car->client->comments }}</textarea>
                            </div>
                        </div>
                    </div>                
                </div>
            </form>
        </div>

        <div class="form-container border">
            <form method="POST">
                <p class="fw-bold fs-5">Automovil</p>
                <div class="row pt-0">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Marca</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $car->brand }}" disabled>
                                    <span class="input-group-text">
                                        <a href="#">
                                            <x-feathericon-edit class="table-icon"/>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <label>Modelo</label>
                                    <input type="text" class="form-control" value="{{ $car->model }}" disabled>
                                </div>
                            </div>                        
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Serie (VIN)</label>
                                <input type="text" class="form-control" value="{{ $car->serie }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Año</label>
                                <input type="text" class="form-control" value="{{ $car->year }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Placa</label>
                                <input type="text" class="form-control" value="{{ $car->plate }}" disabled>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Comentarios</label>
                                <textarea class="form-control" disabled>{{ $car->comments }}</textarea>
                            </div>
                        </div>                        
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-12 pb-2">
            <table class="table table-hover border bg-white" id="table-items">
                <thead>
                    <th>Servicio / Falla</th>
                    <th>Tipo de servicio</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Status</th>
                    <th class="text-end">Total</th>
                </thead>
                <tbody>
                    @foreach ($car->services as $service)
                        <tr>
                            <td>
                                <a href="{{ route('services.show', $service->id) }}">{{ Str::limit($service->fault, 80) }}</a>
                            </td>
                            <td>{{ $service->service_type }}</td>
                            <td>{{ $service->entry_date->format('d-m-Y') }}</td>
                            <td>{{ $service->finished_date->format('d-m-Y') ?? '' }}</td>
                            <td>
                                @if ($service->status == 'Finalizado')
                                    <span class="badge text-bg-success">{{ $service->status }}</span>    
                                @else
                                    <span class="badge text-bg-warning">{{ $service->status }}</span>
                                @endif
                            </td>
                            <td class="text-end">{{ Number::currency($service->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-12">

            <div class="row">
                <div class="col-md-6 mt-3">
                    <x-feathericon-clipboard class="table-icon"/>
                    @if (count($car->services) > 1)
                        Se encontraron {{ count($car->services) }} registros.
                    @else 
                        Se encontro {{ count($car->services) }} registro.
                    @endif
                </div>
                <div class="col-md-6 mt-3 text-end">
                    <a href="{{ route('cars.index') }}" class="btn btn-sm btn-success">Atras</a>
                    <a href="{{ route('reports.client', $car->id) }}" class="btn btn-sm btn-success">Mas Información</a>
                </div>
            </div>

        </div>


    </div>
</div>
@endsection