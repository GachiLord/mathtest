<?php

namespace app\view;

use app\model\Auth\Auth;
use app\model\Data\Stat;
use app\model\Validation;

class View
{

    protected string $baseurl;

    public function __construct()
    {
        $this->baseurl = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    }

    protected function GetDeviceType():string
    {
        return preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i', $_SERVER['HTTP_USER_AGENT']) ? 'mobile' : 'desktop';
    }

    public function header()
    {
        $CreateButton = $this->GetDeviceType() === 'desktop' ? "<a href='$this->baseurl/create'>Создать тест</a>" : "";
        $header = "<div class ='menu'>
				<div class='links headerLink'>
					<a href='$this->baseurl'>Главная</a>
					<a href='$this->baseurl/show?page=20'>Тесты</a>
					$CreateButton
					<a href='$this->baseurl/contact'>Контакты</a></div>";

        if ( Auth::IsLogIn() ) {
            $user = Auth::GetPerson();
            $header.="<div class='AuthInfo'><a href='$this->baseurl/profile/{$user->login}'>{$user->name}</a>
            <a href='$this->baseurl/my'><button class='my edit'>Мои тесты</button></a>
            &nbsp;<button class='quit' controller='Auth' action='logout'>Выйти</button></div>";
        }
        else{
            $header.="<div class='AuthInfo links'> <a href='$this->baseurl/login'>Войти</a><a href='$this->baseurl/register'>Регистрация</a></div>";
        }

        echo "<div style='max-width: 1000px;margin: auto;'>". $header. "</div>";
    }

    public function massage(bool $type, string $massage = 'Готово', string $error = 'Неожиданная ошибка')
    {
        switch ($type) {
            case true:
                echo "<div class='massage'>$massage</div>";
                break;
            case false:
                die("<div class='error'>$error</div>");
        }
    }

    public function content(string $type, $content)
    {
        $response = [];
        foreach ($content as $item){

            switch ($type){
                case 'profile':
                    //stat of user
                    $AverageScore = 0;
                    $stats = Stat::read('owner', $item['id']);
                    if ( !empty($stats) ) {
                        foreach ( $stats as $value ) { $AverageScore += $value['score']; }
                        $AverageScore = floor($AverageScore/count($stats));
                    }

                    //output
                    $str = "<div class='login'>Имя: {$item['name']}</div><div>Статистика: средний балл - $AverageScore</div>";

                    if ( Auth::IsLogIn() ) {
                        if ( Auth::GetPerson()->id === $item['id'] ){
                            $str = "<div class='login'>Имя: {$item['name']}</div><div><a href='$this->baseurl/statistic'>Статистика: средний балл - $AverageScore</a></div>
                            <input class='standartinput newPass' type='password' placeholder='Новый пароль' autocomplete=''></div>
                            <input class='standartinput' type='text' id='name' placeholder='Новое имя'></div><div>
                            <div><input class='standartinput' type='password' id='OldPassword' placeholder='Старый пароль' autocomplete='on'></div>
                            <div><button class='change edit' login='{$item['login']}'>Изменить аккаунт</button></div>
                            <div><button class='delete' controller='Profile' action='DeleteOwn'>Удалить аккаунт</button></div>";
                        }
                        else if ( Auth::GetPerson()->role === 'Admin' ){
                            $params = json_encode([ 'login' => $item['login'] ]);


                            $IsUser = $item['role'] === 'User' ? "checked='checked'" : '';
                            $IsModer = $item['role'] === 'Moder' ? "checked='checked'" : '';
                            $str .= "<div><div><input type='radio' name='change' {$IsUser} value='User'>Пользователь</div>
                            <div><input type='radio' name='change' {$IsModer} value='Moder'>Модератор</div>";


                            $str .= "<div><button class='edit changeRole' user='{$item['login']}'>Изменить роль</button></div>
                            <div><button class='delete' controller='Profile' action='delete' parameters='{$params}'>Удалить пользователя</button></div>";
                        }
                    }

                    $response[] = $str;
                    break;
                case 'content':
                    $str = "<div class='content' style='height: 225px'><div class='inner'>";
                    if ( empty($item['OwnerInfo']['login']) ){
                        $str .= "<div class='name'>Название: {$item['name']}</div><div><a href='launch/{$item['publicid']}'><button class='openBut'>Открыть</button></a></div>";
                    }
                    else{
                        $str .= "<div class='name'>Название: {$item['name']}</div><div class='owner'><a href='profile/{$item['OwnerInfo']['login']}'>Автор: {$item['OwnerInfo']['name']}</a></div>
                        <div><a href='launch/{$item['publicid']}'><button class='openBut'>Открыть</button></a></div>";
                    }

                    if ( Auth::IsLogIn() ){
                        $publicid = json_encode( ['publicid' => $item['publicid'] ]);
                        $auth = Auth::GetAuthorization();
                        if ( ($auth->person->role === 'Moder') && !$auth->IsOwn($item) ){
                            $str .= "<div><button class='delete' controller='Redactor' action='delete' parameters='{$publicid}'>Удалить</button></div>";
                        }
                        else if ($auth->IsOwn($item) || $auth->person->role === 'Admin'){
                            $str .= "<a href='edit/{$item['publicid']}'><button class='edit'>Редактировать</button></a><a href='results/{$item['publicid']}'>
                            <button class='edit' style='background:#3048ef; border: 1px solid #3048ef;'>Результаты</button></a>
                            <div><button class='delete' controller='Redactor' action='delete' parameters='{$publicid}'>Удалить</button></div>";
                        }
                    }

                    $response[] = $str."</div></div>";
                    break;
                case 'TestStat':
                    $str = "";
                    if ( empty($item['owner']) ){
                        $name = $item['guestname'];
                        $str = "<tr><th>{$name}</th><th>{$item['score']}</th></tr>";
                    }
                    else{
                        $user = Validation::GetOwnerInfo([$item]);
                        $user = $user[0]['OwnerInfo'];
                        $str = "<tr><th><a href='$this->baseurl/profile/{$user['login']}'>{$user['name']}</a></th><th>{$item['score']}</th></tr>";
                    }
                    $response[] = $str;
                    break;
                case 'OwnStat':
                    $name = Validation::GetContentInfo($item['testid'], 'name')['name'];
                    if ( $name === false ) break;
                    $str = "<tr><th><a href='launch/{$item['testid']}'>{$name}</th><th>{$item['score']}</th></tr>";

                    $response[] = $str;
                    break;
            }

        }
        echo json_encode($response);
    }
}