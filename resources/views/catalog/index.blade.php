@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Каталог</h1>

        <h3>Группы</h3>
        @include('partials.group-tree', [
            'groups' => $rootGroups,
            'activeGroup' => $currentGroup ?? null
        ])

        <h3>Товары</h3>
        <form method="GET" class="mb-3">
            @if(request()->has('group'))
                <input type="hidden" name="group" value="{{ request()->get('group') }}">
            @endif
            <select name="sort">
                <option value="name" {{ $sortField === 'name' ? 'selected' : '' }}>По названию</option>
                <option value="price" {{ $sortField === 'price' ? 'selected' : '' }}>По цене</option>
            </select>
            <select name="dir">
                <option value="asc" {{ $sortDir === 'asc' ? 'selected' : '' }}>↑</option>
                <option value="desc" {{ $sortDir === 'desc' ? 'selected' : '' }}>↓</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Сортировать</button>

            <select name="perPage" onchange="this.form.submit()">
                <option value="6" {{ request('perPage') == 6 ? 'selected' : '' }}>Показывать по 6</option>
                <option value="12" {{ request('perPage') == 12 ? 'selected' : '' }}>Показывать по 12</option>
                <option value="18" {{ request('perPage') == 18 ? 'selected' : '' }}>Показывать по 18</option>
            </select>

        </form>

        <div id="product-list" class="row">
            @foreach($products as $product)
                @include('partials.product-card', [
                   'product' => $product,
               ])
            @endforeach
        </div>

        <p id="product-stats">
            Показано {{ $products->firstItem() }}–{{ $products->lastItem() }} из {{ $products->total() }} товаров
        </p>
        @if ($products->hasMorePages())
            <button id="load-more"
                    class="btn btn-secondary"
                    data-next-page="{{ $products->currentPage() + 1 }}"
                    data-group="{{ request()->get('group', '') }}"
                    data-sort="{{ $sortField }}"
                    data-dir="{{ $sortDir }}">
                Показать ещё
            </button>
        @endif

            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loadMoreBtn = document.getElementById("load-more");
            const productList = document.getElementById("product-list");
            const stats = document.getElementById("product-stats");

            if (!loadMoreBtn) return;

            let shownCount = productList.children.length;

            loadMoreBtn.addEventListener("click", function () {
                const page = loadMoreBtn.dataset.nextPage;
                const group = loadMoreBtn.dataset.group;
                const sort = loadMoreBtn.dataset.sort;
                const dir = loadMoreBtn.dataset.dir;

                const url = new URL("{{ route('catalog.load-more') }}");
                url.searchParams.append("page", page);
                if (group) url.searchParams.append("group", group);
                if (sort) url.searchParams.append("sort", sort);
                if (dir) url.searchParams.append("dir", dir);

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            productList.insertAdjacentHTML("beforeend", data.html);
                            shownCount += data.count_loaded;

                            if (stats) {
                                stats.textContent = `Показано ${data.first_item}–${data.last_item} из ${data.count_total} товаров`;
                            }

                            if (data.has_more_pages) {
                                loadMoreBtn.dataset.nextPage = parseInt(page) + 1;
                            } else {
                                loadMoreBtn.remove();
                            }
                        }
                    })
                    .catch(err => {
                        console.error("Ошибка при загрузке товаров:", err);
                    });
            });
        });
    </script>

@endsection

<style>
    #app > main > div > nav > div.d-none.flex-sm-fill.d-sm-flex.align-items-sm-center.justify-content-sm-between > div:nth-child(1) > p{
        display: none;
    }
</style>
