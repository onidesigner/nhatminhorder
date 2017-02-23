<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotPermissionController extends Controller
{
    public function index()
    {
        return view('not-permission', [
            'page_title' => 'Page 403'
        ]);
    }
}
