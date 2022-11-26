<?php

namespace app\view\profile;


use app\model\Auth\Auth;
use app\model\Data\Stat;
use app\model\lib\ContentUtils;
use app\model\Person\User;
use app\view\Content\StatComponents;
use app\view\Content\ViewContent;
use RedBeanPHP\OODBBean;


class ViewOwnStats extends ViewContent
{
    protected int|string $owner;
    protected string|int $login;
    protected array|OODBBean $profile;


    use ProfileComponents;
    use StatComponents;


    public function __construct($pagename = "Страница не найдена", $script = 'index.js', $static = '')
    {
        parent::__construct($pagename, $script, $static);

        $preg = preg_match('/\/profile\/\w+/i', $this->fullUrl, $matches );
        $login = str_replace('/profile/', '', $matches[0]);
        if ( !$preg or !User::userExists($login) ) Auth::redirect("{$this->baseurl}/404");
        $this->profile = array_values(User::read('login', [$login]))[0];
        $this->owner = $this->profile->id;
        $this->login = $this->profile->login;
    }

    protected function body(): string
    {
        return "<body>{$this->header()}
                    <div class='container w-75'>
                        <div class='row justify-content-evenly'> 
                            <div class='col-md-auto mt-4 mb-1'>{$this->menu($this->baseurl, $this->login)}</div>
                            <div class='col-md-auto mt-4 mb-1'>
                                <div class='list-group'>{$this->formStats()}
                                </div>
                                <div class='mt-4 align-items-center mx-auto flex-wrap d-flex justify-content-evenly'>{$this->pagination()}</div>
                            </div>
                            <div class='col-md-auto mt-4 mb-1'>{$this->formStatisticCard($this->profile, $this->baseurl)}</div>
                        </div>
                    </div>
                </body>";


    }

    protected function formStats():string
    {
        $html = "";
        $stats = Stat::GetByOwnerAcId($this->page === 1 ? 0 : $this->page * $this->step - $this->step, $this->page === 1 ? $this->step : $this->page * $this->step, $this->owner );
        $this->contentCount = sizeof($stats);



        foreach ($stats as $stat){
            $info = ContentUtils::GetContentInfo($stat['testid'], 'name,publicid');
            $html.= "<li class='list-group-item d-flex justify-content-between align-items-center p-3'>
                        <a class='text-decoration-none' href='{$this->baseurl}/launch/{$info['publicid']}'>{$info['name']}</a>
                        <span class='badge rounded-pill ms-4 {$this->getScoreColor($stat['score'])}'>{$stat['score']}</span>
                    </li>";
        }


        return $html;
    }

    protected function getMaxPage(): int
    {
        return ceil(sizeof(Stat::read('owner', $this->owner)) / $this->step);
    }
}