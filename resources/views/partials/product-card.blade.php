<div class="col-md-4 mb-3" data-product-id="{{ $product->id }}">
    <div class="card p-2">
        <h5>{{ $product->name }}</h5>
        <p>Цена: {{ $product->price->price ?? '—' }}</p>
        <a href="{{ route('catalog.product', $product->id) }}" class="btn btn-outline-primary btn-sm">Подробнее</a>
    </div>
</div>
