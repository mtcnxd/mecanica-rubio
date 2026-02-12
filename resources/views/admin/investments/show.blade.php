@extends('includes.body')

@php
    $first = $investment->investmentData->first();
    $last  = $investment->investmentData->last();
@endphp

@section('content')
<div class="window-container">
    @include('includes.alert')
    <h6 class="window-title shadow text-uppercase fw-bold">
        <span class="ms-3">{{ $investment->name }}</span>
    </h6>
    <div class="window-body shadow py-4">        
        <div class="row p-4 pt-0">
            <div class="col-md-3">
                <x-card_simple_overview_1
                    title="{{ $first->date }}"
                    message="{{ Number::currency($first->amount) }}"
                />
            </div>
            <div class="col-md-3">
                <x-card_simple_overview_1
                    title="{{ $last->date }}" 
                    message="{{ Number::currency($last->amount) }}"
                />
            </div>
            <div class="col-md-3">
                <x-card_simple_overview_1
                    title="$ Incremento a 30 días" 
                    message="{{ Number::currency($first->amount - $last->amount); }}"
                />
            </div>
            <div class="col-md-3">
                <x-card_simple_overview_1
                    title="% Incremento a 30 días" 
                    message="{{ Number::percentage(App\Http\Helpers::convertToPercentage($first->amount, $last->amount), 2); }}"
                />
            </div>  
        </div>

        <table class="table table-hover table-responsive" id="bitso">
            <thead class="thead-inverse">
                <tr>
                    <th>Fecha</th>
                    <th class="text-end">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($investment->investmentData->sortByDesc('created_at') as $item)
                    <tr>
                        <td title="{{ Carbon\Carbon::parse($item->date)->diffForHumans() }}">
                            {{ Carbon\Carbon::parse($item->date)->format('d M Y') }}
                        </td>
                        <td class="text-end">{{ Number::currency($item->amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection