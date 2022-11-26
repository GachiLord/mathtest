<?php

namespace app\model\Data;


use app\model\Auth\Auth;
use app\model\Person\User;
use app\model\Storage\BdStorage;
use app\view\View;


/**
 * Base class for all data types
 */
abstract class DATA
{
    /**
     * @var int|mixed DB id
     */
    protected int $id;
    /**
     * @var array|object|null DB table
     */
    protected array|object $table;
    /**
     * @var BdStorage DB util is used for sql requests etc.
     */
    protected BdStorage $storage;

    public function __construct($property, $value, $storage )
    {
        $this->storage = $storage;
        try{
            $this->table = $this->storage->FindSingleByProperty($property, [$value]);
        }
        catch (\TypeError){
            Auth::redirect();
        }
        $this->id = $this->table['id'];
    }
    public function __get(string $name)
    {
        return $this->table[$name];
    }

    public function delete():bool
    {
        $auth = Auth::GetAuthorization();
        if ( empty($this->table['owner']) && ($auth->IsOwn($this->table) || $auth->person->role === 'Moder' || $auth->person->role === 'Admin') ) return $this->storage->delete($this->id);
        $owner = User::read('id', [$this->table['owner']]);
        $owner = $owner[array_key_first($owner)];


        if ( ($auth->IsOwn($this->table) || $auth->person->role === 'Moder' || $auth->person->role === 'Admin') && ($owner->role === $auth->person->role || $auth->IsOwn($this->table)) ) return $this->storage->delete($this->id);
        else return false;
    }

    /**
     * change property by value
     */
    public function change(string $property, mixed $value):bool
    {
        return $this->storage->change($this->id, $property, $value);
    }

    abstract static public function create(array $arr);

    abstract static public function read($property, $value):array;

    /**
     * update DB table by array like [ property => value ]
     */
    abstract public function update(array $arr):bool;

}