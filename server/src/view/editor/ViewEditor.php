<?php

namespace app\view\editor;

use app\view\View;

class ViewEditor extends View
{

    protected function body(): string
    {
        return "<body>{$this->header()}
                    <div class='container' style='width: 60%; min-width: 20rem'>
                        <div class='row justify-content-md-start'>
                            <div class='col-md-auto mt-4 mb-1'>{$this->formEditPanel()}</div>
                            <div class='col mt-4 mb-1 text-center w-50'><div class='editor'>{$this->formTools()}{$this->formEditor()}</div><button class='btn btn-outline-primary add-task m-3'>Добавить задание</button></div>
                        </div>
                    </div>
                </body>";
    }

    protected function formEditPanel($title = "", $fields = ['name'=>'', 'description'=>'','time'=>''], $options = ""):string
    {
        if ($options === "") $options = "<div class='card-body border-bottom'>
                                              <div class='form-check form-switch'>
                                                  <input class='form-check-input' type='checkbox' role='switch' id='vision' checked='checked'>
                                                  <label class='form-check-label' for='vision'>Видимость во вкладе тесты</label>
                                              </div>
                                        </div>";

        return "<form class='card sticky-top'>
                    $title
                    <div class='card-body border-bottom'>
                        <input type='text' class='form-control m-1' id='name' placeholder='Название' value='{$fields['name']}' required>
                        <textarea type='text' class='form-control m-1' id='description'  placeholder='Описание' required>{$fields['description']}</textarea>
                        <input type='text' class='form-control m-1' id='time'  placeholder='Время прохождения(мин)' value='{$fields['time']}' data-bs-toggle='tooltip' title='Оставить пустым, если ограничение не нужно'>
                    </div>
                    $options
                    <div class='card-body justify-content-center d-flex'><button class='btn-primary btn m-1' id='save' reactable='false'>Сохранить</button></div>
                </form>";
    }

    protected function formEditor():string
    {
        return "";
    }

    protected function formTools():string
    {
        return "<div class='sticky-top mb-3 card'><div class='card-body justify-content-center d-flex flex-wrap p-1 editblock'>
                    <button class='btn-sm btn btn-outline-primary m-1' what='text'>Поле ввода</button>
                    <button class='btn-sm btn btn-outline-primary m-1' what='checkbox'>Флажок</button>
                    <button class='btn-sm btn btn-outline-primary m-1' what='radio'>Радиокнопка</button>
                    <button class='btn-sm btn btn-outline-primary m-1' what='img'>Картинка</button>    
                </div></div>";
    }

    protected function formTask($tasks = null):string
    {
        $html = "";
        if ($tasks === null) {
            $tasks = [ 0 => [ 'text' => $this->loadComponent('task') ] ];
        }


        foreach ($tasks as $task){
            $html.= "{$task['text']}";
        }


        return $html;
    }
}