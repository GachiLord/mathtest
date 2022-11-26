<?php

namespace app\view\profile;

use app\model\Data\Article;
use app\model\Person\User;
use app\view\lib\UrlUtils;
use RedBeanPHP\OODBBean;

class ViewOwnTests extends \app\view\Content\ViewTests
{
    use ProfileComponents;

    protected string $login;
    protected array|OODBBean $profile;

    protected function body(): string
    {
        $login = $this->getLoginOrDie($this->baseurl,UrlUtils::GetFullUrl());
        $this->profile = array_values(User::read('login', [$login]))[0];
        $this->login = $login;
        $owner = $this->profile['id'];

        return "<body>{$this->header()}
                    <div class='container w-75'>
                        <div class='row justify-content-md-center'> 
                            <div class='col-md-auto mt-4 mb-1'>{$this->menu($this->baseurl, $this->login)}</div>
                            <div class='col mt-4 mb-1 w-75'>
                                <div class='align-items-center mx-auto flex-wrap d-flex justify-content-evenly'>{$this->formTest(Article::GetByOwnerAcId($this->page === 1 ? 0 : $this->page * $this->step - $this->step, $this->page === 1 ? $this->step : $this->page * $this->step, $owner), 'mb-4')}
                                </div>
                                <div class='mt-4 align-items-center mx-auto flex-wrap d-flex justify-content-evenly'>{$this->pagination()}</div>
                            </div>
                        </div>
                    </div>
                </body>";
    }
}