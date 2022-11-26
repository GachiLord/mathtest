<?php

namespace app\model\Person;


use app\model\Data\DATA;
use app\model\lib\ContentUtils;
use app\model\Storage\BD;


/**
 * Class for User. It is used to auth, change pass etc.
 */
class User extends DATA {
    protected string $password;
    protected string $login;
    protected string $role;


    public function __construct($value)
    {
        $storage = new BD('users');
        $property = 'login';
        parent::__construct($property, $value, $storage);
        $this->login = $this->table['login'];
        $this->password = $this->table['password'];
        $this->role = $this->table['role'];
    }


    public function VerifyPass(string $pass):bool
    {
        return password_verify($pass, $this->password);
    }

    /**
     * Returns hash using password_hash()
     */
    static public function GetHash(string $pass):string
    {
        return password_hash($pass, PASSWORD_DEFAULT);
    }

    public static function create(array $arr): bool
    {
        $bd = new BD('users');
        if ( $bd->RowExists('login', $arr['login']) ) return false;
        if ( empty($arr['login']) or empty($arr['name']) or empty($arr['password']) ) return false;

        $arr['login'] = ContentUtils::GetTextWithoutTags($arr['login']);
        $arr['name'] = ContentUtils::GetTextWithoutTags($arr['name']);
        $arr['password'] = self::GetHash($arr['password']);
        $arr['role'] = 'User';
        return $bd->create($arr, 'name,login,password, role');
    }

    public static function read($property, $value): array
    {
        $bd = new BD('users');
        return $bd->FindByProperty($property, $value);
    }

    public function update(array $arr): bool
    {
        if ( $this->VerifyPass($arr['OldPassword']) ) {
            $selection = '';

            if ( $arr['name'] === '' && $arr['password'] === '' ) return false;
            if ( $arr['name'] === '' && $arr['password'] !== '' ) $selection .= 'password';
            if ( $arr['name'] !== '' && $arr['password'] === '' ) $selection .= 'name';
            if ( $arr['name'] !== '' && $arr['password'] !== '' ) $selection .= 'name, password';

            $arr['name'] = ContentUtils::GetTextWithoutTags($arr['name']);
            $arr['password'] = self::GetHash($arr['password']);
            return $this->storage->update($this->id, $arr, $selection);
        }
        else return false;
    }

    public static function userExists(string $login):bool
    {
        $bd = new BD('users');
        return $bd->RowExists('login', $login);
    }
}

