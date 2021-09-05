<?php

namespace app\model\Data;

use app\model\Auth\Auth;
use app\model\Person\User;
use app\model\Storage\BD;

class Stat extends DATA
{
    protected int $owner;

    public function __construct($property, $value, $storage)
    {
        $this->storage = new BD('results');
        parent::__construct($property, $value, $storage);
    }

    public static function create(array $arr): bool
    {
        $bd = new BD('results');
        $arr['owner'] = Auth::IsLogIn() ? Auth::GetPerson()->id : null;

        return $bd->create($arr, 'testid, owner, score, guestname');
    }

    public static function ReadOwn( string $property, string|int $value): array
    {
        $auth = Auth::GetAuthorization();
        $stat = new BD('results');
        $test = new BD('content');


        $stat = $stat->FindByProperty($property, [$value]);
        $test = $test->FindSingleByProperty('publicid', [$value]);
        if ( $auth->IsOwn($test) || $auth->IsOwn($stat[array_key_last($stat)]) ) return $stat;
        else return [];
    }

    public function update(array $arr): bool
    {
        $auth = Auth::GetAuthorization();

        if ( $auth->IsOwn($this->table) ) return $this->storage->update($this->id, $arr, 'owner, score, guestname');
        else return false;
    }


    static public function read($property, $value): array
    {
        $stat = new BD('results');

        return $stat->FindByProperty($property, [$value]);
    }
}