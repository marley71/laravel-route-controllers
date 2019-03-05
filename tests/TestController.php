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

    public function postSimple() {
        return 'postSimple';
    }

    public function putSimple() {
        return 'putSimple';
    }

    public function anySimple1() {
        return 'anySimple1';
    }

    public function patchSimple() {
        return 'patchSimple';
    }

    public function deleteSimple() {
        return 'deleteSimple';
    }

    public function optionsSimple() {
        return 'optionsSimple';
    }


    public function getWithParam($param1,$param2="albero") {
        return 'getWithParam [1 ' . $param1 . "] [2 " . $param2 . "]";
    }

    public function postWith1Param($id) {
        return "postWith1Param $id";
    }

}
