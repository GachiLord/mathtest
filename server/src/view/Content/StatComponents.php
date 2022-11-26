<?php

namespace app\view\Content;

use app\model\Auth\Auth;
use app\model\Data\Stat;
use app\model\Person\User;

trait StatComponents
{
    protected function getPublicidOrDie($baseurl, $fullUrl)
    {
        if ( !preg_match('/\/results\/[0-9]+/i', $fullUrl, $matches ) ) Auth::redirect("{$baseurl}/404");
        return str_replace('/results/', '', $matches[0]);
    }

    protected function getScoreColor($score):string
    {
        if ($score >= 90) return 'bg-primary';
        elseif ($score >= 70) return 'bg-success';
        elseif ($score >= 40) return 'bg-warning';
        else return 'bg-danger';
    }
}