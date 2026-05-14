@extends('includes.body')

@section('content')
<div class="window-container">
    <div class="col-md-7">
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">automovil</span></h6>
        <div class="window-body shadow p-4">
            <div class="form-container border">
                <form action="{{ route('cars.update', $auto->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label>Cliente</label>
                            <input type="text" class="form-control" name="client" id="client" value="{{ $auto->name }}" disabled>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Marca</label>    
                            <div class="input-group">
                                <select class="form-select" id="select-brand" name="brand">
                                    @if (isset($auto))
                                        <option>{{ $auto->brand  }} </option>
                                    @else
                                        <option>- Seleccione una marca -</option>
                                    @endif
                                    @foreach ($brands as $brand)
                                        <option>{{ $brand->brand }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-text">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#createBrand">Agregar</a>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label>Modelo</label>
                            <div class="input-group">
                                <select class="form-select" id="select-model" name="model">
                                    @if (isset($auto))
                                        <option>{{ $auto->model }}</option>
                                    @else
                                        <option>- Seleccione un modelo -</option>
                                    @endif
                                </select>
                                <span class="input-group-text">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#createModel">Agregar</a>
                                </span>
                            </div>
                        </div>    
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label>VIN</label>
                            <div class="input-group">
                                <input type="text" name="serie" id="serie" class="form-control" value="{{ isset($auto->serie) ? $auto->serie : '' }}">
                            </div>
                        </div>    
                        <div class="col-md-4">
                            <label>Año</label>
                            <input type="text" class="form-control" name="year" value="{{ isset($auto) ? $auto->year : '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label>Placa</label>
                            <input type="text" class="form-control" name="plate" value="{{ isset($auto) ? $auto->plate : '' }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Comentarios</label>
                            <textarea class="form-control" cols="30" rows="4" name="comments">{{ isset($auto) ? $auto->comments : '' }}</textarea>
                        </div>                
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mt-3 text-end">
                            <a href="{{ route('admin.car.index') }}" class="btn btn-sm btn-secondary">Cancelar</a>
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

@section('modal') 
<div class="modal fade" id="createBrand" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Crear nueva marca</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 pt-2 text-end">
                        Marca
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="new_brand">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3 pt-2 text-end">
                        Premium
                    </div>
                    <div class="col-md-9 pt-2">
                        <input class="form-check-input" type="checkbox" id="premium">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="newBrand">Guardar</button>
            </div>
        </div>
    </div>
</div>    

<div class="modal fade" id="createModel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Crear nuevo modelo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 pt-2 text-end">
                        Marca
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="model_brand">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3 pt-2 text-end">
                        Modelo
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="model">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="newModel">Guardar</button>
            </div>
        </div>
    </div>
</div>    
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#newBrand').on('click', function(){
        const brand   = $("#new_brand").val();
        const premium = $("#premium").prop('checked');
        
        $.ajax({
            url: "createBrand",
            method: 'POST',
            data:{
                brand:brand,
                premium:premium
            },
            success:function(response){
                $('#createBrand').modal('hide');
                $("#select-brand").empty();

                const brands = JSON.parse(response);
                brands.forEach(brand => {
                    $("#select-brand").append('<option>' + brand.brand + '</option');
                });

                showMessageAlert();
            }
        });

    });

    $("#select-brand").on('change', function(){
        const brand = $("#select-brand").val();
        $("#select-model").empty();
        $("#model_brand").val(brand);

        $.ajax({
            url:"loadModels",
            method: 'POST',
            data:{
                brand:brand
            },
            success:function(response){
                const models = JSON.parse(response);

                models.forEach( model => {
                    $("#select-model").append('<option>' + model.model + '</option');
                });
            }
        });
    });        

    $('#newModel').on('click', function(){
        const brand = $("#model_brand").val();
        const model = $("#model").val();

        $.ajax ({
            url: "createModel",
            method:'POST',
            data: {
                brand:brand,
                model:model
            },
            success:function(response){
                $('#createModel').modal('hide');
                $("#select-model").empty();

                const models = JSON.parse(response);
                models.forEach(model => {
                    $("#select-model").append('<option>' + model.model + '</option');
                });

                showMessageAlert();
            }
        });
    
    });
});

function showMessageAlert(){
    Swal.fire({
        text: 'Los datos se guardaron correctamente',
        icon: 'success',
        confirmButtonText: 'Aceptar'
    })
}
</script>    
@endsection