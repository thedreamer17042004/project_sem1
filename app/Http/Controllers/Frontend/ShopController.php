<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {


        $product = Product::all();


        return view('frontend.shop.shop', compact('product'));
    }
}
