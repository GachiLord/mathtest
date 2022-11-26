<?php

namespace app\model\Person;


/**
 * Class for Auth::CheckAccess() compatibility
 */
class Guest{
    protected string $role;

    public function __construct()
    {
        $this->role = 'Guest';
    }
    public function __get(string $name)
    {
        return $this->$name;
    }
}