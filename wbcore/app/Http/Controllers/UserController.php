<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use App\Notifications\RedefinirSenhaNotification;
use Carbon\Carbon;
use DB;

class UserController extends AppBaseController
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Gera uma string randomica
            $token = openssl_random_pseudo_bytes(16);

            // Converte o dado para sua repreentação hexadecimal
            $token = bin2hex($token);

            $user = User::where('email', $request->email)
                        ->where('ativo', 1)
                        ->first();

            return $this->sendResponse([
                'email' => $user->email,
                'token' => $token,
                'nome' => $user->name,
                'apelido' => $user->apelido,
            ], 'autenticado com sucesso');
        }

        return $this->sendError('Falha na autenticação, verique as credênciais', 400);
    }

    public function register(Request $request){

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'apelido' => ['required'],
            'data_nascimento' => ['required'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'apelido' => $request->apelido,
            'data_nascimento' => $request->data_nascimento,
            'ativo' => 1,
        ]);

        return $this->sendSuccess('Usuário cadastrado com sucesso!');

    }

    /**
        Show a "Request reset password form" to the user. ok
        Validate the provided email by the user. ok
        Generate a random reset password token and store it at DB (Need a table with at least two fields: email and token). OK
        Send that token to the user(It's better if you send it as a URL parameter in the reset password link). ok
        When the user navigated to the reset password page, ask for email again and validate the token by checking your DB table and matching the email and token.
        Reset the password to whatever the user wants at this point.
    */

    public function forgotPassword(Request $request){

        $input = $request->all();

        $validator = Validator::make($input,[
            'email' => ['required', 'email', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Falha na operação! Verique se as informações enviadas estão corretas', 400);
        }

        $dataToken = null;

        // Gera uma string randomica
        $token = openssl_random_pseudo_bytes(16);

        // Converte o dado para sua repreentação hexadecimal
        $token = bin2hex($token);

        $userToken = DB::table('password_resets')->select('*')->where('email', $input['email'])->first();

        if(!empty($userToken->token)){

            // Add 30 minutos da data/hora que o token foi criado
            // Ex: token criado às 18h30
            //      DataToken = 19h
            $dataToken = Carbon::parse($userToken->created_at)->addMinutes(30);

            // DataToken Menor que a data_hora atual
            // Caso verdadeiro, token está expirado, criar um novo token
            if($dataToken->lessThan( Carbon::now() )){
                $userToken->token = Hash::make($token);
                $userToken->created_at = Carbon::now()->format('Y-m-d H:i:s');

                DB::table('password_resets')->where('email', $userToken->email)
                ->update([
                    'created_at' => $userToken->created_at,
                    'token' => $token,
                ]);
            }
            // Caso falso utiliza o mesmo token da base
            else {
                $token = $userToken->token;
            }
        }
        // Token não existe
        else {
            DB::table('password_resets')->insert([
                'email' => $input['email'],
                'token' => Hash::make($token),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }


        $user = User::where('email', $input['email'])->first();

        $user->notify( new RedefinirSenhaNotification($token) );

    }

    public function resetPassword(Request $request){
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed'],
        ]);

        $userToken = DB::table('password_resets')
                        ->select('*')
                        ->where('email', $request->email)
                        ->where('token', $request->token)
                        ->first();

        if(empty($userToken->token)){
            return $this->sendError('Falha na operação! Favor refazer o processo de redefinição de senha ou contactar o nosso suporte.', 400);
        }

        $dataToken = Carbon::parse($userToken->created_at)->addMinutes(30);

        // Data Token é menor que a data atual (token expirado)
        if($dataToken->lessThan( Carbon::now() ) ){
            return $this->sendError('Token expirado. Favor refazer o processo de redefinição de senha.', 400);
        }

        $user = User::where('email', $request->email)
        ->update([
            'password' => Hash::make($request->password)
        ]);

        return $this->sendSuccess('Senha alterada com sucesso');
    }

}
