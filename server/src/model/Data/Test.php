<?php

namespace app\model\Data;

use app\model\Auth\Auth;
use app\model\Data\Article;
use app\model\Storage\BD;
use app\model\Validation;

class Test extends Article {

    protected array $answers;

    public function __construct($property, $value)
    {
        parent::__construct($property, $value);
        $this->answers = json_decode($this->table['answers']);
    }

    static public function ReadOwn($property, $value): array
    {
        $auth = Auth::GetAuthorization();
        $bd = new BD('content');
        $read = $bd->FindByProperty($property, $value);



        foreach ($read as $key => $item ) {
            if ( !$auth->IsOwn($item) && $auth->person->role !== 'Admin' ) unset($read[$key]);
        }

        return Validation::DeleteSystemInfo($read);
    }

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


}