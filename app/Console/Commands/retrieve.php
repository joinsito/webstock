<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Web;

class retrieve extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rank:retrieve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves data from AWS AWIS to store it in db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $webs = Web::all(['id', 'url']);
        foreach ($webs as $key =>  $site) {
            $awisInfo = new \App\library\awis(env('AWS_ACCESSKEY'), env('AWS_SECRETKEY'), $site->url);
            $webs[$key]->awis = $awisInfo->getUrlInfo();
        }
        print_r($webs);
    }
}
