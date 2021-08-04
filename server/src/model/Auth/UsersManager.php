<?php


namespace app\model\Auth;


use app\view\View;
use Cassandra\Cluster;
use RedBeanPHP\R as R;


class UsersManager
{
    public static function addUser(string $login, string $name, string $pass)
    {
        if ( self::checkLoginExisting($login) === false){
            $users =  R::dispense('users');
            $users['login'] = strip_tags($login);
            $users['name'] = strip_tags($name);
            $users['password'] = password_hash($pass, PASSWORD_DEFAULT);
            $users['role'] = 'user';
            R::store($users);
            View::massage('massage', 'Вы зарегистрированы');
        }
        else{
            View::massage('error', 'Логин уже существует');
        }
    }
    public static function delete($id){
        $load =  R::load('users', $id);
        R::trash($load);
    }
    public static function checkLoginExisting(string $login):bool
    {
        if ( R::count( 'users', 'login = ?', [$login] ) > 0) return true;
        else return false;
    }
    public static function change(string $property, string $value, $id){
        $load =  R::load('users', $id);
        $load->$property = $value;
        R::store($load);
    }
}

