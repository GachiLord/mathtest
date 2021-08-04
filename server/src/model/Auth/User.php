<?php


namespace app\model\Auth;


use app\controller\Auth;
use app\model\Auth\UsersManager;
use RedBeanPHP\R as R;



class User
{
    private string $role;
    private string $login;
    private string $id;
    protected string $ip;
    protected string $password;
    private string $name;


    public function __construct($login){
        if ( UsersManager::checkLoginExisting($login) === true ) {
            $user = R::findOne('users', 'login = ?', [$login]);

            $this->ip = self::getUserIp();
            $this->role = $user['role'];
            $this->login = $user['login'];
            $this->password = $user['password'];
            $this->id = $user['id'];
            $this->name = $user['name'];
        }
        else{
            return 'wrongData';
        }

    }
    public function __get($property){
        return $this->$property;
    }

    public function verify($pass):bool
    {
        return password_verify($pass,$this->password);
    }

    public function getTrustedIp():string
    {
        return $this->ip;
    }

    public static function getUserIp():string
    {
        $list = array();
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $list = array_merge($list, $ip);
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $list = array_merge($list, $ip);
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $list[] = $_SERVER['REMOTE_ADDR'];
        }

        $list = array_unique($list);
        return implode(',', $list);
    }

}