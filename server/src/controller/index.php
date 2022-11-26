<?php
namespace app\controller;
require '../../vendor/autoload.php';

use app\view\Content\ViewStats;
use app\view\Content\ViewTests;
use app\view\editor\ViewEditor;
use app\view\launch\ViewTest;
use app\view\profile\ViewOwnStats;
use app\view\profile\ViewOwnTests;
use app\view\profile\ViewProfile;
use app\view\View;
use RedBeanPHP\R as R;
use app\view\lib\UrlUtils;


R::setup( 'mysql:host=localhost;dbname=data','user', '12345678' );



$auth = \app\model\Auth\Auth::GetAuthorization();
if (!empty($_POST['controller'])) {
    if (file_exists("{$_POST['controller']}.php") && $auth->CheckAccess($_POST['controller'], $_POST['action'])) {
        eval ("use app\\controller\\{$_POST['controller']};
               if ( method_exists ( {$_POST['controller']}::class, '{$_POST['action']}' ) ){
               \$controller = new {$_POST['controller']}();
               \$controller->{$_POST['action']}();
               }");
    }
}
else {
    $link = $_SERVER['REQUEST_URI'][-1] === '/' ? $_SERVER['REQUEST_URI']: $_SERVER['REQUEST_URI'].'/';


    if (str_contains($link, '/index/')){
        $index = new View('Главная', 'index.js', 'index');
        echo $index->html();
    }
    elseif (str_contains($link, '/login/')){
        $login = new View('Вход', 'login.js', 'login');
        echo $login->html();
    }
    elseif (str_contains($link, '/register/')){
        $register = new View('Регистрация', 'register.js', 'register');
        echo $register->html();
    }
    elseif (str_contains($link, '/show/')){
        $show = new ViewTests('Тесты');
        echo $show->html();
    }
    elseif (str_contains($link, '/contact/')){
        $contacts = new View('Контакты', 'index.js', 'contact');
        echo $contacts->html();
    }
    elseif (str_contains($link, '/profile/')){
        if (str_contains($link, '/my/')){
            $my = new ViewOwnTests('Мои тесты');
            echo $my->html();
        }
        elseif (str_contains($link, '/stat/')){
            $ownStat = new ViewOwnStats('Статистика');
            echo $ownStat->html();
        }
        else{
            $profile = new ViewProfile('Профиль', 'profile.js');
            echo $profile->html();
        }
    }
    elseif (str_contains($link, '/create/') or str_contains($link, '/edit/')){
        $editor = new ViewEditor(str_contains($link, '/edit/') ? 'Редактировать тест' : 'Создать тест', 'editor.js');
        echo $editor->html();
    }
    elseif (str_contains($link, '/results/')){
        $stats = new ViewStats('Результаты');
        echo $stats->html();
    }
    elseif (str_contains($link, '/launch/')){
        $launch = new ViewTest('Тест', 'launch.js', 'launch');
        echo $launch->html();
    }
    else {
        $view = new View();
        echo $view->html();
    }
};



R::close();