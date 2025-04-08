@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Каталог</a></li>
                @foreach($breadcrumbs as $crumb)
                    <li class="breadcrumb-item">
                        <a href="{{ route('catalog.index', ['group' => $crumb->id]) }}">{{ $crumb->name }}</a>
                    </li>
                @endforeach
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="card p-4">
            <h2>{{ $product->name }}</h2>
            <p><strong>Цена:</strong> {{ $product->price->price ?? '—' }}</p>
        </div>
    </div>
@endsection
