<?php


namespace App\API;


class APIError
{
    public static function MensagemErro($message, $code){
        return [
            'data' => [
                'msg' => $message,
                'code' => $code
            ]
        ];
    }


}