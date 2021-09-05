<?php

namespace app\controller;

use app\model\Data\Stat;
use app\model\Validation;

class Test extends CONTROLLER
{
    public function score()
    {
        $test = new \app\model\Data\Test('publicid', $this->params['publicid']);
        $score = $test->GetScore($this->params['answers']);

        Stat::create( [ 'score'=>$score, 'testid'=>$this->params['publicid'], 'guestname'=>$this->params['name'] ] );
        echo $score;
    }

    public function get()
    {
        foreach ( \app\model\Data\Test::read('publicid', [$this->params['publicid']]) as $item )
        {
            $item = $item->export();
            $item = Validation::GetOwnerInfo([$item]);
            $item = Validation::DeleteSystemInfo($item);

            echo json_encode($item[0]);
        }
    }
}