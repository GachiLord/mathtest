<?php


namespace app\model\Auth;


use app\controller\Auth;
use http\Client\Curl\User;
use RedBeanPHP\R;

class Authorization
{
    public static function isLogIn()
    {
        if ( self::getUserRole() === 'guest' ){ die('notAuthed'); }
    }
    public static function CheckAccess(array $role){
        self::isLogIn();
        if ( !in_array( self::getUserRole(), $role ) && $role !== []) { die('Access error'); }
    }
    public static function GetAccessState(array $role):bool
    {
        if ( !in_array( self::getUserRole(), $role ) && $role !== []) return false;
        else return true;
    }
    public static function CheckOwner(object $content){
        Session::start();
        if ( !empty($_SESSION) ) {
            if ($content['owner'] !== $_SESSION['user']->id || $content['owner'] === null) die('Access error');
        }
        else die('Access error');
    }
    public static function getContentOwner(object $content, string $property):string
    {
        if ( $content['owner'] !== null ) return R::load('users', $content['owner'])[$property];
        else return 'guest-p4TYuqcj';
    }
    public static function getUserRole():string
    {
        Session::start();
        if ( empty($_SESSION) ){
            Session::stop();
            return 'guest';
            }
        else if ( $_SESSION['user']->getTrustedIp() !== $_SESSION['user']::getUserIp() ){
            Session::stop();
            return 'guest';
        }
        else{ return $_SESSION['user']->role; }
    }
    public static function getOwnerState(object $content):bool
    {
        Session::start();
        if ( !empty($_SESSION) ){
            if ( $content['owner'] === $_SESSION['user']->id && $content['owner'] !== null ) return true;
            else return false;
        }else return false;
    }
    public static function update(){
        Session::start();
        $_SESSION['user'] = new \app\model\Auth\User($_SESSION['user']->login);
    }
}