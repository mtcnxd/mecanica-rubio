@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Buscar auto</span></h6>
    <div class="window-body shadow py-4">
        <table class="table table-hover table-borderless bg-white mb-4" id="autos">
            <thead>
                <tr>
                    <th>Automovil</th>
                    <th>Cliente</th>
                    <th>Comentario</th>
                    <th>VIN</th>
                    <th>Año</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($cars as $car)
                <tr>
                    <td>
                        <a href="{{ route('cars.show', $car->id) }}">
                            <span class="table-icon-round">{{ Str::limit($car->brand,1, null) }}</span>
                            {{ $car->brand }} {{ $car->model }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('clients.show', $car->client->id) }}">
                            {{ $car->client->name }}
                        </a>
                    </td>
                    <td>{{ $car->comments }}</td>
                    <td>{{ $car->serie }}</td>
                    <td>{{ $car->year }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    new DataTable('#autos', {
        pageLength: 10,
        lengthMenu: [10, 50, 100],
        columnDefs: [{
            orderable: false,
            target: [2,3,4]
        }]            
    });
</script>
@endsection