<?php

namespace app\view\profile;

use app\model\Auth\Auth;
use app\model\Data\Stat;
use app\model\Data\Test;
use app\model\Person\User;
use RedBeanPHP\OODBBean;

trait ProfileComponents
{

    protected function menu($baseurl, $login):string
    {
        return "<div class='card d-flex align-items-center text-center'>
                    <div class='card-body nav flex-column nav-pills align-items-center' id='v-pills-tab' role='tablist' aria-orientation='vertical'>
                        <a href='{$baseurl}/profile/{$login}' class='nav-link' type='button' role='tab' aria-selected='false'>Профиль</a>
                        <a href='{$baseurl}/profile/{$login}/my/page/1' class='nav-link' type='button' role='tab' aria-selected='false'>Мои тесты</a>
                        <a href='{$baseurl}/profile/{$login}/stat/page/1' class='nav-link' type='button' role='tab' aria-selected='false'>Статистика</a>
                    </div>
                </div>";
    }

    protected function getLoginOrDie($baseurl, $fullUrl)
    {
        if ( !preg_match('/\/profile\/\w+/i', $fullUrl, $matches ) or !User::userExists(str_replace('/profile/', '', $matches[0])) ) Auth::redirect("{$baseurl}/404");
        return str_replace('/profile/', '', $matches[0]);
    }

    protected function formStatisticCard($profile, $baseurl):string
    {
        $avg = Stat::GetAverageScoreForOwner($profile['id']);
        $count = Stat::getResultsCount($profile['id']);
        $fullCount = Test::getTestsCount() < $count ? $count : Test::getTestsCount();
        $percent = floor($count / ($fullCount === 0 ? 1 : $fullCount) * 100);
        return "<div class='card'>
                        <div class='card-body border-bottom'><h2 class='card-title '>{$profile['name']}</h2></div>
                        <div class='card-body'>
                            Средний балл:
                            <p class='card-text'><div class='progress'>
                                <div class='progress-bar' role='progressbar' style='width: {$avg}%;' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100'>{$avg}</div></div>
                            </p>
                            Всего пройдено тестов:
                            <p class='card-text'>
                                <div class='progress'>
                                    <div class='progress-bar' role='progressbar' style='width: {$percent}%;' aria-valuenow='0' aria-valuemin='0' aria-valuemax='{$fullCount}'>{$count}</div></div>
                            
                            </p>
                            <p class='card-text text-center pb-1'><a href='{$baseurl}/profile/{$profile['login']}/stat/1' class='text-decoration-none'>Подробнее</a></p>
                        </div> 
                    </div>
                    ";
    }
}