@extends('includes.body')

@section('content')
<div class="window-container">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-header">
                    <strong>Autos</strong>
                </div>
                <div class="card-body p-1">
                    <ul class="list-group list-group-flush" style="overflow-y: scroll; max-height: 450px;">
                        @foreach ($brands as $brand)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">{{ $brand->brand }}</div>
                                <span class="badge text-bg-warning rounded-pill">{{ $brand->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <strong>Refacciones</strong>
                </div>
                <div class="card-body p-1">
                    <ul class="list-group list-group-flush" style="overflow-y: scroll; max-height: 450px;">
                        @foreach ($items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <a href="#" onclick="getItemInformation(this)">{{ $item->item }}</a>
                                </div>
                                <span class="badge text-bg-warning rounded-pill">{{ $item->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mt-3" style="display: none;" id="div-results">
                <ul class="list-group list-group-flush" id="results">
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script>
    function getItemInformation(element){
        element.preventDefault;
        $.ajax({
            url: "{{ route('services.itemGetInfo') }}",
            method: "GET",
            data: { item:element.text },
            success: function (response) {
                $("#results").empty();
                $("#div-results").show();
                response.data.forEach((item) => {
                    $("#results").append('<li class="list-group-item d-flex justify-content-between">'+ item.brand +' '+ item.model +'</li>');
                })
            }
        });
    }
</script>
@endsection