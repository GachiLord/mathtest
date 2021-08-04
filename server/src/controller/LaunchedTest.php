<?php


namespace app\controller;


use app\model\Auth\Authorization;
use app\model\Statistic;
use RedBeanPHP\R;

class LaunchedTest
{
    public static function finishTest(array $arr){
        //checks answers of user
        $content = R::findOne( 'content', 'publicid=?', [$arr['id']] );
        $maxScore = 0;
        $score = 0;
        $userAnswer = $arr['answers'];
        $answers = json_decode($content['answers']);



        foreach ($answers as $item){
            foreach ( $item as $value ){
                if ( $value !== 'false' ) $maxScore++;
            }
        }

        foreach ( $answers as $key => $item ){
            foreach ( $item as $index => $value ){
                if ( $userAnswer[$key][$index] === $value && $value !== 'false' ) $score++;
            }
        }

        //username
        $owner = -77;
        $guestName = null;
        if ( Authorization::getUserRole() !== 'guest' ){
            $owner = $_SESSION['user']->id;
        }
        else
        {
            $guestName = $arr['name'];
        }

        Statistic::storeStats($score * 100 / $maxScore, $arr['id'], $owner, $guestName);

        echo round( $score * 100 / $maxScore );
    }
    public static function getTest(array $arr){
        $content = R::findOne( 'content', 'publicid=?', [$arr['id']] );
        $owner = 'Автор не указан';
        if ( Authorization::getContentOwner($content, 'name') !== 'guest-p4TYuqcj') $owner = Authorization::getContentOwner($content, 'name');


        echo json_encode( [ "name" => $content['name'], "text" => json_decode($content['text']), "date" => $content['date'], "creator" => $owner, "type" => $content['type']] );
    }
    public static function checkAnswer(array $arr){
        //checks answer and returns bool response
        $content = R::findOne( 'content', 'publicid=?', [$arr['id']] );

        if ( $content[$arr['pageid']][$arr['answerid']] === $arr['answer'] ) echo 'true';
        else echo 'false';
    }
}