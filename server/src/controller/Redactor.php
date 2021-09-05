<?php

namespace app\controller;

use app\model\Data\Article;
use app\model\Data\Test;

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
}