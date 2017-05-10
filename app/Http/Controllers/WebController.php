<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Web;
use Auth;
use Illuminate\Support\Facades\Storage;

class WebController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return mixed
     */
    public function signupwebform(Request $request) {
        $this->validate($request, [
            'site_name' => 'required|unique:webs,name|max:255'
        ]);
        $web = new Web;
        $web->name = $request->site_name;
        $web->description = $request->site_description;
        $web->url = $request->site_url;
        $web->user_id = Auth::user()->id;
        $web->current_value = 0;
        $web->previous_value = 0;
        $web->save();
        list($type, $data) = explode(';', $request->site_image);
        list(, $data)      = explode(',', $data);
        $encodedData = str_replace(' ','+',$data);
        $decodedData = base64_decode($encodedData);
        $imageName = $web->id;
        Storage::put('/images/sites/'.$imageName,$decodedData);

        return response()->json(['ok']);
    }
}
