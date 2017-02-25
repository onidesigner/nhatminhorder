<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Exchange;
use Illuminate\Support\Facades\Request;
use App\Cart;

class AddonController extends Controller
{

    public function __construct()
    {

    }

    public function addCart(Request $request){
        $params = Request::all();
        if(Cart::addCart($params)):

            return Response::json(array('success' => true));
        endif;
        return Response::json(array('success' => false));
    }

    public function getExchange(){
        return Exchange::getExchange();
    }
}
