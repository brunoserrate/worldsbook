<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use GuzzleHttp\Client;
use App\Models\Tags;


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
        $result = DB::table('comentarios')->skip(6)->take(2)->get()->toArray();

        var_dump($result);
    }


}
