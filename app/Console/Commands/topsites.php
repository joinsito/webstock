<?php

namespace App\Console\Commands;

use App\WebHistory;
use Illuminate\Console\Command;
use App\Web;
use Illuminate\Support\Facades\DB;


class topsites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rank:gettopsites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $awisInfo = new \App\library\awis(env('AWS_ACCESSKEY'), env('AWS_SECRETKEY'));
        $sites = $awisInfo->getTopSites(100000, 100, 'ES');
        foreach ($sites as $alexaSite) {
            $siteid = DB::table('webs')->where('url', $alexaSite['url'])->value('id');
            if (!$siteid) {
                $site = new Web();
                $site->name=$alexaSite['url'];
                $site->url=$alexaSite['url'];
                $site->description=$alexaSite['url'];
                $site->alexa_rank=$alexaSite['rank'];
                $site->alexa_globalrank=$alexaSite['globalrank'];
                $site->alexa_reach=$alexaSite['reach'];
                $site->alexa_pageviewsmillion=$alexaSite['pageviewsmillion'];
                $site->alexa_pageviewsuser=$alexaSite['pageviewsuser'];
                $site->user_id = 1;
                $site->current_value = 0;
                $site->previous_value = 0;
                $site->share_count = 0;
                $site->save();
                $siteid = $site->id;
            }
            $history = new WebHistory();
            $history->url = $alexaSite['url'];
            $history->alexa_rank=$alexaSite['rank'];
            $history->alexa_globalrank=$alexaSite['globalrank'];
            $history->alexa_reach=$alexaSite['reach'];
            $history->alexa_pageviewsmillion=$alexaSite['pageviewsmillion'];
            $history->alexa_pageviewsuser=$alexaSite['pageviewsuser'];
            $history->web_id = $siteid;
            $history->save();
        }
    }
}
