<?php


namespace app\controller;


use app\model\Auth\Authorization;
use app\view\View;

class Info
{
    public static function menu(){
        View::header();
    }
    public static function authState(){
        return Authorization::isLogIn();
    }
}