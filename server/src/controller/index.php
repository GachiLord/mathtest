<?php
namespace app\controller;
require '../../vendor/autoload.php';
use RedBeanPHP\R as R;


R::setup( 'mysql:host=localhost;dbname=data','user', '12345678' );



$auth = \app\model\Auth\Auth::GetAuthorization();
if ( file_exists("{$_POST['controller']}.php") && $auth->CheckAccess($_POST['controller'], $_POST['action']) ) {
    eval ("use app\\controller\\{$_POST['controller']};
               if ( method_exists ( {$_POST['controller']}::class, '{$_POST['action']}' ) ){
               \$controller = new {$_POST['controller']}();
               \$controller->{$_POST['action']}();
               }");
    }
else echo 'Wrong Controller';



R::close();