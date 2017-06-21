<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 17/06/2017
 * Time: 12:07
 */

namespace App\Http\Controllers;


class HeaderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


}