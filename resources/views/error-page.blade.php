@extends('includes.body')

@section('content')
<div class="window-container">    
    <h6 class="window-title shadow text-uppercase fw-bold"><span class="ms-3">Error page</span></h6>
    <div class="window-body shadow p-4">
        <div class="row m-1 mb-3 pb-3">
            <div class="alert alert-warning p-4">
                <div class="row m-1 mb-3 pb-3">
                    <div class="col-md-12 text-center">
                        <x-feathericon-alert-triangle style="height: 100px; width: 100px"/>
                    </div>
                </div>
                <div class="row m-1 mb-3 pb-3 text-center">
                    <div class="col-md-12 text-center">
                        @if (session()->has('warning'))
                            <p class="fw-bold text-center">Something went wrong!!!</p>
                            <p>{{ session('warning') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection