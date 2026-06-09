@extends('includes.body')

@section('content')
<div class="window-container">
    <div class="row m-1 mb-3 pb-3">
        <div class="alert alert-warning p-4">
            <div class="row m-1 mb-3 pb-3">
                <div class="col-md-12 text-center">
                    <x-feathericon-alert-triangle style="height: 100px; width: 100px"/>
                </div>
            </div>
            <div class="row m-1 mb-3 pb-3 text-center">
                <div class="col-md-12 text-center">
                    <p class="fs-4 fw-bold text-center">Lamentablemente ocurrio un error !!!</p>
                    <p>{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection