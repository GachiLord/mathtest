<?php

namespace app\model\Data;

use app\model\Auth\Auth;
use app\model\Storage\BD;

class Timer extends DATA
{

    protected string $timerid;
    protected int $owner;
    protected int $testid;
    protected int|null $MaxTime;

    public function __construct($testid)
    {
        $storage = new BD('timer');
        $property = 'timerid';
        $this->timerid = (string)Auth::GetPerson()->id . (string)$testid;
        $this->testid = $testid;
        $this->owner = Auth::GetPerson()->id;
        parent::__construct($property, $this->timerid, $storage);


        $this->MaxTime = $this->table['end'];
    }

    static public function create(array $arr):bool
    {
        $bd = new BD('timer');
        $UserId = Auth::GetPerson()->id;
        $TestId = $arr['publicid'];
        $arr['owner'] = $UserId;
        $arr['timerid'] = "{$UserId}{$TestId}";
        $arr['end'] = time() + $arr['time'] * 60;
        $arr['testid'] = $arr['publicid'];
        return $bd->create( $arr, 'begin, timerid, end, testid, owner' );
    }

    static public function read($property, $value): array
    {
        $bd = new BD('timer');
        return $bd->FindByProperty($property, $value);
    }

    public function update(array $arr = []): bool
    {
        return $this->storage->update($this->id, $arr, 'begin, end, timerid');
    }
    public function IsLate():bool
    {
        return time() > $this->MaxTime + 20;
    }
    public static function IsBegan($publicid):bool
    {
        $UserId = Auth::GetPerson()->id;
        $TestId = $publicid;

        $bd = new BD('timer');
        return $bd->RowExists('timerid', "{$UserId}{$TestId}");
    }
    public static function DeleteByPublicId($publicid):bool
    {
        $bd = new BD('timer');
        return $bd->execute( 'DELETE FROM timer WHERE testid =?', [$publicid] );
    }
    public function TimeLeft():int
    {
        return $this->MaxTime - time();
    }
}