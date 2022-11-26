<?php

namespace app\model\Auth;


use app\model\Person\Guest;
use app\model\Person\User;
use app\view\lib\UrlUtils;
use app\view\View;
use JetBrains\PhpStorm\NoReturn;
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


    /**
     * Checking of access for current controller and its methods.
     * Redirect if there is no access
     */
    public function CheckAccess($controller, $action):bool
    {
        if ( $controller === 'CONTROLLER' ) goto error;
        if ( empty($this->forbidden[ $this->person->role ][$controller]) ) goto access;
        foreach ($this->forbidden[ $this->person->role ][$controller] as $item){
            if ( $action === $item ) goto error;
        }
        access:return true;
        error:Auth::redirect(); return false;
    }

    public function remember(): void
    {
        $this->session::start();
        $this->session->create( 'user',$this->person);
    }

    public function forget(): void
    {
        $this->session::stop();
    }

    public static function IsLogIn(): bool
    {
        return !Session::isEmpty('user');
    }

    /**
     * @return Auth object or Guest object
     */
    public static function GetAuthorization():Auth
    {
        $session = new Session();
        return self::IsLogIn() ? new self( new User ($session->read('user')->login) ) : new self(new Guest());
    }

    /**
     * @return void updates session
     */
    public function sync(): void
    {
        $session = new Session();
        $session->update('user', new User( $this->person->login ));
    }

    /**
    * return User obj or null
    */
    #[Pure] public static function GetPerson():object|null
    {
        $session = new Session();
        return $session->read('user');
    }

    /**
     * @param $content mixed works with User|Test|Article beans
     * @return bool if User`s id and content`s own are equal
     */
    public function IsOwn(mixed $content):bool
    {
        if ( empty($content['owner']) ) return false;
        return $this->person->id === $content['owner'];
    }

    /**
     * Redirects and stops all operations. Use before any echo!!!
     * @param string $url only relative path required
     */
    #[NoReturn] public static function redirect(string $url = ""): void
    {
        if ($url === "") $url = UrlUtils::GetBaseUrl() . '/404';
        header("Location: {$url}");
        die();
    }

}


