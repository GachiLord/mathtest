<?php

namespace app\model\Auth;

interface SessionInterface
{
    public static function start();

    public static function stop();

    public static function isEmpty(string $property):bool;
}