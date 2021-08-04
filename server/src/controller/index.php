<?php
namespace app\controller;




require '../../vendor/autoload.php';

use app\view\View;
use RedBeanPHP\R as R;


R::setup( 'mysql:host=localhost;dbname=data','user', '12345678' );
if ( !R::testConnection() )
{
        exit ('Нет соединения с базой данных');
}


    if (isset($_POST['parameters'])) $parameters = $_POST['parameters'];
    else $parameters = json_encode(array(null));



    if (file_exists("{$_POST['controller']}.php")) {
        eval ("use app\\controller\\{$_POST['controller']}; if ( method_exists ( {$_POST['controller']}::class, '{$_POST['action']}' ) ){ {$_POST['controller']}::{$_POST['action']}(json_decode('{$parameters}', true));}");
    }
    else{
        View::massage('error','Wrong Controller');
    }



R::close();
