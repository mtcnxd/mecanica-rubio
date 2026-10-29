@extends('includes.body')

@section('content')
@include('includes.alert')

<div class="window-container">
    <h6 class="window-title text-uppercase fw-bold d-flex justify-content-between align-items-center">
        <span class="ms-3">Cliente</span>
    </h6>
    <div class="window-body p-4">
        {{-- Client profile header --}}
        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
            <span class="client-avatar me-3">{{ Str::limit($client->name, 1, null) }}</span>
            <div>
                <h5 class="mb-1 fw-bold">
                    {{ $client->name }}
                    <a href="{{ route('admin.client.edit', $client->id) }}" class="btn btn-link btn-sm me-2 fs-7">
                        Editar
                    </a>
                </h5>
                <div class="text-muted fs-7">
                    @if($client->email)
                        <x-feathericon-mail class="table-icon me-1" style="margin-top:-2px; color: var(--blue-gray-500)"/>
                        {{ $client->email }}
                        &nbsp;&nbsp;
                    @endif
                    @if($client->phone)
                        <x-feathericon-phone-call class="table-icon me-1" style="margin-top:-2px; color: var(--amber-700)"/>
                        {{ $client->phone }}
                    @endif
                </div>
                @if($client->created_at)
                <div class="text-muted fs-8 mt-1">
                    Cliente desde {{ $client->created_at->format('j M Y') }}
                    <span class="text-muted"> · #{{ $client->id }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Client info --}}
        <div class="form-container">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted fs-8 text-uppercase fw-bold mb-1">Teléfono</label>
                            <p class="mb-3">{{ $client->phone ?: '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fs-8 text-uppercase fw-bold mb-1">RFC</label>
                            <p class="mb-3">{{ $client->rfc ?: '—' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label text-muted fs-8 text-uppercase fw-bold mb-1">Correo</label>
                            <p class="mb-3">{{ $client->email ?: '—' }}</p>
                        </div>
                    </div>
                    @if($client->comments)
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label text-muted fs-8 text-uppercase fw-bold mb-1">Comentarios</label>
                            <p class="mb-0">{{ $client->comments }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted fs-8 text-uppercase fw-bold mb-1">Dirección</label>
                    <p class="mb-3">{{ $client->street ?: '—' }}</p>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted fs-8 text-uppercase fw-bold mb-1">Colónia</label>
                            <p class="mb-3">{{ $client->address ?: '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fs-8 text-uppercase fw-bold mb-1">Código Postal</label>
                            <p class="mb-3">{{ $client->postcode ?: '—' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted fs-8 text-uppercase fw-bold mb-1">Ciudad</label>
                            <p class="mb-0">{{ $client->city ?: '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fs-8 text-uppercase fw-bold mb-1">Estado</label>
                            <p class="mb-0">{{ $client->state ?: '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs: Services & Cars --}}
        <div class="mt-4 mb-4">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" aria-selected="true">
                        <x-feathericon-tool class="table-icon" style="margin: 0 0 2px 5px"/>
                        Servicios
                        <span class="badge bg-success pt-2 ms-1">{{ $client->services->count() }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" aria-selected="false">
                        <x-feathericon-key class="table-icon" style="margin: 0 0 2px 5px"/>
                        Autos
                        <span class="badge bg-success pt-2 ms-1">{{ $client->cars->count() }}</span>
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="home-tab-pane" aria-labelledby="home-tab">
                    <table class="table table-hover border border-top-0 bg-white" id="table-items">
                        <thead>
                            <th width="300px">Automovil</th>
                            <th>Servicio/Fallo</th>
                            <th></th>
                            <th>Status</th>
                            <th class="text-end">Fecha servicio</th>
                        </thead>
                        <tbody>
                            @foreach ($client->services as $service)
                            <tr>
                                <td>
                                    <x-feathericon-arrow-right-circle class="table-icon" style="margin: 0 5px 2px"/>
                                    <a href="{{ route('admin.car.show', $service->car->id) }}">{{ $service->car->fullName }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.service.show', $service->id) }}">{{ $service->fault }}</a>
                                </td>
                                <td>
                                    @if ($service->quote)
                                        <span class="badge text-bg-warning">Cotización</span>
                                    @endif
                                </td>
                                <td>
                                    <x-badge-simple status="{{ $service->status }}"></x-badge-simple>
                                </td>
                                <td class="text-end">
                                    <span title="{{ $service->created_at->diffForHumans() }}">
                                        {{ $service->created_at->format('j M Y') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="profile-tab-pane" aria-labelledby="profile-tab">
                    <table class="table table-hover border border-top-0 bg-white">
                        <thead>
                            <th width="300px">Automovil</th>
                            <th>VIN <span class="text-muted">(Vehicle Identification Number)</span></th>
                            <th>Matrícula</th>
                        </thead>
                        <tbody>
                            @foreach ($client->cars as $car)
                            <tr>
                                <td>
                                    <x-feathericon-arrow-right-circle class="table-icon" style="margin: 0 5px 2px"/>
                                    <a href="{{ route('admin.car.show', $car->id) }}">{{ $car->fullName }}</a>
                                </td>
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
            <div class="col-md-12">
                <a href="{{ route('admin.client.index') }}" class="btn btn-sm btn-success">Atras</a>
            </div>
        </div>
    </div>
</div>
@endsection
