<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;


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
        $webs = DB::table('webs')->inRandomOrder()->limit(10)->paginate(10);
        $options = new \stdClass();
        $options->title = ["text" => "Site shares evolution"];
        $options->yAxis["title"] = ["text" => "Share price"];
        $options->xAxis["title"] = ["text" => "Day"];
        $user = User::find(1);

        foreach ($webs as $web) {
            $options->series[] = ['name' => $web->name.' ('.$web->url.')','data' => [1,2,3,4]];
        }
        return response()->json(['webs' => $webs,'options' => $options]);
    }

}
