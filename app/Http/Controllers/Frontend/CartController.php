<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {

        

        return view('frontend.cart.cart');

    }
   



  
}
