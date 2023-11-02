<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {

        $products = Product::all();

        return view('frontend.home.home', compact('products'));
    }
   
    public function blog()
    {

        // $product = Product::all();

        return view('frontend.blog.blog');
    }
    public function contact()
    {

        // $product = Product::all();

        return view('frontend.contact.contact');
    }
  
   
    public function about()
    {

        // $product = Product::all();

        return view('frontend.about.about');
    }
   

    public function product_detail()
    {

        $product = Product::all();

        return view('frontend.product.product_detail.product_detail', compact('product'));
    }



  
}
