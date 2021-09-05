<?php

namespace app\model;

use app\model\Storage\BD;

class Validation
{
    public static function TextWithTags(string $text):string
    {
        return strip_tags($text, '<img><br><div></div><input>');
    }
    public static function TextWithoutTags(string $text):string
    {
        return strip_tags($text);
    }
    public static function DeleteSystemInfo(array $arr):array
    {
        foreach ($arr as &$item) {
            unset($item['id'], $item['password'], $item['owner']);
        }
        return $arr;
    }
    public static function GetOwnerInfo(array $arr):array
    {
        $bd = new BD('users');

        foreach ( $arr as &$item ){
            $user = $bd->FindSingleByProperty('id', [$item['owner']]);
            if ( empty($user) || $user == null ) $item['OwnerInfo'] = [ 'name' => 'Гость' ];
            else $item['OwnerInfo'] = [ 'login' => $user['login'], 'name' => $user['name'] ];
        }

        return $arr;
    }
    public static function GetContentInfo(string $publicid, string $selection):array
    {
        $bd = new BD('content');
        $content = $bd->FindSingleByProperty('publicid', [$publicid]);
        $selection = explode(',', $selection);
        $output = [];

        foreach ($selection as $item) {
            if ( empty($content) ) {
                $output[$item] = 'Запись была удалена';
                continue;
            }
            $output[$item] = $content[$item];
        }
        return $output;
    }
}