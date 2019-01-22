<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getSimple() {
        return "getSimple";
    }

    public function getWithParam($param1,$param2="albero") {
        return 'getWithParam [1 ' . $param1 . "] [2 " . $param2 . "]";
    }

    public function postSimple() {
        return 'postSimple';
    }

    public function postWith1Param($id) {
        return "postWith1Param $id";
    }

    public function putSimple() {
        return 'putSimple';
    }
}
