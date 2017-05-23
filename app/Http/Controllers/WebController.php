<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Web;
use Auth;
use Illuminate\Support\Facades\Storage;
use JonnyW\PhantomJs\Client;


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

    public function siteinfo($siteId) {
        $site = Web::find($siteId);
        if ($site->visited!=1) {
            $awisInfo = new \App\library\awis(env('AWS_ACCESSKEY'), env('AWS_SECRETKEY'), $site->url);
            $awsInfo = $awisInfo->getUrlInfo();
            $site->description = $awsInfo['description'];
            $site->links = $awsInfo['links'];
            $site->visited = 1;
            $site->save();
            $site->awis = $awsInfo;
        }else {
            $site->awis = new \stdClass();
            $site->awis->description = $site->description;
            $site->awis->links = $site->links;
        }
        if (!file_exists('images/sites/'.$siteId.'.jpg')) {
            // Capture site
            $client = Client::getInstance();
            $client->getProcedureCompiler()->enableCache();
            $client->getEngine()->addOption('--ignore-ssl-errors=true');
            $request  = $client->getMessageFactory()->createCaptureRequest("http://".$site->url);
            $request->setTimeout(4000);
            $request->setOutputFile('images/sites/'.$siteId.'.jpg');
            $width  = 1024;
            $height = 768;
            $top    = 0;
            $left   = 0;
            $request->setViewportSize($width, $height);
            $request->setCaptureDimensions($width, $height, $top, $left);
            $response = $client->getMessageFactory()->createResponse();
            $client->send($request, $response);
        }

        return response()
            ->json(['siteinfo' => $site]);
    }

    public function site($siteId,$siteUrl) {
        return view('siteinfo')->with('siteId',$siteId);
    }



}
