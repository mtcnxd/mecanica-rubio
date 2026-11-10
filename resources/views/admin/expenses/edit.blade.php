@extends('includes.body')

@section('content')
<div class="window-container">
    <div class="row">
        <div class="col-md-7">
            <h6 class="window-title text-uppercase fw-bold"><span class="ms-3">Egreso</span></h6>
            <div class="window-body p-4">
                <form action="{{ route('admin.finance.expense.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
                    <div class="form-container">
                        @csrf
                        @method('PATCH')
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Responsable</label>
                                <input type="text" class="form-control" value="{{ $expense->employee->name }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Fecha</label>
                                <input type="date" class="form-control" name="expense_date" value="{{ $expense->expense_date->toDateString() }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Concepto</label>
                                <input type="text" class="form-control" value="{{ $expense->name }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Categoria</label>    
                                <select class="form-select" name="category" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}" {{ $expense->category == $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
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
                            <div class="col-md-12">
                                <label>Descripcion</label>
                                <textarea cols="60" rows="5" class="form-control" disabled>{{ $expense->description }}</textarea>
                            </div>
                        </div>
                        <hr>

                        <div class="row pt-3">
                            <div class="col-md-6">
                                <select class="form-select" name="status">
                                    <option value="Pagado" {{ $expense->status == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                                    <option value="Pendiente" {{ $expense->status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="file" class="form-control" name="attach">
                            </div>
                        </div>
                    </div>

                    @if ($expense->status != 'Pagado')
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('admin.finance.expense.index') }}" class="btn btn-sm btn-secondary">Atras</a>
                                <button type="submit" class="btn btn-sm btn-success">
                                    <x-feathericon-save class="table-icon" style="margin: -2px 5px 2px"/>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-none p-4">
                @if ($expense->attach == "")
                    <p>El archivo no esta disponible o no se adjunto al gasto</p>
                @else
                    <div class="row">
                        <div class="card p-2">
                            <img src="/uploads/expenses/{{ $expense->attach }}" width="auto">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection