<?php

namespace app\view\Content;

use app\model\Auth\Auth;
use app\model\Data\Article;
use app\model\lib\ContentUtils;

class ViewTests extends ViewContent
{



    public function __construct($pagename = "Страница не найдена", $script = 'index.js')
    {
        parent::__construct($pagename, $script);
    }

    protected function body():string
    {

        return "<body>{$this->header()}
                    <div class='w-75 align-items-center mx-auto flex-wrap d-flex justify-content-evenly'>{$this->formTest(Article::ReadAcId($this->page === 1 ? 0 : $this->page * $this->step - $this->step, $this->page === 1 ? $this->step : $this->page * $this->step))}</div>
                    <div class='mt-4 align-items-center mx-auto flex-wrap d-flex justify-content-evenly'>{$this->pagination()}</div>
                </body>";
    }

    protected function formTest($tests, $class = 'mt-4'):string
    {
        $this->contentCount = sizeof($tests);
        $html = "";


        foreach ($tests as &$item) {
            $description = ContentUtils::GetTextWithoutTags($item['description']);
            $options = "";
            $publicId = json_encode(["publicid" => $item['publicid']]);
            if (Auth::IsLogIn()) {
                $auth = Auth::GetAuthorization();
                if (($auth->person->role === 'Moder' or $auth->person->role === 'Admin') && !$auth->IsOwn($item)) {
                    $options .= "<button class='m-1 btn-sm btn btn-outline-secondary' reload='true' controller='Redactor' action='delete' parameters='{$publicId}'>Удалить</button>";
                }
                if ($auth->IsOwn($item)) {
                    $options .= "<a class='m-1 btn-sm btn btn-outline-success' href='{$this->baseurl}/edit/{$item['publicid']}'>Редактировать</a>
                            <a class='m-1 btn-sm btn btn-outline-dark' href='{$this->baseurl}/results/{$item['publicid']}/1'>Результаты</a>
                            <button class='m-1 btn-sm btn btn-outline-secondary' controller='Redactor' action='ClearTimers' parameters='$publicId' data-bs-placement='bottom' title='Пользователь сможет пройти тест еще раз'>Удалить запись о прохождении на время</button>
                            <button class='m-1 btn-sm btn btn-outline-danger' controller='Redactor' action='delete' parameters='$publicId' reload='true'>Удалить</button>
                            ";
                }
            }

            $html .= "<div class='card $class' style='width: 15rem;'>
                        <div class='card-body border-bottom mt-0'>
                            <h5 class='card-title'>Название: {$item['name']}</h5>
                            <h5 class='card-title'>Автор: <a class='text-decoration-none' href='{$this->baseurl}/profile/{$item['OwnerInfo']['login']}'>{$item['OwnerInfo']['name']}</a></h5>
                        </div>
                        <div class='card-body border-bottom'>
                            <p class='card-text'>{$description}</p>
                        </div>
                        <div class='d-flex justify-content-center flex-wrap p-3'>
                            {$options}
                            <a href='{$this->baseurl}/launch/{$item['publicid']}' class='btn btn-sm btn-outline-primary align-bottom m-1'>Открыть</a>
                        </div>
                    </div>";


        }
        return $html;
    }

    protected function getMaxPage():int
    {
        return ceil(sizeof(Article::read('visibility', [1])) / $this->step);
    }
}