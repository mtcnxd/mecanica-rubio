@extends('includes.body')

@section('content')
@include('includes.alert')
<div class="window-container">    
    <div class="col-md-7">
        <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Perfil</span></h6>
        <div class="window-body shadow p-4">
            <div class="form-container border">
                @isset($user->avatar)
                    <div class="text-center mb-3">
                        <img src="{{ $user->avatar }}" width="86px" height="86px" class="rounded-pill border border-4">
                    </div>
                @endisset

                <div class="row">
                    <div class="col-md-12">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" disabled>
                        <input type="hidden" name="id" value="{{ $user->id }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <x-feathericon-smartphone class="table-icon" style="margin: -2px 5px 2px"/>
                            </span>
                            <input type="number" class="form-control" name="phone" value="{{ $user->phone }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Correo</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <x-feathericon-mail class="table-icon" style="margin: -2px 5px 2px"/>
                            </span>
                            <input type="text" class="form-control" name="email" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Fecha inicio</label>
                        <input type="date" class="form-control" name="date" value="{{ isset($user->created_at) ? $user->created_at->format('Y-m-d') : '' }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>Antiguedad</label>
                        <input type="text" class="form-control" value="{{ isset($user->created_at) ? $user->created_at->diffInMonths() : '-' }} meses" disabled>
                    </div>
                </div>

                <h6 class="border-bottom pt-4 pb-2">Notificaciones</h6>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="mt-2">
                            <input type="checkbox">
                            Recibir mensajes por correo electronico
                        </label>
                        <label class="mt-2">
                            <input type="checkbox">
                            Recibir mensajes por whatsapp
                        </label>
                    </div>
                </div>
                
                <h6 class="border-bottom pt-4 pb-2">Cambiar contraseña</h6>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Contraseña</label>
                        <input type="password" class="form-control" name="password" required placeholder="Cambiar contraseña">
                    </div>
                    <div class="col-md-6">
                        <label>Repetir</label>
                        <input type="password" class="form-control" name="repeat" required placeholder="Repetir contraseña">
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 text-end">
                    <a href="{{ route('admin.employee.index') }}" class="btn btn-sm btn-secondary">Atras</a>
                    <button type="submit" class="btn btn-sm btn-success">
                        <x-feathericon-save class="table-icon" style="margin: -2px 5px 2px"/>
                        Guardar
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


@section('js')
<script>
    $("#create").on('change', function(){
        if ($(this).prop('checked')) {
            $("#password").removeAttr('disabled');
            $("#repeat").removeAttr('disabled');
        } else {
            $("#password").attr('disabled', 'disabled');
            $("#repeat").attr('disabled','disabled');
        }
    });
</script>
@endsection