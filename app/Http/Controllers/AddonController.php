<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Exchange;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Cart;

class AddonController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * @author vanhs
     * @param Request $request
     * @return mixed
     */
    public function addCart(Request $request){
        $not_login = Auth::guest();
        $params = Request::all();

        if($not_login):
            $view = View::make('add_to_cart_success', [
                'success' => false,
                'is_translate' => $params['is_translate'],
                'message' => 'Vui lòng <a style="color:blue;" target="_blank" href="' . url('login') . '">đăng nhập</a> vào hệ thống để tiến hành đặt hàng!'
            ]);
            $html = $view->render();
            return response()->json(['html' => $html]);
        endif;

        if(Cart::addCart($params)):
            $view = View::make('add_to_cart_success', [
                'success' => true,
                'is_translate' => $params['is_translate'],
                'price' => $params['price_origin'] > $params['price_promotion']
                    ? $params['price_promotion'] : $params['price_origin']
            ]);
            $html = $view->render();
            return response()->json(['html' => $html]);
        endif;

        $view = View::make('add_to_cart_success', [
            'success' => false,
            'is_translate' => $params['is_translate'],
            'price' => $params['price_origin'] > $params['price_promotion']
                ? $params['price_promotion'] : $params['price_origin'],
            'message' => 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ. Vui lòng thử lại!'
        ]);
        $html = $view->render();
        return response()->json(['html' => $html]);
    }

    /**
     * @author vanhs
     * @desc Cung cap ti gia cho cong cu dat hang
     * @return int
     */
    public function getExchange(){
        return Exchange::getExchange();
    }
}
