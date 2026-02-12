@extends('includes.body')

@section('content')
@include('includes.alert')
<div class="window-container">
    <div class="col-md-7">
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Cliente</span></h6>
        <div class="window-body shadow p-4">
            <form action="{{ route('clients.store') }}" method="POST">
                <div class="form-container border">
                    @method('POST')
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" name="name" id="name" required autocomplete="off">
                            <ul id="resultClientsList" style="display: none;" class="float-suggestions"></ul>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="email">Correo</label>
                            <input type="text" class="form-control" name="email" id="email">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="phone">Teléfono</label>
                            <input type="number" class="form-control" name="phone" id="phone" required>
                        </div>
                        <div class="col-md-6">
                            <label for="postcode">Código Postal</label>
                            <input type="text" class="form-control" name="postcode" id="postcode">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="address">Colónia</label>
                            <select class="form-select" name="address" id="address">
                                <option value=""> - Selecciona una colonia - </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="street">Calle / Número</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="street" id="street">
                                <span class="input-group-text">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#searchModal">Buscar</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="city">Ciudad</label>
                            <input type="text" class="form-control" name="city" id="city">
                        </div>
                        <div class="col-md-6">
                            <label for="state">Estado</label>
                            <input type="text" class="form-control" name="state" id="state">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="rfc">RFC</label>
                            <input type="text" class="form-control" name="rfc" id="rfc">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="comments">Comentarios</label>
                            <textarea class="form-control" cols="30" rows="4" name="comments" id="comments"></textarea>
                        </div>
                    </div>                
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('clients.index') }}" class="btn btn-sm btn-secondary">Cancelar</a>
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
                        <input type="text" class="form-control" id="textPostalCode" autocomplete="off">
                        <ul id="resultPostalList" style="display: none;" class="float-suggestions"></ul>
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
    $('#name').on('keyup', function(){
        if (this.value.length >= 4){
            $.ajax({
                url: "{{ route('client.search') }}",
                method: "GET",
                data: {
                    name:this.value
                },
                success:function (response){
                    console.log(response);
                    $("#resultClientsList").empty();
                    $("#resultClientsList").show();
                    response.data.forEach( (client) => {
                        $("#resultClientsList").append("<li>"+ client.name +"</li>");
                    })
                }
            })
        }
    });

    $("#postcode").on('focus', function(){
        ajaxRequest(this);
    })

    $("#postcode").on('keyup', function() {
        ajaxRequest(this);
    });

    $("#textPostalCode").on('keyup', function(){
        if (this.value.length > 3) {
            $.ajax({
                url:"{{ route('client.searchPostalCode') }}",
                method: 'GET',
                data:{ address:this.value },
                success: function(response){
                    $("#resultPostalList").empty();
                    $("#resultPostalList").show();
                    response.data.forEach(function(pc){
                        $("#resultPostalList").append("<li onClick='selectItem("+ pc.postalcode +")'>" + pc.postalcode +" - "+ pc.address + "</a></li>");
                    })
                }
            });
        }
    });

    $("#deleteClient").on('click', function(btn){
        var client = $(this).data('bsClient');

        $.ajax({
            url: "{{ route('client.delete') }}",
            method: 'POST',
            data: {
                client:client
            },
            success: function(response){
                showMessageAlert(response);
            }
        });
    });

    function ajaxRequest(element){
        if (element.value.length >= 4){
            $.ajax({
                url: "{{ route('client.searchPostalCode') }}",
                method: 'GET',
                data: {
                    postcode:element.value
                },
                success: function(response){
                    $("#address").empty();

                    console.log(response);

                    response.data.forEach(element => {
                        $("#address").append('<option>' + element.address + '</option>');
                        $("#city").val(element.city);
                        $("#state").val(element.state);
                    });
                }
            });
        }
    }

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
            location.replace('/admin/clients');
        });
    }
</script>
@endsection