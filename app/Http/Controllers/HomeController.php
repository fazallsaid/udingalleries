<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;

class HomeController extends Controller
{
    function index(Request $request){
        $auth = new AuthController;
        if(session('userRole') != "customer"){
            $auth->logout($request);
        }

        $title = "Home / Udin Gallery";
        $data = [
            'title' => $title
        ];
        return view('home', $data);
    }

    function products(){
        $title = "Produk / Udin Gallery";
        $data = [
            'title' => $title
        ];
        return view('pages.products.index', $data);
    }

    function categories(){
        $title = "Kategori / Udin Gallery";
        $data = [
            'title' => $title
        ];
        return view('pages.categories.index', $data);
    }

    function details($product_code, $product_slug){
        $title = "Detail Produk / Udin Gallery";
        $data = [
            'title' => $title,
            'product_code' => $product_code,
            'product_slug' => $product_slug
        ];
        return view('detail', $data);
    }

    function cart(){
        $title = "Keranjang Produk / Udin Gallery";
        $data = [
            'title' => $title
        ];

        return view('pages.cart.index', $data);
    }

    function checkout(){
        if(session('userRole') != 'user' && session('userLogin') != true){
            return redirect('/');
        }

        $user = Users::find(session('userId'));
        $title = "Selesaikan Pesanan / Udin Gallery";
        $data = [
            'title' => $title,
            'user' => $user
        ];

        return view('pages.checkout.index', $data);
    }

    function my(){
        if(session('userRole') != 'user' && session('userLogin') != true){
            return redirect('/');
        }

        $user = Users::find(session('userId'));
        $title = "Akun Saya / Udin Gallery";
        $data = [
            'title' => $title,
            'user' => $user
        ];

        return view('pages.my.index', $data);
    }

}
