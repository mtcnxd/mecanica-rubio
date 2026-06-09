@if ( $errors->any() )
    @foreach ($errors->all() as $error)
        <div class="alert alert-warning alert-dismissible fade show shadow mb-4">
            <x-feathericon-check-circle class="window-title-icon" style="margin: -3px 10px 0px;"/>
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>    
    @endforeach
@endif