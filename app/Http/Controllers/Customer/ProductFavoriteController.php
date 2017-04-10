<?php

namespace App\Http\Controllers\Customer;

use App\ProductFavorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductFavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexs(){
        $per_page = 50;
        $product_favorite = ProductFavorite::where([
            'user_id' => Auth::user()->id,
        ])
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        return view('customer/product_favorite', [
            'page_title' => 'Sản phẩm đã lưu',
            'product_favorite' => $product_favorite
        ]);
    }
}
