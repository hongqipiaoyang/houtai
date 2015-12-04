<?php
namespace app\controllers;
use app\controllers\BaseController;

class LoginController extends BaseController{
    //put your code here
    
    public function actionIndex(){
//        echo '登录';
        $this->layout = FALSE;
        return $this->render('Index');
    }
}
