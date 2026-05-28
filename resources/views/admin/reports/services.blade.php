@extends('includes.body')

@section('content')
<div class="window-container">
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Ingresos del mes</span></h6>
    <div class="window-body shadow">
        <table class="table border table-hover bg-white">
            <thead>
                <th width="3%">#</th>
                <th width="5%">Folio</th>
                <th width="10%">Fecha</th>
                <th>Concepto</th>
                <th class="text-end">Mano de obra</th>
                <th class="text-end">Total</th>
            </thead>
            <tbody class="bg-white">
                @php
                    $total = 0;
                @endphp

                @foreach ($list as $row)
                    @php
                        $total += $row->serviceItems->where('item','Servicio (mano de obra)')->first()->price;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td> #{{ $row->id }}</td>
                        <td>{{ Carbon\Carbon::parse($row->finished_date)->format('j M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.service.show', $row->id) }}">{{ $row->car->fullName }}</a>
                        </td>
                        <td class="text-end">{{ Number::currency($row->serviceItems->where('item','Servicio (mano de obra)')->first()->price) }}</td>
                        <td class="text-end">{{ Number::currency($row->total) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td class="text-end fw-bold">{{ Number::currency($total) }}</td>
                    <td class="text-end fw-bold">{{ Number::currency($list->sum('total')) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
