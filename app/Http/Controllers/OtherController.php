<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtherController extends Controller
{
    public function renderPageNotFound()
    {
        return view('not-found', [
            'page_title' => 'Page 404'
        ]);
    }

    public function renderPageNotPermission()
    {
        return view('not-permission', [
            'page_title' => 'Page 403'
        ]);
    }
}
