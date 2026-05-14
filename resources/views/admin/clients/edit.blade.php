@extends('includes.body')

@section('content')
<div class="window-container">
    @include('includes.alert')
    <div class="col-md-7">
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Buscar cliente</span></h6>
        <div class="window-body shadow p-4">
            <form action="{{ route('admin.client.update', $client->id) }}" method="POST">
                <div class="form-container border">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="name" value="{{ isset($client) ? $client->name : '' }}" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Correo</label>
                            <input type="text" class="form-control" name="email" value="{{ isset($client) ? $client->email : '' }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Teléfono</label>
                            <input type="number" class="form-control" name="phone" value="{{ isset($client) ? $client->phone : '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label>Código Postal</label>
                            <input type="text" class="form-control" id="postcode" name="postcode" value="{{ isset($client) ? $client->postcode : '' }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Colónia</label>
                            <select class="form-select" id="address" name="address">
                                @if ( isset($client) )
                                    <option>{{ $client->address }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                        
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Calle / Número</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="street" value="{{ isset($client) ? $client->street : '' }}">
                                <span class="input-group-text">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#searchModal">Buscar</a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Ciudad</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ isset($client) ? $client->city : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label>Estado</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ isset($client) ? $client->state : '' }}">
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>RFC</label>
                            <input type="text" class="form-control" name="rfc" value="{{ isset($client) ? $client->rfc : '' }}">
                        </div>
                    </div>
                        
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Comentarios</label>
                            <textarea class="form-control" cols="30" rows="4" name="comments">{{ isset($client) ? $client->comments : '' }}</textarea>
                        </div>
                    </div>                
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('admin.client.index') }}" class="btn btn-sm btn-secondary">Cancelar</a>
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
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Buscar codigo postal</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 pt-2 text-end">
                        Colonia
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="textPostalCode">
                        <ul id="resultList" style="display: none;" class="float-suggestions">
                            <li>Results</li>
                        </ul>
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

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $("#postcode").on('focus', function(){
        ajaxRequest(this);
    })

    $("#postcode").on('keyup', function() {
        ajaxRequest(this);
    });    

    function ajaxRequest(postcode) {
        console.log(postcode);

        /*
        if (postcode.length >= 4){
            $.ajax({
                url: "{{ route('api.postal-codes', ':postcode') }}".replace(':postcode', postcode),
                method: 'GET',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({postcode:postcode}),
                success: function(response){
                    $("#address").empty();
                    response.data.forEach(element => {
                        $("#address").append('<option>' + element.address + '</option>');
                        $("#city").val(element.city);
                        $("#state").val(element.state);
                    });
                }
            });
        }
        */
    }

    $("#textPostalCode").on('keyup', function(event){
        /*
        if (this.value.length > 3) {
            $.ajax({
                url:"{{ route('api.postal-codes') }}",
                method: 'GET',
                data:{
                    address:this.value
                },
                success: function(response){
                    $("#resultList").empty();
                    $("#resultList").show();
                    response.data.forEach((pc) => {
                        $("#resultList").append("<li onClick='selectItem("+ pc.postalcode +")'>" + pc.postalcode +" - "+ pc.address + "</a></li>");
                    })
                }
            });
        }
        */
    });

    function selectItem(postalcode){
        $("#searchModal").modal('hide');
        $("#postcode").val(postalcode);
        $("#postcode").focus();
    };

    function showMessageAlert(message){
        Swal.fire({
            text: message,
            icon: 'success',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            location.replace("{{ route('admin.client.index') }}");
        });
    }
</script>
@endsection