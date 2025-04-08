<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'name');
        $sortDir = $request->input('dir', 'asc');

        $groups = Group::where('id_parent', 0)->get();

        $products = Product::with('price')
            ->orderBy($sortField === 'price' ? 'price.price' : 'name', $sortDir)
            ->leftJoin('prices as price', 'products.id', '=', 'price.id_product')
            ->select('products.*')
            ->paginate(10);

        return view('catalog.index', compact('groups', 'products', 'sortField', 'sortDir'));
    }
}
