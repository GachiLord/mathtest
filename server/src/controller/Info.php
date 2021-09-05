<?php

namespace app\controller;

use app\model\Data\Article;
use app\model\Data\Test;

class Info extends CONTROLLER
{
    public function menu()
    {
        $this->view->header();
    }
    public function AuthState()
    {
        echo \app\model\Auth\Auth::IsLogIn() ? 'authed' : 'NotAuthed';
    }
    public function GetTestCount()
    {
        echo Article::GetArticleCount();
    }
}