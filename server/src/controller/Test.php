<?php

namespace app\controller;

use app\model\Data\Stat;
use app\model\Data\Timer;
use app\model\lib\ContentUtils;

class Test extends CONTROLLER
{
    public function score()
    {
        $test = new \app\model\Data\Test('publicid', $this->params['publicid']);
        $score = $test->GetScore($this->params['answers']);
        $code = true;

        if ( !empty($test->time) ) {
            $timer = new Timer($this->params['publicid']);
            $code = $timer->IsLate();
        }

        Stat::create( [ 'score'=>$score, 'testid'=>$this->params['publicid'], 'guestname'=>$this->params['name'] ] );
        if ( $code ) echo $score;
        else echo json_encode(['late']);
    }

    public function get()
    {
        foreach ( \app\model\Data\Test::read('publicid', [$this->params['publicid']]) as $item )
        {
            $test = new \app\model\Data\Test('publicid', $this->params['publicid']);
            if ( !empty($item['time']) ) {
                $test->CreateTimerOrDie($this->params['publicid'], $item['time']);
                $timer = new Timer($this->params['publicid']);
                $item['time'] = $timer->GetTimeLeft();
            }


            $item = $item->export();
            $item = ContentUtils::GetOwnerInfo([$item]);
            $item = ContentUtils::DeleteSystemInfo($item);

            echo json_encode($item[0]);
        }
    }
}
