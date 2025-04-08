@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Каталог</h1>

        <h3>Группы товаров</h3>
        <ul>
            @foreach($groups as $group)
                <li>
                    <a href="{{ route('catalog.group', $group->id) }}">{{ $group->name }}</a>
                    ({{ $group->products->count() }}) {{-- временно, позже добавим учёт подгрупп --}}
                </li>
            @endforeach
        </ul>

        <h3>Товары</h3>
        <form method="GET" class="mb-3">
            <select name="sort">
                <option value="name" {{ $sortField === 'name' ? 'selected' : '' }}>По названию</option>
                <option value="price" {{ $sortField === 'price' ? 'selected' : '' }}>По цене</option>
            </select>
            <select name="dir">
                <option value="asc" {{ $sortDir === 'asc' ? 'selected' : '' }}>↑</option>
                <option value="desc" {{ $sortDir === 'desc' ? 'selected' : '' }}>↓</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Сортировать</button>
        </form>

        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-3">
                    <div class="card p-2">
                        <h5>{{ $product->name }}</h5>
                        <p>Цена: {{ $product->price->price ?? '—' }}</p>
                        <a href="{{ route('catalog.product', $product->id) }}" class="btn btn-outline-primary btn-sm">Подробнее</a>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endsection
