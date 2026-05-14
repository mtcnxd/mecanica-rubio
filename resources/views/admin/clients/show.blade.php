@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')    
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Cliente</span></h6>
    <div class="window-body shadow p-4">
        <div class="form-container border">
            <form method="POST">
                <div class="row pt-0 pb-0">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-text"> #{{ $client->id }}</span>
                                    <input type="text" class="form-control" name="name" value="{{ isset($client) ? $client->name : '' }}" disabled>
                                    <span class="input-group-text">
                                        <a href="{{ route('admin.client.edit', $client->id) }}">
                                            <x-feathericon-edit class="table-icon"/>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Correo</label>
                                <input type="text" class="form-control" name="email" value="{{ isset($client) ? $client->email : '' }}" disabled>
                            </div>                
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Telefono</label>
                                <input type="text" class="form-control" name="phone" value="{{ isset($client) ? $client->phone : '' }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>RFC</label>
                                <input type="text" class="form-control" name="rfc" value="{{ isset($client) ? $client->rfc : '' }}" disabled>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Comentarios</label>
                                <textarea class="form-control" rows="3" name="comments" disabled>{{ isset($client) ? $client->comments : '' }}</textarea>
                            </div>
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Dirección</label>
                                <input type="text" class="form-control" name="street" value="{{ isset($client) ? $client->street : '' }}" disabled>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Colónia</label>
                                <select class="form-select" id="address" name="address" disabled>
                                    @if ( isset($client) )
                                        <option>{{ $client->address }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Codigo Postal</label>
                                <input type="text" class="form-control" id="postcode" name="postcode" value="{{ isset($client) ? $client->postcode : '' }}" disabled>
                            </div>                            
                        </div>
                        <div class="row mt-3"> 
                            <div class="col-md-6">
                                <label>Ciudad</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ isset($client) ? $client->city : '' }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Estado</label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ isset($client) ? $client->state : '' }}" disabled>
                            </div>
                        </div>
                    </div>                
                </div>
            </form>
        </div>

        <div class="mt-4 mb-4">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" aria-selected="true">
                        <x-feathericon-tool class="table-icon" style="margin: 0 0 2px 5px"/>
                        Servicios
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" aria-selected="false">
                        <x-feathericon-key class="table-icon" style="margin: 0 0 2px 5px"/>
                        Autos
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="home-tab-pane" aria-labelledby="home-tab">
                    <table class="table table-hover border border-top-0 bg-white" id="table-items">
                        <thead>
                            <th width="300px">Automovil</th>
                            <th>Año</th>
                            <th>Servicio/Fallo</th>
                            <th></th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Fecha servicio</th>
                        </thead>
                        <tbody>
                            @foreach ($client->services as $service)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.car.show', $service->car->id) }}">{{ $service->car->carName() }}</a>
                                </td>
                                <td>{{ $service->car->year }}</td>
                                <td>{{ $service->fault }}</td>
                                <td>
                                    @if ($service->quote)
                                        <span class="badge text-bg-warning">Cotización</span>
                                    @endif
                                </td>
                                <td class="text-center"><span class="badge text-bg-success">{{ $service->status }}</span></td>
                                <td class="text-end">
                                    <span title="{{ $service->created_at->diffForHumans() }}">
                                        {{ $service->created_at->format('j M Y') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="pt-2 mb-0">
                        <x-feathericon-clipboard class="table-icon" style="margin-top:-4px;"/>
                        Se encontraron {{ $client->services->count() }} servicios.
                    </p>
                </div>
                <div class="tab-pane fade" id="profile-tab-pane" aria-labelledby="profile-tab">
                    <table class="table table-hover border border-top-0 bg-white" id="table-items">
                        <thead>
                            <th width="300px">Automovil</th>
                            <th>Año</th>
                            <th>VIN <span class="text-muted"> (Vehicle Identification Number)</span></th>
                            <th>Matrícula</th>
                        </thead>
                        <tbody>
                            @foreach ($client->cars as $car)
                            <tr>
                                <td>
                                    <x-feathericon-arrow-right-circle class="table-icon" style="margin: 0 5px 2px"/>
                                    <a href="{{ route('admin.car.show', $car->id) }}">{{ $car->brand }} {{ $car->model }}</a>
                                </td>
                                <td>{{ $car->year }}</td>
                                <td>{{ $car->serie }}</td>
                                <td>{{ $car->plate }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-end">
                <a href="{{ route('admin.client.index') }}" class="btn btn-sm btn-success">Atras</a>
            </div>
        </div>
    </div>
</div>
@endsection