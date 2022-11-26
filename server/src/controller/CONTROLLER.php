<?php

namespace app\controller;




use app\view\View;


abstract class CONTROLLER
{
    protected View $view;
    protected array $params;



    public function __construct()
    {
        $this->view = new View();
        $this->params = json_decode($_POST['parameters'], true);
    }
}