<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use GuzzleHttp\Client;
use App\Models\Tags;
use Illuminate\Support\Facades\Storage;
use File;

class BrunoSerrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:bs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testes Bruno Serrate';

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
    public function handle() {

        $id = DB::table('capitulos')->select('id')->where('id', 1)->first()->id;

        var_dump($id);


    }


}
