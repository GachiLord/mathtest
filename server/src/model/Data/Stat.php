<?php

namespace app\model\Data;

use app\model\Auth\Auth;
use app\model\lib\ContentUtils;
use app\model\Storage\BD;


/**
 * Class for all Statistic operations
 */
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

    /**
     * Use it, if it requires to get stat for authed user
     * @return array returns bean or [ ]
     */
    public static function ReadOwn(string $property, string|int $value): array
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


    /**
     * Use it to read all Stat U need
     * @param $property string only strings!
     * @param $value string|int only str and int!!!
     * @return array
     */
    static public function read($property, $value): array
    {
        $stat = new BD('results');

        return $stat->FindByProperty($property, [$value]);
    }

    static public function getAverageScoreByProperty(){
        //implement
    }

    /**
     * @param int $owner
     * @return int avr or 0
     */
    static public function GetAverageScoreForOwner(int $owner):int
    {
        $stat = new BD('results');

        return $stat->GetAverageByProperty('owner', $owner, 'score');
    }
    static public function getResultsCount(int $owner):int
    {
        $stat = new BD('results');

        return $stat->GetRowCount('owner=?', [$owner]);
    }

    /**
     * @param int $Min
     * @param int $Max
     * @param int $publicid
     * @return array
     */
    public static function GetByPublicidAcId(int $Min, int $Max, int $publicid):array
    {
        $read = new BD('results');
        return ContentUtils::GetOwnerInfo($read->FindAcId($Min, $Max, "where testid = $publicid"));
    }

    /**
     * Choose results from $Min to $Max for current owner
     * @param int $Min it must not be > $Max
     * @param int $Max it must not be < $Min
     * @param int|string $owner any owner you need
     * @return array table with owner info
     */
    public static function GetByOwnerAcId(int $Min, int $Max, int|string $owner):array
    {
        $read = new BD('results');
        return ContentUtils::GetOwnerInfo($read->FindAcId($Min, $Max, "where owner = $owner"));
    }

    public static function IsExists($testid):bool
    {
        $read = new BD('results');
        return $read->RowExists('testid', $testid);
    }
}