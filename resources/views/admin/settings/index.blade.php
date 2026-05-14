@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')

    <div class="col-md-6">
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Configuracion</span></h6>
        <div class="window-body shadow p-4">
            <form action="{{ route('admin.setting.store') }}" method="POST">
                <div class="form-container border">
                    <p class="fw-bold">Nuevo indice</p>
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <label>Clave</label>
                            <input type="text" class="form-control" name="name" value="">
                        </div>
                        <div class="col-md-7">
                            <label>Valor</label>
                            <input type="text" class="form-control" name="value">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3 text-end">
                            <button type="submit" class="btn btn-sm btn-success">
                                <x-feathericon-save class="table-icon" style="margin: -2px 5px 2px"/>
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <form action="" method="POST">
                <div class="form-container border">
                    <p class="fw-bold">Editar indice</p>
                    @csrf
                    @method('POST')
                    @foreach ($configs as $config)
                        <div class="row mt-3">
                            <div class="col-md-5">
                                <label>Clave</label>
                                <input type="text" class="form-control" name="name[]" value="{{ $config->name }}" disabled>
                            </div>
                            <div class="col-md-7">
                                <label>Valor</label>
                                <input type="text" class="form-control" name="{{ $config->name }}" value="{{ $config->value }}">
                            </div>
                        </div>
                    @endforeach

                    <div class="row">
                        <div class="col-md-12 mt-3 text-end">
                            <button type="submit" class="btn btn-sm btn-success">
                                <x-feathericon-save class="table-icon" style="margin: -2px 5px 2px"/>
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>    
</div>
@endsection