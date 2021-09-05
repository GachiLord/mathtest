<?php

namespace app\controller;


use app\model\Auth\Auth as Authorization;
use app\model\Person\User;



class Auth extends CONTROLLER
{
    protected Authorization $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = Authorization::GetAuthorization();
    }

    public function login(){
        //verify pass & login & auth
        $user = new User($this->params['login']);
        if ( $user->VerifyPass($this->params['password']) ) {
            $auth = new Authorization($user);
            $auth->remember();
            $this->view->massage(true, 'Добро пожаловать');
        }
        else $this->view->massage(false, '', 'Неверный логин или пароль');
    }

    public function logout(){
        $this->service->forget();
    }

    public function register(){
        if ( User::create($this->params) ) $this->login();
        else $this->view->massage(false, '', 'Логин уже существует');
    }
}