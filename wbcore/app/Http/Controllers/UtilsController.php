<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use DB;

class UtilsController extends AppBaseController {


    static public function uploads(Request $request){

        $file = $request->file('file');

        if(empty($file)){
            return [
                'success' => false,
                'code' => 500,
                'message' => 'Falha ao carregar a imagem! Tente novamente ou entre em contato com nosso suporte',
                'data' => []
            ];
        }

        $originalName = strtr( $file->getClientOriginalName(), [
            '.png' => '',
            '.jpeg' => '',
            '.jpg' => ''
        ] );

        $filename = $originalName . uniqid('_img_') . '.' . $file->getClientOriginalExtension();
        $path = storage_path('temp');
        $file->move($path, $filename);

        return [
            'success' => true,
            'code' => 200,
            'message' => 'Upload realizado com sucesso',
            'data' => [
                'path' => $path,
                'path_file' => $path . DIRECTORY_SEPARATOR . $filename,
                'file_name' => $filename
            ]
        ];
    }
}
