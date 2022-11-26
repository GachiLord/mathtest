<?php

namespace app\view;

use app\model\Auth\Auth;
use app\view\lib\UrlUtils;


/**
 * Base class for Views. It generates and echo html.
 */
class View
{

    protected string $baseurl;
    protected string $pagename;
    protected string $script;
    protected string $fullUrl;
    protected string $static;


    public function __construct($pagename = "Страница не найдена", $script = 'index.js', $static = '' )
    {
        $this->baseurl = UrlUtils::GetBaseUrl();
        $this->fullUrl = UrlUtils::GetFullUrl();
        $this->pagename = $pagename;
        $this->script = $script;
        $this->static = $static;
    }

    /**
     * @return string mobile|desktop.
     */
    protected function GetDeviceType():string
    {
        return preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i', $_SERVER['HTTP_USER_AGENT']) ? 'mobile' : 'desktop';
    }

    /**
     * it returns html str using component from components dir.
     * @param $name name of file in components dir
     * @param $ext
     * @return string html from file
     */
    protected function loadComponent($name, $ext = "htm"):string
    {
        $path = dirname(__FILE__).'/components/';
        return str_replace('$link', $this->baseurl, file_get_contents($path.$name.'.'.$ext));
    }

    protected function header():string
    {
        //auth checking to show required buttons
        $actions = "";
        //$info =
        if (Auth::IsLogIn()){
            $person = Auth::GetPerson();
            $actions = "<a class='nav-link' href='{$this->baseurl}/profile/{$person->login}'><button class='btn btn-outline-success btn-sm' type='submit'>Профиль</button></a>
                        <a class='nav-link' id='quit' href='#' controller='Auth' action='logout' reload='true'><button class='btn btn-outline-primary btn-sm' reload='true' type='button' controller='Auth' action='logout'>Выйти</button></a>";
        }
        else{
            $actions = "<div class='navbar-nav'><a class='nav-link' href='{$this->baseurl}/login'><button class='btn btn-outline-primary btn-sm' type='button' '>Войти</button></a></div>
                        <div class='navbar-nav'><a class='nav-link' href='{$this->baseurl}/register'><button class='btn btn-outline-primary btn-sm' type='button'>Зарегистрироваться</button></a></div>";
        }

        //desktop and mobile ver
        if ($this->GetDeviceType() === 'desktop'){
            return "<nav class='navbar navbar-expand-lg navbar-light border-bottom'>
                    <div class='container-fluid justify-content-evenly'>
                            <div class='navbar-nav'>
                                <a class='nav-link' aria-current='page' href='{$this->baseurl}/index'>Главная</a>
                                <a class='nav-link' href='{$this->baseurl}/show/page/1'>Тесты</a>
                                <a class='nav-link' href='{$this->baseurl}/create'>Создать тест</a>
                                <a class='nav-link' href='{$this->baseurl}/contact'>Контакты</a>
                            </div>
                            <div class='navbar-nav'>
                                {$actions}
                            </div>
                    </div>
                </nav>";
        }
        else{
            return "<nav class='navbar navbar-light border-bottom'>
                        <div class='container-fluid justify-content-around'>
                            <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#collapseExample' aria-controls='collapseExample' aria-expanded='false' style='box-shadow:none'>
                                <span class='navbar-toggler-icon'></span>
                            </button>
                            <div class='navbar-nav'><a class='nav-link' aria-current='page' href='{$this->baseurl}/index'>Главная</a></div>
                            <div class='navbar-nav'><a class='nav-link' href='{$this->baseurl}/show/1'>Тесты</a></div>
                            <div class='navbar-nav'><a class='nav-link' href='{$this->baseurl}/create'>Создать тест</a></div>
                        </div>
                    </nav>
                    <div class='collapse' id='collapseExample'>
                        <nav class='navbar navbar-expand-lg navbar-light bg-light'>
                            <div class='container-fluid d-flex justify-content-around'>
                                <div class='navbar-nav'><a class='nav-link' href='{$this->baseurl}/contact'>Контакты</a></div>
                                {$actions}                                        
                            </div>
                        </nav>
                    </div>";
        }


    }

    protected function head($script):string
    {
        return "<head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>{$this->pagename}</title>
                <script src='http://mathtest/app/{$script}'></script>
                </head>";
    }

    protected function body():string
    {
        $body = "<div class='d-flex flex-column justify-content-center align-items-center' style='height: 80vh'>
                    <h1 class='display-1 my-auto'>Здесь ничего нет</h1>
                </div>";


        //load html if it seted
        if ($this->static === '') return "<body>{$this->header()}{$body}</body>";
        else return "<body>{$this->header()}{$this->loadComponent($this->static)}</body>";

    }

    /**
     * use it in "index.php" to echo page.
     * @return string ready html page.
     */
    public function html():string
    {
        return "<!DOCTYPE html><html lang='ru'>{$this->head($this->script)}{$this->body()}</html>";
    }

    /**
     * echo notify for user.
     * @param bool $type true == msg , false == error
     */
    public function massage(bool $type, string $massage = 'Готово', string $error = 'Неожиданная ошибка')
    {
        switch ($type) {
            case true:
                echo json_encode(["type" => $type, "massage" => $massage], true);
                break;
            case false:
                die(json_encode(["type" => $type, "massage" => $error], true));
        }
    }

    /**
     * @param array $cut [ http:/ ,mathtest, index ]
     * @return void
     */
    protected function setUrlFromCut(array $cut)
    {
        $this->fullUrl = $cut.join("");
    }

}
