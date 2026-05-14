@if ($status == 'Pendiente')
    <span class="badge text-bg-warning">{{ $status }}</span>
@endif

@if ($status == 'Finalizado')
    <span class="badge text-bg-primary">{{ $status }}</span>
@endif

@if ($status == 'Entregado')
    <span class="badge text-bg-success">{{ $status }}</span>
@endif

@if ($status == 'Cancelado')
    <span class="badge text-bg-danger">{{ $status }}</span>
@endif

@if ($status == 'Esperando cliente')
    <span class="badge text-bg-secondary">{{ $status }}</span>
@endif

@if ($status == 'Esperando refaccion')
    <span class="badge text-bg-secondary">{{ $status }}</span>
@endif