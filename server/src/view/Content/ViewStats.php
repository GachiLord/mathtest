<?php

namespace app\view\Content;

use app\model\Data\Stat;
use app\model\lib\ContentUtils;

class ViewStats extends ViewContent
{
    protected int|string $publicid;
    use StatComponents;
    public function __construct($pagename = "Страница не найдена", $script = 'index.js', $static = '')
    {
        parent::__construct($pagename, $script, $static);
        $this->publicid = $this->getPublicidOrDie($this->baseurl, $this->fullUrl);
    }

    protected function body(): string
    {
        return "<body>{$this->header()}
                    <div class='w-75 mt-4 align-items-center mx-auto flex-wrap d-flex justify-content-evenly'><ul class='list-group'>{$this->formStats()}</ul></div>
                    <div class='mt-4 align-items-center mx-auto flex-wrap d-flex justify-content-evenly'>{$this->pagination()}</div>
                </body>";
    }

    protected function formStats():string
    {
        $html = "";
        $stats = ContentUtils::GetOwnerInfo(Stat::GetByPublicidAcId($this->page === 1 ? 0 : $this->page * $this->step - $this->step, $this->page === 1 ? $this->step : $this->page * $this->step, $this->publicid ));
        $this->contentCount = sizeof($stats);



        foreach ($stats as $stat){
            $html.= "<li class='list-group-item d-flex justify-content-between align-items-center p-3' style='width: 40vh'>
                        <a class='text-decoration-none' href='{$this->baseurl}/profile/{$stat['OwnerInfo']['login']}'>{$stat['OwnerInfo']['name']}</a>
                        <span class='badge rounded-pill {$this->getScoreColor($stat['score'])}'>{$stat['score']}</span>
                    </li>";
        }


        return $html;
    }

    protected function getMaxPage(): int
    {
        return ceil(sizeof(Stat::read('testid', $this->publicid)) / $this->step);
    }


}