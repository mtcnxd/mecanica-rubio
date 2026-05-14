@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <div class="col-md-7">
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Servicio</span></h6>
        <div class="window-body shadow p-4">
            <form action="{{ route('admin.service.store') }}" method="POST">
                <div class="form-container border">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label for="select-client">Cliente</label>    
                            <div class="input-group">
                                <select class="form-select" id="select-client" name="client_id" required>
                                    <option value="">- Seleccione un cliente -</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="car">Automóvil</label>
                            <select class="form-select" name="car_id" id="car" >
                                <option>- Seleccione un automovil -</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="odometer">Odómetro</label>
                            <div class="input-group">
                            <input type="text" class="form-control" name="odometer" id="odometer">
                                <span class="input-group-text">Km</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="fault">Servicio / Fallo reportado</label>
                            <textarea class="form-control" cols="30" rows="4" name="fault" required></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="me-3">
                                <input type="radio" value="Fallo" name="service_type" id="type" checked>
                                Fallo/Reparación
                            </label>
                            <label class="me-3">
                                <input type="radio" value="Menor" name="service_type" id="type">
                                Servicio Menor
                            </label>
                            <label>
                                <input type="radio" value="Mayor" name="service_type" id="type">
                                Servicio Mayor
                            </label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="comments">Comentarios</label>
                            <textarea class="form-control" cols="30" rows="4" name="comments"></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 pt-2">
                            <label class="me-3">
                                <input type="checkbox" value="quote" name="quote" id="quote">
                                Cotizacion
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('admin.service.index') }}" class="btn btn-sm btn-secondary">Cancelar</a>
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

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $("#select-client").select2();

    const selectCars = $("#car");
    
    $("#select-client").on('change', function(event){
        var clientId = $(this).val();

        $.ajax({ 
            url: "{{ route('api.client.car.show', ':client') }}".replace(':client', clientId),
            method: 'GET',
            success:function(response){
                console.log(response);
                selectCars.empty();

                if (!response.success){
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });

                    return;
                }

                response.data.forEach(car => {
                    selectCars.append('<option value="'+ car.id +'">'+ car.brand +' '+ car.model +' ['+ car.year +']</option>');
                });
            }
        });
    });

});
</script>    
@endsection
