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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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

            $avatar = $user->foto_perfil;

            if(empty($avatar)){
                $avatar = Cache::get('user_avatar_' . $user->id);
            }

            if (empty($avatar)) {

                $name = '';
                $name =  strtolower( str_replace(' ', '',$this->removeAcento( $user->name ) ) ) ;

                $avatar = 'https://avatars.dicebear.com/api/initials/'. $name .'.svg';

                // Chave, valor, segundos
                Cache::put('user_avatar_' . $user->id, $avatar, 7200);

            }

            return $this->sendResponse([
                'email' => $user->email,
                'token' => $token,
                'nome' => $user->name,
                'apelido' => $user->apelido,
                'usar_apelido' => $user->usar_apelido,
                'avatar' => $avatar,
                'user_id' => $user->id,
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

    public function logoutWeb(Request $request) {
        Auth::guard('web')->logout();
    }

    public function preferenciaUsarApelido(){
        $user = User::where('id', Auth::user()->id)
                    ->where('ativo', 1)
                    ->first();

        $user->usar_apelido = !$user->usar_apelido;

        $user->save();

        return $this->sendSuccess('Preferência alterada com sucesso');
    }

    private function removeAcento($str)
    {

        if ( !preg_match('/[\x80-\xff]/', $str) )
            return $str;

        $chars = array(
            // Decompositions for Latin-1 Supplement
            chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
            chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
            chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
            chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
            chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
            chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
            chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
            chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
            chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
            chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
            chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
            chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
            chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
            chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
            chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
            chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
            chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
            chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
            chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
            chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
            chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
            chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
            chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
            chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
            chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
            chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
            chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
            chr(195).chr(191) => 'y',
            // Decompositions for Latin Extended-A
            chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
            chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
            chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
            chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
            chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
            chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
            chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
            chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
            chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
            chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
            chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
            chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
            chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
            chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
            chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
            chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
            chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
            chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
            chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
            chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
            chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
            chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
            chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
            chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
            chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
            chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
            chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
            chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
            chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
            chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
            chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
            chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
            chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
            chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
            chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
            chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
            chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
            chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
            chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
            chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
            chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
            chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
            chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
            chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
            chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
            chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
            chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
            chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
            chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
            chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
            chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
            chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
            chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
            chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
            chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
            chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
            chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
            chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
            chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
            chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
            chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
            chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
            chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
            chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );

        $string = $this->utf8Fix( utf8_encode( strtr($str, $chars) ) );

        return $string;

    }

    private function utf8Fix($msg){
        $accents = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");

        $utf8 = array("Ã¡","Ã ","Ã¢","Ã£","Ã¤","Ã©","Ã¨","Ãª","Ã«","Ã­","Ã¬","Ã®","Ã¯","Ã³","Ã²","Ã´","Ãµ","Ã¶","Ãº","Ã¹","Ã»","Ã¼","Ã§","Ã","Ã€","Ã‚","Ãƒ","Ã„","Ã‰","Ãˆ","ÃŠ","Ã‹","Ã","ÃŒ","ÃŽ","Ã","Ã“","Ã’","Ã”","Ã•","Ã–","Ãš","Ã™","Ã›","Ãœ","Ã‡",);

        $fix = str_replace($utf8, $accents, $msg);

        return $fix;
    }

}
