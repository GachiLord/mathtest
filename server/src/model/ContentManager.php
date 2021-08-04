<?php


namespace app\model;


use app\model\Auth\Authorization;
use RedBeanPHP\R as R;
use app\view\View;

class ContentManager
{

    public static function create(array $arr){
        $content =  R::dispense('content');

        $content['name'] = strip_tags($arr['name']);
        $content['show'] = $arr['show'];
        $content['answers'] = json_encode($arr['answers']);
        $text = [];
        foreach ( $arr['text'] as $item){  $text[] = strip_tags($item, '<img><br><div></div><input>'); }
        $content['text'] = json_encode($text);
        $content['date'] = date("m.d.y");


        //type checking
        $checker = 0;
        if ( !empty($arr['answers']) ){
            foreach ( $arr['answers'] as $item ){
                if ( !empty($item) ) {
                    foreach ( $item as $value ){
                        if ( $value != "" ) $checker+=1;
                    }
                }
            }
        }

        if ( $checker === 0 ) $content['type'] = 'article';
        //role checking
        if ( Authorization::getUserRole() !== 'guest' ) $content['owner'] = $_SESSION['user']->id;
        $content['publicid'] = self::getPublicId();

        R::store($content);
        echo "http://{$_SERVER['HTTP_HOST']}/edit/{$content['publicid']}";
    }
    public static function read($publicId):array
    {
        $content =  R::findOne('content', 'publicid=?', [$publicId]);
        return [ "id"=> $content['id'],"name" => $content['name'],"text"=> $content['text'], "answers"=> $content['answers'], "show"=>$content['show'], 'publicid'=> $content['publicid'] ];
    }
    public static function update(array $arr,$id){
        $content = R::load('content',$id);

        $content['name'] = strip_tags($arr['name']);
        $content['show'] = strip_tags($arr['show']);
        $text = [];
        foreach ( $arr['text'] as $item){  $text[] = strip_tags($item, '<img><br><div></div><input>'); }
        $content['text'] = json_encode($text);
        $content['answers'] = json_encode($arr['answers']);


        //type checking
        $checker = 0;
        if ( !empty($arr['answers']) ){
            foreach ( $arr['answers'] as $item ){
                if ( !empty($item) ) {
                    foreach ( $item as $value ){
                        if ( $value != "" ) $checker+=1;
                    }
                }
            }
        }

        if ( $checker === 0 ) $content['type'] = 'article';
        else $content['type'] = 'test';
        R::store($content);
    }
    public static function delete($id){
        $content = R::load('content',$id);
        R::trash($content);
    }
    public static function getPublicId():int
    {
        $id = rand(0,1000000);
        if ( R::count('content', 'publicid =?', [$id]) == 0 ) return $id;
        else self::getPublicId();
    }
    public static function getContentByProperty($property){
        $content =  R::find('content', $property);
        View::content('content', $content);
    }
}