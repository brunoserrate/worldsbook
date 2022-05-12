<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use GuzzleHttp\Client;
use App\Models\Tags;
use App\Models\Historia;
use Illuminate\Support\Facades\Storage;
use File;
use App\Models\User;

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

        $historia = Historia::where('usuario_id', 1)->select('id')->get()->toArray();

        foreach ($historia as $hist) {
            echo $hist['id'] . PHP_EOL;
        }


        // $pesquisa = 1;

        // $user = User::where('users.id', $pesquisa)
        //             ->select(
        //                 'users.id as user_id',
        //                 'users.email',
        //                 'users.name',
        //                 'users.apelido',
        //                 'users.foto_perfil',
        //                 DB::RAW('COUNT(historia.id) AS qtd_historias')
        //             )
        //             ->join('historia', 'historia.usuario_id', '=', 'users.id')
        //             ->first();

        // var_dump($user->toArray());

    }


}
