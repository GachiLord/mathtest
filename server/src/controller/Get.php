<?php


namespace app\controller;
use app\model\Auth\Authorization;
use app\model\Auth\Session;
use app\model\Statistic;
use app\view\View;
use RedBeanPHP\R;

class Get
{
    public static function getContentByName(array $arr){
        $content = R::find('content', 'name =?', [$arr['name']]);
        View::content('content', $content);
    }
    public static function getContentById(array $arr){
        $content = R::find('content', [$arr['id']]);
        View::content('content', $content);
    }
    public static function getProfileByLogin(array $arr){

        $content = R::find('users', 'login=?', [quoted_printable_decode( str_replace('%', '=', $arr['login']) )] );

        if ( empty($content) ) {
            echo json_encode(['Пользователь не найден']);
        }
        else{
            View::content('profile', $content);
        }


    }
    public static function getContentAcPublicId(array $arr){
        $content = R::find( 'content', 'id < ?', $arr['id']);
        $changed = array();

        if ( Authorization::GetAccessState(['admin', 'moderator']) === false ){
            foreach ($content as $item){
                if ( $item['show'] == 1 ) $changed[] = $item;
            }
        }
        else $changed = $content;
        View::content('content', $changed);
    }
    public static function getOwnContent($arr){
        Session::start();
        Authorization::isLogIn();
        $content = R::find('content', 'owner=?', [$_SESSION['user']->id] );
        View::content('content', $content);
    }

    public static function getOwnStats($arr){
        Session::start();
        Authorization::isLogIn();

        View::content('ownStats', Statistic::getStatsById($_SESSION['user']->id));
    }

    public static function getTestStats(array $arr){
        Authorization::CheckOwner(R::findOne('content', 'publicid=?', [$arr['id']]));
        View::content('stats', Statistic::getTestStats($arr['id']) );
    }
}