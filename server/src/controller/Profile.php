<?php


namespace app\controller;


use app\model\Auth\Authorization;
use app\model\Auth\UsersManager;
use app\view\View;

class Profile
{
    public static function changePass($param){
        Authorization::checkAccess([]);
        $user = $_SESSION['user'];
        if ( $user->verify($param['password']) ) {
            UsersManager::change('password', password_hash($param['newPass'], PASSWORD_DEFAULT), $user->id);
            Authorization::update();
            View::massage('massage','Пароль изменен');
        }
        else{
            View::massage('error','Неверный пароль');
        }
    }
    public static function deleteOwn()
    {
        Authorization::checkAccess([]);
        UsersManager::delete($_SESSION['user']->id);
        Auth::logout();
        View::massage('massage','Аккаунт удален');
    }
    public static function deleteById($param){
        Authorization::checkAccess(['admin']);
        UsersManager::delete($param['id']);
        View::massage('massage','Аккаунт удален');
    }
    public static function changeRole($param){
        Authorization::checkAccess(['admin']);
        UsersManager::change('role', $param['role'], $param['id']);
        View::massage('massage','Роль изменена');
    }
    public static function changeName($param){
        Authorization::checkAccess([]);
        UsersManager::change('name', $param['name'], $_SESSION['user']->id);
        Authorization::update();
        View::massage('massage','Имя изменено');
    }
}