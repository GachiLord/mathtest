<?php

namespace app\model\Auth;


use app\model\Data\Timer;
use app\model\Person\Guest;
use app\model\Person\User;
use app\view\View;
use JetBrains\PhpStorm\Pure;


class Auth
{
    protected object $person;
    protected array $forbidden;
    protected View $view;
    protected Session $session;

    public function __construct($person)
    {
        $this->session = new Session();
        $this->person = $person;
        $this->view = new View();
        $this->forbidden = [
            "Guest"=>[ 'Auth' => ['logout'], 'Profile' => ['edit', 'ChangeRole', 'delete'],
                'Redactor' => ['get', 'edit', 'delete'], 'Selection' => ['GetOwnContent', 'GetOwnStat', 'GetTestStat'] ],
            "User"=>[ 'Profile' => ['ChangeRole', 'delete'] ],
            "Moder"=>[ 'Profile' => ['ChangeRole', 'delete'] ],
            "Admin"=>[]
        ];
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function CheckAccess($controller, $action):bool
    {
        if ( $controller === 'CONTROLLER' ) goto error;
        if ( empty($this->forbidden[ $this->person->role ][$controller]) ) goto access;
        foreach ($this->forbidden[ $this->person->role ][$controller] as $item){
            if ( $action === $item ) goto error;
        }
        access:return true;
        error:$this->view->massage(false, 'null','Ошибка доступа');return false;
    }

    public function remember(){
        $this->session::start();
        $this->session->create( 'user',$this->person);
    }

    public function forget(){
        $this->session::stop();
    }

    public static function IsLogIn(): bool
    {
        return !Session::isEmpty('user');
    }
    public static function GetAuthorization():Auth
    {
        $session = new Session();
        return self::IsLogIn() ? new self( new User ($session->read('user')->login) ) : new self(new Guest());
    }
    public function sync()
    {
        $session = new Session();
        $session->update('user', new User( $this->person->login ));
    }

    #[Pure] public static function GetPerson():object
    {
        $session = new Session();
        return $session->read('user');
    }

    public function IsOwn($content):bool
    {
        if ( empty($content['owner']) ) return false;
        return $this->person->id === $content['owner'];
    }
    public function CreateTimerOrDie(int $publicid, $MaxTime)
    {
            if ( !self::IsLogIn() ) die(json_encode(['NotAuthed']));
            if ( !Timer::IsBegan($publicid) ) Timer::create(['publicid'=>$publicid, 'time'=>$MaxTime]);
            else {
                $timer = new Timer($publicid);
                if ( $timer->IsLate() ) die(json_encode(['late']));
        }
    }

}


