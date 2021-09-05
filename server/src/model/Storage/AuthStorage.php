<?php

namespace app\model\Storage;

interface AuthStorage
{
    public function create( string|int $property, object|array $arr ):bool;

    public function read( string|int $property ):mixed;

    public function update ( string|int $property, mixed $arr ):bool;

    public function delete ( string|int $property ):bool;
}