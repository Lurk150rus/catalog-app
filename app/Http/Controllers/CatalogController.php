<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $rootGroups = Group::where('id_parent', 0)->with('children')->get();

        $sortField = $request->input('sort', 'name');
        $sortDir = $request->input('dir', 'asc');
        $groupId = $request->input('group');

        $expandedGroupId = null;

        $query = Product::with('price')
            ->leftJoin('prices as price', 'products.id', '=', 'price.id_product')
            ->select('products.*');

        $expandedGroupIds = [];

        if ($groupId) {
            $group = Group::with('children', 'parent')->findOrFail($groupId);

            // Получаем всех родителей + саму выбранную группу
            $expandedGroupIds = $group->getParentIds();
            $expandedGroupIds[] = $group->id;

            $groupIds = $group->getAllDescendantIds();
            $groupIds[] = $group->id;

            $query->whereIn('id_group', $groupIds);
        }


        $products = $query->orderBy($sortField === 'price' ? 'price.price' : 'name', $sortDir)
            ->paginate(10);

        return view('catalog.index', compact(
            'rootGroups', 'products', 'sortField', 'sortDir', 'groupId', 'expandedGroupIds'
        ));
    }

    public function product($id)
    {
        $product = Product::with(['price', 'group.parent'])->findOrFail($id);

        $breadcrumbs = $product->group->getBreadcrumbs();

        return view('catalog.product', compact('product', 'breadcrumbs'));
    }

    public function loadMoreProducts(Request $request)
    {
        $sortField = $request->input('sort', 'name');
        $sortDir = $request->input('dir', 'asc');
        $groupId = $request->input('group');
        $perPage = 10;

        $query = Product::with('price')
            ->leftJoin('prices as price', 'products.id', '=', 'price.id_product')
            ->select('products.*');

        if ($groupId) {
            $group = Group::with('children', 'parent')->findOrFail($groupId);

            $groupIds = $group->getAllDescendantIds();
            $groupIds[] = $group->id;

            $query->whereIn('id_group', $groupIds);
        }

        $products = $query->orderBy($sortField === 'price' ? 'price.price' : 'name', $sortDir)
            ->paginate($perPage);

        $html = '';
        foreach ($products as $product) {
            $html .= view('partials.product-card', compact('product'))->render();
        }

        return response()->json([
            'status' => 'success',
            'html' => $html,
            'count_loaded' => $products->count(),
            'count_total' => $products->total(),
            'first_item' => $products->firstItem(),
            'last_item' => $products->lastItem(),
            'has_more_pages' => $products->hasMorePages(),
        ]);
    }


}
