<?php

namespace app\controller;

use app\model\Auth\Auth;
use app\model\Data\Article;
use app\model\Data\Test;
use app\model\Data\Timer;

class Redactor extends CONTROLLER
{
    public function create()
    {
        echo Article::create($this->params);
    }

    public function get()
    {
        foreach ( Test::ReadOwn('publicid', [$this->params['publicid']]) as $item ) echo json_encode($item);

    }

    public function edit()
    {
        $content = new Article('publicid', $this->params['publicid']);
        $this->view->massage( $content->update($this->params) );
    }

    public function delete()
    {
        $content = new Article('publicid', $this->params['publicid']);
        $this->view->massage($content->delete());
    }

    public function ClearTimers()
    {
        $content = new Article('publicid', $this->params['publicid']);
        $auth = Auth::GetAuthorization();
        if ( $auth->IsOwn(!$auth->IsOwn($content->table) && $auth->person->role !== 'Admin') ) $this->view->massage(false );
        $this->view->massage(Timer::DeleteByPublicId($this->params['publicid']), 'Готово', 'Никто еще не прошел тест');
    }
}