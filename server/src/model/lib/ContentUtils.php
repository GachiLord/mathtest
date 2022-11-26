<?php

namespace app\model\lib;

use app\model\Storage\BD;


class ContentUtils
{
    /**
     * it returns html without forbidden tags.
     * @param string $text html
     */
    public static function GetTextWithTags(string $text):string
    {
        return strip_tags($text, '<img><br><div></div><input>');
    }

    /**
     it deletes all html from text
     */
    public static function GetTextWithoutTags(string $text):string
    {
        return strip_tags($text);
    }

    /**
     * it deletes id,password,owner fields from array
     * @param array $arr bean|table
     */
    public static function DeleteSystemInfo(array $arr):array
    {
        foreach ($arr as &$item) {
            unset($item['id'], $item['password'], $item['owner']);
        }
        return $arr;
    }

    /**
     * @param array $arr bean|table
     * @return array ['OwnerInfo'] = [ 'name' => name, 'login' => login ]
     */
    public static function GetOwnerInfo(array $arr):array
    {
        $bd = new BD('users');

        foreach ( $arr as &$item ){
            $user = $bd->FindSingleByProperty('id', [$item['owner']]);
            if ( empty($user) || $user == null ) $item['OwnerInfo'] = [ 'name' => $item['guestname'], 'login' => '' ];
            else $item['OwnerInfo'] = [ 'login' => $user['login'], 'name' => $user['name'] ];
        }

        return $arr;
    }

    /**
     * returns arrays with fields from $selection like "id,name"
     */
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