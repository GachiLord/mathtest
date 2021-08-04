<?php


namespace app\controller;


use app\model\Auth\Authorization;
use app\model\ContentManager;
use app\view\View;
use RedBeanPHP\R as R;


class Redactor
{
    public static function create($arr){
        ContentManager::create($arr);
    }
    public static function getOwn($arr){
        Authorization::CheckOwner( R::findOne('content', 'publicid=?', [$arr['id']] ) );
        echo json_encode( ContentManager::read( $arr['id'] ) );
    }
    public static function deleteOwn($arr){
        Authorization::CheckOwner( R::load('content', $arr['id']) );
        ContentManager::delete($arr['id']);
        View::massage('massage','Пост удален');
    }
    public static function deleteById($arr){
        Authorization::CheckAccess(['admin','moderator']);
        ContentManager::delete($arr['id']);
        View::massage('massage','Пост удален');
    }
    public static function changeOwn($arr){
        Authorization::CheckOwner( R::load('content',$arr['id']) );
        ContentManager::update($arr['content'],$arr['id']);
        View::massage('massage','Пост изменен');
    }
}