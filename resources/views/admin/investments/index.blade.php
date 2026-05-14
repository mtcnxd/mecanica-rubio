@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')

    <x-window_main title="Bitso wallet" class="py-4">
        <p class="fw-bold ps-2">Libro de compras</p>
        <table class="table table-hover table-responsive" id="bitso">
            <thead class="thead-inverse">
                <tr>
                    <th>Libro</th>
                    <th class="text-end">Cantidad</th>
                    <th class="text-end">Precio compra</th>
                    <th class="text-end">Valor compra</th>
                    <th class="text-end">Valor actual</th>
                    <th class="text-end">G/L %</th>
                    <th class="text-end">Fecha de compra</th>
                    <th style="width: 30px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results['crypto'] as $crypto)
                    <tr>
                        <td scope="row">{{ $crypto->book }}</td>
                        <td class="text-end">{{ $crypto->amount }}</td>
                        <td class="text-end">{{ Number::currency($crypto->price) }}</td>
                        <td class="text-end">{{ Number::currency($crypto->purchase_value) }}</td>
                        <td class="text-end">{{ Number::currency(0) }}</td>
                        <td class="text-end">
                            @if (true)
                                <span class="badge text-bg-danger rounded-pill">{{ Number::percentage(0, 2) }}</span>
                            @else
                                <span class="badge text-bg-success rounded-pill">{{ Number::percentage(0, 2) }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <span title="{{ $crypto->created_at->diffForHumans() }}">{{ $crypto->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="text-end">
                            <a href="#" class="cancell-trade" data-id="{{ $crypto->id }}">
                                <x-feathericon-trash class="table-icon" />
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td colspan="2"></td>
                    <td class="text-end fw-bold">{{ Number::currency(0) }}</td>
                    <td class="text-end fw-bold">{{ Number::currency(0) }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
        
        <div style="display: flex; justify-content:space-between;">
            <a href="#" class="ms-3 ps-3 pe-3 btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addShopping">Nueva Compra</a>
            <span class="pe-3">{{ 0 }} Registros encontrados</span>
        </div>
    </x-window_main>

    <div class="row mt-4">
        <div class="col-md-5">
            <div class="widget-simple">
                <canvas id="chartRevenue"></canvas>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <x-card_simple_overview_1
                    title="$ Incremento en 30 días"
                    message="{{ 0 }}"
                />
            </div>
            <div class="row mt-3">
                <x-card_simple_overview_1
                    title="% Incremento en 30 días"
                    message="{{ 0 }}"
                />
            </div>
            <div class="row mt-3">
                <x-card_simple_overview_1
                    title="Pending ..."
                    message="Loading ..."
                />
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget-simple" style="max-height: 330px;display: flex;justify-content: center;">
                <canvas id="chartPie"></canvas>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-5">
            <x-window_main title="Actualizar Saldo" class="p-4">
                <form action="{{ route('investments.update') }}" method="POST">
                    @csrf
                    <label for="investment_id" class="mb-1">Instrumento de inversion</label>
                    <select name="investment_id" class="form-select">
                        @foreach ($results as $investment)
                            <option value="{{-- $investment->id --}}">{{-- $investment->name --}}</option>
                        @endforeach
                    </select>
                    <label for="amount" class="mt-3 mb-1">Cantidad actual</label>
                    <input type="text" name="amount" class="form-control">
                    <button type="submit" class="ps-3 pe-3 btn btn-sm btn-secondary mt-3">Actualizar Saldo</button>
                </form>
            </x-window_main>
        </div>

        <div class="col-md-7">
            <x-window_main title="Mis Inversiones" class="pb-4" >
                <table class="table table-hover table-responsive" id="bitso">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Activo</th>
                            <th class="text-end">Cantidad</th>
                            <th class="text-end">Incremento</th>
                            <th class="text-end">Porcentaje</th>
                            <th class="text-end" width="25%">Ultima actualizacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sumDifference = 0;
                        @endphp
                        @foreach ($results['other'] as $investment)
                            <tr>
                                <td><a href="{{ route('investments.show', $investment->id) }}">{{ $investment->name }}</a></td>
                                <td class="text-end">{{ Number::currency(0) }}</td>
                                <td class="text-end">{{ Number::currency(0) }}</td>
                                <td class="text-end">{{ Number::percentage(0, 1) }}</td>
                                <td class="text-end">{{ $investment->date->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td class="text-end fw-bold">{{ 0 }}</td>
                            <td class="text-end fw-bold">{{ Number::currency($sumDifference) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </x-window_main>
        </div>
    </div>

</div>
@endsection

@include('admin.investments.modal_create')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const rutes = {
        investmentItemStore : "{{ route('api.bitso.store') }}",
        investmentItemRemove : "{{ route('api.bitso.destroy', ':id') }}",
    }
</script>
<script src="{{ asset('js/investments.js') }}"></script>
<script>
    var ctx = document.getElementById('chartRevenue').getContext('2d');
    var incomes = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($charts->getRevenueChart()['labels']),
            datasets: [{
                data: @json($charts->getRevenueChart()['values']),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Revenue'
                    }
                }
            },
            plugins:{
                legend: {
                    display: false
                }
            }
        }
    });

    var ctx = document.getElementById('chartPie').getContext('2d');
    var incomes = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($charts->getDiversificationChart()['labels']),
            datasets: [{
                data: @json($charts->getDiversificationChart()['values']),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.3)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(75, 192, 192, 0.9)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins:{
                legend: {
                    position: 'bottom',
                    display: true
                }
            }
        }
    });
</script>
@endsection