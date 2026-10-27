<h6 class="window-title text-uppercase fw-bold">
    <span class="ms-3">{{ $title }}</span>
</h6>
<div {{ $attributes->merge(['class' => 'window-body']) }}>
    {{ $slot }}
</div>