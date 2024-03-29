<?php

namespace app\model\Storage;

use RedBeanPHP\R;


class BD implements BdStorage
{

    protected string $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function execute(string $request, array $params): int|array|null
    {
        return R::exec($request, $params);
    }

    public function FindByProperty(string $property, array $value): array|null
    {
        return R::find($this->table, $property.'=?', $value);
    }

    public function FindByProperties(array $properties): array|null
    {
        return R::findLike($this->table, $properties);
    }

    public function change(int $id, string $property, mixed $value): bool
    {
        try{
            $bean = R::load($this->table, $id);
            $bean[$property] = $value;
            R::store($bean);
        }
        catch (\Exception){
            return false;
        }
        return true;
    }

    public function create(array $arr, $selection): bool
    {
        try {
            $bean = R::dispense($this->table);
            $bean->import($arr, $selection);
            R::store($bean);
        }
        catch (\Exception) {
            return false;
        }
        return true;
    }

    public function update(int $id, array $arr, $selection): bool
    {
        try{
            $bean = R::load($this->table,$id);
            $selection = explode(',', $selection);
            foreach ( $selection as $item ){
                $item = trim($item);
                $bean[$item] = $arr[$item];
            }

            R::store($bean);
        }
        catch (\Exception){
            return false;
        }
        return true;
    }

    public function delete(int $id): bool
    {
        try{
            $bean = R::load($this->table, $id);
            R::trash($bean);
        }
        catch (\Exception){
            return false;
        }
        return true;
    }
    public function DeleteAll(array $ids):bool
    {
        try{
            $beans = R::loadAll($this->table, $ids);
            foreach ( $beans as $item ) R::trash($item);
        }
        catch (\Exception){
            return false;
        }
        return true;
    }

    public function RowExists(string $property, string $value): bool
    {
        return boolval(R::count($this->table, $property.='=?', [$value]));
    }

    public function FindSingleByProperty(string $property, array $value):array|object|null
    {
        return R::findOne($this->table, $property.'=?', $value);
    }

    public function FindAcId($Min, $Max, $sql = ''): array|null
    {
        return R::find( $this->table, $sql.' limit ? , ?', [$Min, $Max]);
    }

    public function GetRowCount(string $sql = null, array $arr = []): int
    {
        return R::count($this->table, $sql, $arr);
    }

    public function GetAverageByProperty(string $property, string $value, string $field):int
    {
        $beans = $this->FindByProperty($property, [$value]);
        $sum = 0;

        foreach ($beans as $bean) $sum += $bean[$field];
        return $sum / (count($beans) > 0 ? count($beans): 1);
    }
}