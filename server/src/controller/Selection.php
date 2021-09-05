<?php

namespace app\controller;

use app\model\Data\Article;
use app\model\Data\Stat;
use app\model\Person\User;

class Selection extends CONTROLLER
{

    public function GetProfile()
    {
        $this->view->content('profile', User::read('login', [$this->params['login']]));
    }

    public function GetContent()
    {
        $this->view->content('content', Article::read('publicid', $this->params['publicid']));
    }

    public function GetContentById()
    {
        $this->view->content('content', Article::ReadAcId($this->params['load'], $this->params['page']));
    }

    public function GetOwnContent()
    {
        $this->view->content('content', Article::read('owner', [\app\model\Auth\Auth::GetPerson()->id] ) );
    }

    public function GetOwnStat()
    {
        $this->view->content('OwnStat', Stat::ReadOwn('owner', \app\model\Auth\Auth::GetPerson()->id));
    }

    public function GetTestStat()
    {
        $this->view->content('TestStat', Stat::ReadOwn('testid', $this->params['testid']));
    }

}