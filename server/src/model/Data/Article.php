<?php

namespace app\model\Data;

use app\model\Auth\Auth;
use app\model\lib\ContentUtils;
use app\model\Storage\BD;


class Article extends DATA
{
    protected int|null $owner;
    protected array $text;
    protected string $name;
    protected int $PublicId;
    protected string $type;
    protected int|null $time;

    public function __construct($property, $value)
    {
        $storage = new BD('content');
        parent::__construct($property, $value, $storage);
        $this->owner = $this->table['owner'];
        $this->text = json_decode($this->table['text']);
        $this->name = $this->table['name'];
        $this->PublicId = $this->table['publicid'];
        $this->type = $this->table['type'];
        $this->time = $this->table['time'];
    }

    static public function create(array $arr): int
    {
        $bd = new BD('content');

        $arr['name'] = ContentUtils::GetTextWithoutTags($arr['name']);
        $arr['type'] = self::GetType($arr['answers']);
        $arr['answers'] = json_encode($arr['answers']);
        foreach ( $arr['text'] as &$item ) { $item = ContentUtils::GetTextWithTags($item); }
        $arr['text'] = json_encode($arr['text']);
        $arr['date'] = date('m.d.y');
        $arr['owner'] = Auth::IsLogIn() ? Auth::GetPerson()->id : null;
        $arr['publicid'] = self::GetPublicId();
        $arr['visibility'] = $arr['show'];
        $arr['description'] = !empty($arr['description']) ? ContentUtils::GetTextWithoutTags($arr['description']) : 'Описание отсутствует';
        $arr['time'] = $arr['time'] == "" ? null : $arr['time'];

        $bd->create($arr, 'name, visibility, type, answers, text, date, owner, publicid, time, description');
        return $arr['publicid'];

    }

    public static function read($property, $value):array
    {
        $read = new BD('content');
        $read = $read->FindByProperty($property, $value);

        foreach ($read as &$item) unset($item['answers']);
        return ContentUtils::GetOwnerInfo($read);
    }

    public static function ReadAcId($Min = 0, $Max = 20):array
    {
        $read = new BD('content');
        return ContentUtils::GetOwnerInfo($read->FindAcId($Min, $Max, 'where visibility = 1'));
    }

    public static function GetArticleCount():int
    {
        $bd = new BD('content');
        return $bd->GetRowCount('visibility = 1');
    }

    public function UpdatePublicid():bool|int
    {
        $auth = Auth::GetAuthorization();
        if ( $auth->IsOwn(!$auth->IsOwn($this->table) && $auth->person->role !== 'Admin') ) return false;
        $id = $this->GetPublicId();
        $this->storage->change($this->id, 'publicid', $id);
        return $id;
    }

    public function update(array $arr): bool
    {
        $auth = Auth::GetAuthorization();
        if (!$auth->IsOwn($this->table) && $auth->person->role !== 'Admin') return false;
        $arr['type'] = self::GetType($arr['answers']);
        $arr['text'] = json_encode($arr['text']);
        $arr['answers'] = json_encode($arr['answers']);
        $arr['visibility'] = $arr['show'];
        $arr['time'] = $arr['time'] == "" ? null : $arr['time'];
        $arr['description'] = ContentUtils::GetTextWithoutTags($arr['description']);
        return $this->storage->update($this->id, $arr, 'name, visibility, type, answers, text, time, description');
    }

    protected static function GetType(array $arr):string|null
    {
        $checker = 0;
        if ( !empty($arr) ){
            foreach ( $arr as $item ){
                if ( !empty($item) ) {
                    foreach ( $item as $value ){
                        if ( $value != "" ) $checker+=1;
                    }
                }
            }
        }

        return $checker === 0 ? 'article' : 'test';
    }
    protected static function GetPublicId():int
    {
        $bd = new BD('content');
        $id = rand(0,1000000);
        if ( !$bd->RowExists('publicid', $id) ) return $id;
        else return self::GetPublicId();
    }

    public static function GetByOwnerAcId(int $Min, int $Max, int|string $owner):array
    {
        $read = new BD('content');
        return ContentUtils::GetOwnerInfo($read->FindAcId($Min, $Max, "where owner = $owner"));
    }

}