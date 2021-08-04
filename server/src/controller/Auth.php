<?php

namespace app\controller;


use app\model\Auth\Authorization;
use app\model\Auth\Session;
use app\model\Auth\User;
use app\model\Auth\UsersManager;
use app\view\View;


class Auth {
    public static function login($param){
        if ( UsersManager::checkLoginExisting($param['login']) === true){
            $user = new User($param['login']);
        }
        else{
            View::massage('error','Неверный логин или пароль');
        }


        if ( $user->verify($param['password']) )
        {
            Session::start();
            $_SESSION['user'] = $user;

            View::massage('massage','Добро пожаловать');
        }
        else{
            View::massage('error','Неверный логин или пароль');
        }

    }
    public static function logout(){
        Session::stop();

        View::massage('massage','До свидания');
    }
    public static function register($param){
        UsersManager::addUser($param['login'], $param['name'], $param['password']);
        Session::start();
        $_SESSION['user'] = new User($param['login']);
    }
}

