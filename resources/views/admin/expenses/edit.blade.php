@extends('includes.body')

@section('content')
<div class="window-container">
    <div class="col-md-7">
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Egreso</span></h6>
        <div class="window-body shadow p-4">
            <form action="{{ route('admin.finance.expense.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
                <div class="form-container border">
                    @csrf
                    @method('PATCH')
                                        
                    <div class="row">
                        <div class="col-md-12">
                            <label>Concepto</label>
                            <input type="text" class="form-control" value="{{ $expense->name }}" disabled>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Cantidad</label>
                            <input type="text" class="form-control" value="{{ $expense->amount }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label>Precio</label>
                            <input type="text" class="form-control" value="{{ Number::currency($expense->price) }}" disabled>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Responsable</label>
                            <input type="text" class="form-control" value="{{ $expense->employee->name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label>Status</label>
                            <select class="form-select" name="status">
                                <option value="Pagado" {{ $expense->status == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                                <option value="Pendiente" {{ $expense->status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Descripcion</label>
                            <textarea cols="60" rows="5" class="form-control" disabled>{{ $expense->description }}</textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="row pt-3">
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="expense_date" value="{{ $expense->expense_date->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-8">
                            <input type="file" class="form-control" name="attach">
                        </div>
                    </div>
                    
                    @if ($expense->attach)
                        <div class="col-md-6">
                            <div class="row">
                                <div class="row col-md-6">
                                    <div class="card p-2">
                                        <img src="/public/uploads/expenses/{{ $expense->attach }}" width="auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('admin.finance.expense.index') }}" class="btn btn-sm btn-secondary">Atras</a>
                        <button type="submit" class="btn btn-sm btn-success">
                            <x-feathericon-save class="table-icon" style="margin: -2px 5px 2px"/>
                            Guardar
                        </button>
                    </div>
                </div>                
            </form>
        </div>
    </div>
</div>
@endsection