<?php

namespace app\model\Auth;

use app\model\Storage\AuthStorage;


class Session implements SessionInterface, AuthStorage
{

    public function create(string|int $property, object|array $arr):bool
    {
        $this->start();
        $_SESSION[$property] = $arr;

        return true;
    }

    public function read(int|string $property): mixed
    {
        return $_SESSION[$property];
    }

    public function update(int|string $property, mixed $arr):bool
    {
        $_SESSION[$property] = $arr;
        return $_SESSION[$property] === $arr;
    }

    public function delete(int|string $property):bool
    {
        $_SESSION[$property] = null;
        return $_SESSION[$property] === null;
    }

    public static function start()
    {
        if(session_status() === PHP_SESSION_NONE) session_name('auth');
        if(session_status() === PHP_SESSION_NONE) session_start();

        $secureFlag = true;
        if ( $_SERVER['HTTP_HOST'] === 'mathtest' ) $secureFlag = false;
        setcookie('auth', session_id(), [ "expires"=> time() + 1440,"httponly"=>true, "samesite"=>"Lax", 'path'=>'/', "secure"=> $secureFlag ]);
    }

    public static function stop()
    {
        self::start();

        unset($_COOKIE['auth']);
        setcookie('auth', null, -1, '/');
        session_destroy();
    }

    public static function isEmpty(string $property): bool
    {
        self::start();
        if (empty($_SESSION[$property])){
            self::stop();
            return true;
        }
        else return false;

    }
}