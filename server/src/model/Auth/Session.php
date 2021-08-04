<?php


namespace app\model\Auth;


class Session
{
    public static function start(){
        if(session_status() === PHP_SESSION_NONE) session_name('auth');
        if(session_status() === PHP_SESSION_NONE) session_start();

        $secureFlag = true;
        if ( $_SERVER['HTTP_HOST'] === 'mathtest' ) $secureFlag = false;
        setcookie('auth', session_id(), [ "expires"=> time() + 1440,"httponly"=>true, "samesite"=>"Lax", 'path'=>'/', "secure"=> $secureFlag ]);

    }
    public static function stop(){
        self::start();

        unset($_COOKIE['auth']);
        setcookie('auth', null, -1, '/');
        session_destroy();
    }
}