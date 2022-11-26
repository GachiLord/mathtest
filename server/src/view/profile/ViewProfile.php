<?php

namespace app\view\profile;


use app\model\Auth\Auth;
use app\model\Data\Stat;
use app\model\Data\Test;
use app\model\Person\User;
use app\view\View;
use RedBeanPHP\OODBBean;


class ViewProfile extends View
{
    protected string $userLogin;
    protected array|OODBBean $profile;
    use ProfileComponents;


    public function __construct($pagename = "Страница не найдена", $script = 'index.js', $static = '')
    {
        parent::__construct($pagename, $script, $static);

        $this->userLogin = $this->getLoginOrDie($this->baseurl, $this->fullUrl);
        $this->profile = array_values(User::read('login', [$this->userLogin]))[0];
    }

    protected function body(): string
    {
        return "<body>{$this->header()}
                    <div class='container w-75'>
                        <div class='row justify-content-md-center'> 
                            <div class='col-md-auto mt-4 mb-1'>{$this->menu($this->baseurl, $this->userLogin)}</div>
                            <div class='col-md-auto mt-4 mb-1'>{$this->formSettings()}</div>
                            <div class='col-md-auto mt-4 mb-1'>{$this->formStatisticCard($this->profile, $this->baseurl)}</div>
                        </div>
                    </div>
                </body>";
    }

    protected function formSettings():string
    {
        $disabled = ((Auth::IsLogIn() and Auth::GetPerson()->id === $this->profile['id'])) ? "" : "disabled";
        $roleList = "<div class='card-body border-bottom d-flex justify-content-evenly flex-wrap'>
                     <div><select class='form-select m-1'>
            <option value='User'>пользователь</option>
            <option value='Moder'>модератор</option>
            </select>
            </div><div><button class='btn btn-success m-1' type='button' reactable='false' id='change' login='{$this->userLogin}'>Изменить роль</button></div></div>";
        $roleChanger = (Auth::IsLogIn() and Auth::GetPerson()->role === 'Admin') ? $roleList : "";


        return "<div class='card'>
                    <form class=''>
                    <div class='card-body border-bottom'>
                        <h3 class='card-title'>Редактирование профиля</h3>
                    </div>
                    <div class='card-body border-bottom'>
                        <div class='mb-3'>
                            <label for='login' class='form-label'>Новое имя</label>
                            <input type='login' class='form-control' id='name' $disabled>
                        </div>
                        <p class='form-text text-center'>И / ИЛИ</p>
                        <div class='mb-3'>
                            <label for='password' class='form-label'>Новый Пароль</label>
                            <input type='password' class='form-control' id='newPassword' $disabled>
                        </div>
                    </div>
                    <div class='card-body border-bottom'>
                        <div class='mb-3'>
                                <label for='password' class='form-label'>Старый пароль</label>
                                <input type='password' class='form-control' id='password' $disabled>
                            </div>
                    </div>
                    $roleChanger
                    <div class='card-body d-flex justify-content-center flex-wrap'>
                        <button class='btn btn-primary m-1' type='button' reactable='false' id='save' $disabled login='{$this->userLogin}'>Сохранить изменения</button>
                        <button class='btn btn-secondary m-1' type='button' reactable='false' id='delete' $disabled login='{$this->userLogin}'>Удалить учетную запись</button>
                    </div>
                    </form>
                </div>
                ";
    }

}