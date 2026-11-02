@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        @foreach ($errors->all() as $error)
            <div class="d-flex align-items-center gap-2">
                <x-feathericon-alert-triangle style="width:16px;height:16px;margin:-2px 8px 0 0;"/>{{ $error }}
            </div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <x-feathericon-check-circle style="width:16px;height:16px;margin:-2px 8px 0 0;"/>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif