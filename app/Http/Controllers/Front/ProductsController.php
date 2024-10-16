<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index(){
        $products = Product::with(['category'])->active()->latest()->limit(8)->get();
        return view('front.home',compact('products'));
    }

    public function show(Product $product){
      if($product->status != 'active'){
        abort(404);
      }
      return view('front.products.show', compact('product'));
    }

}
