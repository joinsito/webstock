<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function welcome()
    {
        $webs = DB::table('webs')->get();
        return response()
            ->json(['webs' => $webs]);
    }

}
