<?php


namespace app\view;



use app\model\Auth\Authorization;
use app\model\Auth\Session;
use app\model\Statistic;
use mysql_xdevapi\Exception;
use RedBeanPHP\R;

class View
{
    private static function baseurl():string
    {
        return ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    }

    public static function header(){
        $baseurl = self::baseurl();
        $header = "<div class ='menu'>
				<div class='links headerLink'>
					<a href='$baseurl'>Главная</a>
					<a href='$baseurl/show'>Тесты</a>
					<a href='$baseurl/create'>Создать тест</a>
					<a href='$baseurl/contact'>Контакты</a></div>";

        if ( Authorization::getUserRole() !== 'guest' ){
            $empty = json_encode('');
            $user = $_SESSION['user'];
            echo $header."<div class='AuthInfo'><a href='$baseurl/profile/{$user->login}'>{$user->name}</a>
            <a href='$baseurl/my'><button class='my edit'>Мои тесты</button></a>&nbsp;<button class='quit' controller='Auth' action='logout' parameters='{$empty}'>Выйти</button></div>";
        }
        else{
            echo $header."<div class='AuthInfo links'> <a href='$baseurl/login'>Войти</a><a href='$baseurl/register'>Регистрация</a></div>" ;
        }
    }

    public static function massage(string $type ,string $massage){
        switch ($type){
            case 'massage':
                echo "<div class='massage'>$massage</div>";
                break;
            case 'error':
                die( "<div class='error'>$massage</div>");
        }
    }

    public static function content(string $type, $bean){
        $content = $bean;
        $response = [];

        foreach ($content as $item){
            $id = json_encode([ "id"=> $item['id'] ]);
            switch ($type) {
                case 'content':
                    $str = "<div class='content' style='height: 225px'><div class='inner'>";
                    $login = Authorization::getContentOwner($item, 'login');
                    $name = Authorization::getContentOwner($item, 'name');
                    if ( $login === 'guest-p4TYuqcj' ){
                        $str .= "<div class='name'>Название: {$item['name']}</div><div><a href='launch/{$item['publicid']}'><button class='openBut'>Открыть</button></a></div>";
                    }
                    else{
                        $str .= "<div class='name'>Название: {$item['name']}</div><div class='owner'><a href='profile/{$login}'>Автор: {$name}</a></div><div><a href='launch/{$item['publicid']}'><button class='openBut'>Открыть</button></a></div>";
                    }

                    if ( (Authorization::getUserRole() === 'admin' || Authorization::getUserRole() === 'moderator') && !Authorization::getOwnerState($item)){
                        $str .= "<div><button class='delete' controller='Redactor' action='deleteById' parameters=' {$id} '>Удалить</button></div>";
                    }
                    if (Authorization::getOwnerState($item)) {
                        $str .= "<a href='edit/{$item['publicid']}'><button class='edit'>Редактировать</button></a><a href='results/{$item['publicid']}'><button class='edit' style='background:#3048ef; border: 1px solid #3048ef;'>Результаты</button></a><div><button class='delete' controller='Redactor' action='deleteOwn' parameters=' {$id} '>Удалить</button></div>";
                    }
                    $response[] = $str."</div></div>";
                    break;

                case 'profile':
                    Session::start();
                    $baseurl = self::baseurl();
                    $averageScore = Statistic::getAverageScore($item['id']);
                    $str = "<div class='login'>Имя: {$item['name']}</div><div>Статистика: средний балл - $averageScore</div>";


                    if( $item['id'] === $_SESSION['user']->id ){
                        $str = "<div class='login'>Имя: {$item['name']}</div><div><a href='$baseurl/statistic'>Статистика: средний балл - $averageScore</a></div><div><input class='standartinput password' type='password' placeholder='Введите старый пароль'><input class='standartinput newPass' type='password' placeholder='Введите новый пароль'></div><div><button class='changePass edit'>Изменить пароль</button></div><div><input class='standartinput' type='text' id='name' placeholder='Введите имя'></div><div><button class='changeName edit'>Изменить Имя</button></div><div><button class='delete' controller='Profile' action='deleteOwn' parameters=''>Удалить аккаунт</button></div>";
                    }
                    if ( Authorization::getUserRole() === 'admin' && $item['login'] !== $_SESSION['user']->login && $item['role'] !== 'admin' ) {
                        switch ($item['role']){
                            case 'user':
                                $str.= "<div><div><input type='radio' name='change' checked='checked' value='user'>Пользователь</div><div><input type='radio' name='change' value='moderator'>Модератор</div><div><button class='edit changeRole' user='{$item['id']}'>Изменить роль</button></div></div><button class='delete' controller='Profile' action='deleteById' parameters='{$id}'>Удалить пользователя</button>";
                                break;
                            case 'moderator':
                                $str.= "<div><div><input type='radio' name='change' value='user'>Пользователь</div><div><input type='radio' checked='checked' name='change' value='moderator'>Модератор</div><div><button class='edit changeRole' user='{$item['id']}'>Изменить роль</button></div></div><button class='delete' controller='Profile' action='deleteById' parameters='{$id}'>Удалить пользователя</button>";
                                break;
                        }
                    }

                    $response[] = $str;
                    break;

                case 'stats':
                    $baseurl = self::baseurl();
                    $str = "";
                    if ( $item['owner'] == -77 ){
                        $name = $item['guestname'];
                        $str = "<tr><th>{$name}</th><th>{$item['score']}</th></tr>";
                    }
                    else{
                        $user = R::load('users', $item['owner']);
                        $str = "<tr><th><a href='$baseurl/profile/{$user['login']}'>{$user['name']}</a></th><th>{$item['score']}</th></tr>";
                    }



                    $response[] = $str;
                    break;
                case 'ownStats':
                    $name = R::findOne( 'content', 'publicid=?', [$item['testid']] );
                    if( empty($name['name']) ) break;
                    else $name = $name['name'];


                    $str = "<tr><th><a href='launch/{$item['testid']}'>{$name}</th><th>{$item['score']}</th></tr>";

                    $response[] = $str;
                    break;
            }
        }
        echo json_encode($response);
    }
}