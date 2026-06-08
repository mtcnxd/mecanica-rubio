@extends('includes.body')

@section('content')
<div class="window-container">
    <div class="col-md-7">    
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Egreso</span></h6>
        <div class="window-body shadow p-4">
            <form action="{{ route('admin.finance.expense.store') }}" method="POST" enctype="multipart/form-data">
                <div class="form-container border">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label>Concepto</label>    
                            <div class="input-group">
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Descripción</label>
                            <textarea class="form-control" cols="30" rows="4" name="description"></textarea>
                        </div>                
                    </div>             
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Cantidad</label>
                            <input type="text" class="form-control" name="amount" value="1">
                        </div>
                        <div class="col-md-6">
                            <label>Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" name="price" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Responsable</label>
                            <select class="form-select" name="employee_id" required>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Estatus</label>
                            <select class="form-select" name="status" required>
                                <option>Pendiente</option>
                                <option>Pagado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Adjuntar recibo/nota</label>
                            <input type="file" class="form-control" name="attach">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('admin.finance.expense.index') }}" class="btn btn-sm btn-secondary">Cancelar</a>
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