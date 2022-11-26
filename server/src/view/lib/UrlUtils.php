<?php

namespace app\view\lib;

class UrlUtils
{
    static public function GetBaseUrl():string
    {
        return ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    }

    static public function GetFullUrl():string
    {
        return $_SERVER['REQUEST_URI'];
    }

    static public function GetParamFromUrl($param = "page"):int
    {
        preg_match("/$param\/[0-9]+/i", UrlUtils::GetFullUrl(), $m);
        preg_match('/[0-9]/i', end($m), $output);
        return end($output);
    }

    static public function LinkHasParam($param = "page"):bool
    {
        return preg_match("/$param\/[0-9]+/i", UrlUtils::GetFullUrl());
    }

    static public function getCutOfUrl():array
    {
        return preg_split('/\//i', UrlUtils::GetFullUrl());
    }
}