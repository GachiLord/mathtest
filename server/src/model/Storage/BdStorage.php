<?php

namespace app\model\Storage;

interface BdStorage
{
    public function execute( string $request, array $params ):mixed;

    public function FindByProperty ( string $property, array $value ):array|null;

    public function FindByProperties( array $properties ):array|null;

    public function FindSingleByProperty( string $property, array $value ):array|object|null;

    public function FindAcId( $Min, $Max ):array|null;

    public function change( int $id, string $property, mixed $value ):bool;

    public function create( array $arr, string $selection ):bool;

    public function update( int $id, array $arr, $selection ):bool;

    public function delete( int $id ):bool;

    public function RowExists(string $property, string $value):bool;

    public function GetRowCount( string $sql, array $arr ):int;
}