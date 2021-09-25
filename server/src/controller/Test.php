<?php

namespace app\controller;

use app\model\Data\Stat;
use app\model\Data\Timer;
use app\model\Validation;

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
            $auth = \app\model\Auth\Auth::GetAuthorization();
            if ( !empty($item['time']) ) {
                $auth->CreateTimerOrDie($this->params['publicid'], $item['time']);
                $timer = new Timer($this->params['publicid']);
                $item['time'] = $timer->TimeLeft();
            }


            $item = $item->export();
            $item = Validation::GetOwnerInfo([$item]);
            $item = Validation::DeleteSystemInfo($item);

            echo json_encode($item[0]);
        }
    }
}
