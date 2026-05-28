@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')

    <h3 class="mb-4">{{ $servicesByClient[0]->client->name }}</h3>
    <div class="row col-md-12">
        <div class="col-md-4">
            <div class="widget-simple">
                <div class="widget-simple-head">
                    <span class="pt-1">Ingreso generados</span>
                    <x-feathericon-tool class="window-title-icon"/>
                </div>
                <div class="widget-simple-body fs-3">
                    <h3>{{ Number::currency($servicesByClient->sum('total')) }}</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4"> 
            <div class="widget-simple">
                <div class="widget-simple-head">
                    <span class="pt-1">Servicios ingresados</span>
                    <x-feathericon-tool class="window-title-icon"/>
                </div>
                <div class="widget-simple-body fs-3">
                    <h3>{{ $servicesByClient->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row col-md-12 mt-4">
        @foreach ($servicesByClient as $service)
            <div class="row border p-2 m-1 shadow-sm" style="background-color: var(--gray-100)">
                <p class="mb-0 fs-6 fw-bold">{{ $service->car->fullName }} {{ $service->car->year }} | Servicio: #{{ $service->id }}</p>
            </div>
            @foreach ($service->serviceItems as $serviceItem)
                <div class="row p-1 m-1">
                    <div class="col-md-4">{{ $serviceItem->item }}</div>
                    <div class="col-md-3">{{ $serviceItem->supplier }}</div>
                    <div class="col text-end">{{ Number::currency($serviceItem->price) }}</div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
@endsection
