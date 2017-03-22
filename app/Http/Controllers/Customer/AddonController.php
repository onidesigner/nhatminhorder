<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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
     * @desc API them san pham vao gio hang
     * @param Request $request
     * @return mixed
     */
    public function addCart(Request $request){
        $not_login = Auth::guest();
        $params = Request::all();

        if($not_login):
            $html = $this->__addon_alert_template(false, $params['is_translate'], 'Vui lòng <a style="color:blue;" target="_blank" href="' . url('login') . '">đăng nhập</a> vào hệ thống để tiến hành đặt hàng!');
            return response()->json(['html' => $html]);
        endif;

        if(Cart::addCart($params)):
            $price = $this->__get_price($params['price_origin'], $params['price_promotion']);
            $html = $this->__addon_alert_template(true, $params['is_translate'], null, $price);
            return response()->json(['html' => $html]);
        endif;

        $price = $this->__get_price($params['price_origin'], $params['price_promotion']);
        $html = $this->__addon_alert_template(false, $params['is_translate'], 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ. Vui lòng thử lại!', $price);
        return response()->json(['html' => $html]);
    }

    private function __get_price($price_origin, $price_promotion){
        return $price_origin > $price_promotion
            ? $price_promotion : $price_origin;
    }

    private function __addon_alert_template($success, $is_translate, $message = null, $price = null){
        $view = View::make('customer/add_to_cart_success', [
            'success' => $success,
            'is_translate' => $is_translate,
            'message' => $message,
            'price' => $price
        ]);
        return $view->render();
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
