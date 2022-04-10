<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

            return $this->sendResponse(['status' => 200], 'autenticado com sucesso');
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

    public function forgotPassword(Request $request){

    }

    public function resetPassword(Request $request){

    }

}
