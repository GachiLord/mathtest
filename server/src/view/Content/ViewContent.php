<?php

namespace app\view\Content;

use app\model\Auth\Auth;
use app\view\lib\UrlUtils;
use app\view\View;

abstract class ViewContent extends View
{
    protected int $page;
    protected int $maxPage;
    protected int $step = 20;
    protected int $contentCount;

    public function __construct($pagename = "Страница не найдена", $script = 'index.js', $static = '')
    {
        parent::__construct($pagename, $script, $static);


        $this->page = $this->getPageOrDie();
    }

    protected function pagination():string
    {
        //link
        $cut = UrlUtils::getCutOfUrl();
        $cut = array_splice($cut, 0, sizeof($cut) - 1);
        $link = "";
        foreach ($cut as $c) $link.= "/$c";
        $this->fullUrl = substr($link, 1, strlen($link)-1);
        //maxPage
        $this->maxPage = $this->getMaxPage();
        //previous
        $disabledForPrevious = "";
        $previousPage = $this->page - 1;
        if ($previousPage <= 0) {
            $disabledForPrevious = "disabled";
            $previous = "<a class='page-link' href='#' aria-label='Previous'>";
        }
        else $previous = "<a class='page-link' href='{$this->baseurl}{$this->fullUrl}/{$previousPage}' aria-label='Previous'>";
        //first
        $first = "";
        if ($previousPage > 0) $first = "<a class='page-link' href='{$this->baseurl}{$this->fullUrl}/1'>1</a>";
        //third and nextPage
        $nextPage = $this->page + 1;
        $disabledForNextAndThird = "";
        $third = ($this->page != $this->maxPage and $this->maxPage != 0) ? $this->maxPage : $nextPage;
        if ($this->contentCount < $this->step) $disabledForNextAndThird = "disabled";


        return "<nav aria-label='Page navigation example'>
                    <ul class='pagination'>
                        <li class='page-item $disabledForPrevious'>
                            $previous
                                <span aria-hidden='true'>&laquo;</span>
                            </a>
                        </li>
                        <li class='page-item'>$first</li>
                        <li class='page-item'><a class='page-link' href='{$this->baseurl}{$this->fullUrl}/{$this->page}'>{$this->page}</a></li>
                        <li class='page-item $disabledForNextAndThird'><a class='page-link' href='{$this->baseurl}{$this->fullUrl}/{$third}'>$third</a></li>
                        <li class='page-item $disabledForNextAndThird'>
                            <a class='page-link' href='{$this->baseurl}{$this->fullUrl}/{$nextPage}' aria-label='Next'>
                            <span aria-hidden='true'>&raquo;</span>
                            </a>
                        </li>
                        </ul>
                </nav>";
    }

    protected function getPageOrDie():int
    {
        if ( !UrlUtils::LinkHasParam() ) Auth::redirect();
        return UrlUtils::GetParamFromUrl();
    }

    protected function getMaxPage():int
    {
        return 1;
    }
}