@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <x-feathericon-check-circle style="width:16px;height:16px;margin:-2px 8px 0 0;"/>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <x-feathericon-alert-triangle style="width:16px;height:16px;margin:-2px 8px 0 0;"/>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
