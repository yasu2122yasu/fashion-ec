<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.index')
            ->with('products', Product::get());
    }

    //ルーティングで指定した引数を受け取る
    public function show($id)
    {
        return view('product.show')
            ->with('product', Product::find($id));
    }
}
