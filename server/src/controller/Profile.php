<?php

namespace app\controller;

use app\model\Person\User;

class Profile extends CONTROLLER
{
    public function ChangeRole()
    {
        $user = new User( $this->params['login'] );
        $this->view->massage($user->change('role', $this->params['role']));
    }
    public function edit()
    {
        $auth = \app\model\Auth\Auth::GetAuthorization();
        $this->view->massage($auth->person->update($this->params),'Профиль изменен', 'Неверный пароль или не введены дынные');
        $auth->sync();
    }
    public function DeleteOwn()
    {
        $user = \app\model\Auth\Auth::GetPerson();
        $user->forget();
        $this->view->massage($user->delete());
    }
    public function delete()
    {
        $user = new User( $this->params['login'] );
        $this->view->massage($user->delete());
    }
}