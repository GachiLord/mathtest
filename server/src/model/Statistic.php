<?php


namespace app\model;


use app\model\Auth\Authorization;
use app\view\View;
use mysql_xdevapi\Exception;
use RedBeanPHP\R;

class Statistic
{
    public static function storeStats(int $score, int $testId, int $owner, $guestName){
        $stats = R::dispense('results');
        $stats['testid'] = $testId;
        $stats['owner'] = $owner;
        $stats['score'] = strip_tags($score);
        $stats['guestname'] = strip_tags($guestName);

        R::store($stats);
    }

    public static function getStatsById($id):array
    {
        return R::find('results', 'owner=?', [$id]);
    }

    public static function getTestStats(int $publicID):array
    {
        return R::find('results', 'testid=?', [$publicID]);
    }

    public static function getAverageScore(int $id):int
    {
        $score = R::find( 'results','owner=?',  [$id]);
        $sum = 0;


        if ( $score !== [] ){
            foreach ( $score as $item ){
                $sum += $item['score'];

            }

            return round( $sum/count($score) );
        }
        else return 0;

    }
}