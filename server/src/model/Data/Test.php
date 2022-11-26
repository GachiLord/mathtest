<?php

namespace app\model\Data;

use app\model\Auth\Auth;
use app\model\lib\ContentUtils;
use app\model\Storage\BD;

/**
 * Class for Test table
 */
class Test extends Article {

    protected array $answers;

    public function __construct($property, $value)
    {
        parent::__construct($property, $value);
        $this->answers = json_decode($this->table['answers']);
    }

    /**
     * Read own test for authed user. Don`t use it for tests selection
     * @param $property string only strings
     * @param $value mixed only arrays like [value]
     * @return array array without SystemInfo
     */
    static public function ReadOwn(string $property, mixed $value): array
    {
        $auth = Auth::GetAuthorization();
        $bd = new BD('content');
        $read = $bd->FindByProperty($property, $value);


        foreach ($read as $key => $item ) {
            if ( !$auth->IsOwn($item) && $auth->person->role !== 'Admin' ) unset($read[$key]);
        }

        return ContentUtils::DeleteSystemInfo($read);
    }

    /**
     * Returns score using answers
     */
    public function GetScore($answers):int
    {
        $maxScore = 0;
        $score = 0;
        $UserAnswers = $answers;
        $answers = $this->answers;
        $ignore = ['false'];

        //gets max score
        foreach ($answers as $item){
            foreach ( $item as $value ){
                if ( !in_array( $value, $ignore ) ) $maxScore++;

            }
        }

        //gets user score
        foreach ( $answers as $key => $item ){
            foreach ( $item as $index => $value ){
                if ( $UserAnswers[$key][$index] === $value && !in_array( $value, $ignore ) ) $score++;
            }
        }

        return round( $score * 100 / $maxScore );
    }

    public static function getTestsCount():int
    {
        $stat = new BD('content');

        return $stat->GetRowCount('type=?', ['test']);
    }

    /**
     * Creates timer for temporary test, if user logIn
     */
    public function CreateTimerOrDie(int $publicid, $MaxTime): void
    {
        if ( !Auth::IsLogIn() ) die(json_encode(['NotAuthed']));
        if ( !Timer::IsBegan($publicid) ) Timer::create(['publicid'=>$publicid, 'time'=>$MaxTime]);
        else {
            $timer = new Timer($publicid);
            if ( $timer->IsLate() ) die(json_encode(['late']));
        }
    }


}