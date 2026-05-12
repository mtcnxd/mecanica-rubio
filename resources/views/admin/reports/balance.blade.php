@extends('includes.body')

@section('content')

@php
    $income = 0;
    $expenses = 0;
@endphp

<div class="window-container">
    <div class="row mb-4">
        <div class="col-md-12">
            <table class="table border table-hover">
                <thead>
                    <th width="40px">#</th>
                    <th>Concepto</th>
                    <th>Fecha</th>
                    <th class="text-end">Ingresos</th>
                    <th class="text-end">Egresos</th>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">
                            <strong class="text-uppercase">Ingresos</strong>
                        </td>
                    </tr>
                    @foreach ($montlyData['services'] as $service)
                        @php
                            $income += $service->serviceItems->sum('price');
                        @endphp
                        <tr>
                            <td>{{ sprintf('#%s', $service->id) }}</td>
                            <td><strong>Ingreso: </strong> {{ $service->car->brand }} {{ $service->car->model }} [{{ $service->car->year }}]</td>
                            <td>{{ $service->finished_date->format('d/m/Y') }}</td>
                            <td class="text-end">{{ Number::currency($service->serviceItems->sum('price')) }}</td>
                            <td class="text-end"> - </td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #efefef;">
                        <td colspan="5">
                            <strong class="text-uppercase">Egresos</strong>
                        </td>
                    </tr>
                    @foreach ($montlyData['expenses'] as $expense)
                        @php
                            $expenses += $expense->amount * $expense->price;
                        @endphp
                        <tr>
                            <td>{{ sprintf('#%s', $expense->id) }}</td>
                            <td><strong>Egreso: </strong> {{ $expense->name }}</td>
                            <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                            <td class="text-end"> - </td>    
                            <td class="text-end">{{ Number::currency($expense->amount * $expense->price) }}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #efefef;">
                        <td colspan="5">
                            <strong class="text-uppercase">Nóminas</strong>
                        </td>
                    </tr>
                    @foreach ($montlyData['payrolls'] as $payroll)
                        @php
                            $expenses += $payroll->total;
                        @endphp

                        <tr>
                            <td>{{ sprintf('#%s', $payroll->id) }}</td>
                            <td><strong>Nomina: </strong> {{ $payroll->employee->name }} <strong>Periodo:</strong> {{ $payroll->start_date->format('d/m/Y') }} - {{ $payroll->end_date->format('d/m/Y') }}</td>
                            <td>{{ $payroll->paid_date->format('d/m/Y') }}</td>
                            <td class="text-end"> - </td>    
                            <td class="text-end">{{ Number::currency($payroll->total) }}</td>
                        </tr>
                    @endforeach

                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-end fw-bold">{{ Number::currency($income) }}</td>
                            <td class="text-end fw-bold">{{ Number::currency($expenses) }}</td>
                            <input type="hidden" id="income" value="">
                            <input type="hidden" id="expenses" value="">
                        </tr>
                    </tfoot>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header fs-6">
                    <strong>Saldo anterior</strong>
                </div>
                <div class="card-body">
                    {{ Number::currency($montlyData['balance']) }}
                </div>
            </div>
        </div>
    
        <div class="col-md-3">
            <div class="card">
                <div class="card-header fs-6">
                    <strong>Saldo actual <span class="fs-8 text-muted">(Ingresos-Egresos)</span></strong>
                </div>
                <div class="card-body">
                    @php
                        $currentBalance = 0;
                    @endphp
                    {{ Number::currency($income - $expenses) }}
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header fs-6">
                    <strong>Saldo nuevo</strong>
                </div>
                <div class="card-body">
                    {{ Number::currency($montlyData['balance'] + $income - $expenses) }}
                    <input type="hidden" id="balance" value="{{ ($montlyData['balance'] + $income - $expenses) }}">
                </div>
            </div>
        </div>
    </div>
    <hr style="color: var(--orange-800);">
    <div class="row col-md-4">
        <div class="col">
            <a class="btn btn-sm btn-outline-success" id="print" onclick="downloadPDF()">
                Imprimir
            </a>

            <a class="btn btn-sm btn-outline-success" id="closeMonth">
                Conciliar mes actual
            </a>
            <img src="{{ asset('/images/image.gif') }}" width="20px" height="20px" style="display:none;" class="ms-2" id="loader">
        </div>
    </div>    
</div>
@endsection


@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const btnClose = document.getElementById('closeMonth');

btnClose.addEventListener('click', (btn) => {
    btn.preventDefault();
    let income   = document.getElementById('income').value;
    let expenses = document.getElementById('expenses').value;
    let balance  = document.getElementById('balance').value;

    if (
        confirm('¿Confirmas que deseas cerrar el mes actual?')
    ){
        $("#loader").show();
        $.ajax({
            method: 'POST',
            data: {
                income:income,
                expenses:expenses,
                balance:balance
            },
            success: function(response){
                if (response.success){
                    Swal.fire({
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        history.go();
                    });
                }
            },
            error:function(error){
                console.log(error);
            }
        })
        .then(() => {
            $("#loader").hide();
        });
    }

});

function downloadPDF(){
    $.ajax({
        method:'POST',
        data:{},
        xhrFields: {
            responseType: 'blob' // Recibir respuesta como un Blob
        },
        success: function (response){
            const blob = new Blob([response], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = 'balance.pdf';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        },
    });
}
</script>
@endsection