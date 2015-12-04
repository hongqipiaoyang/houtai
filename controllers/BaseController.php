<?php

/*
 * 基础控制器
 */
namespace app\controllers;

use Yii;
// use yii\filters\AccessControl;
use yii\web\Controller;
// use yii\filters\VerbFilter;

class BaseController extends Controller{
    
    protected $path_info_str;           //当前请求的path_info信息
    
    
    public function __construct($id, $module, $config = array()) {
       parent::__construct($id, $module, $config);
       $this->_getPathInfo();
       $this->_checkLogin();
    }
    
    
    //判断是否登录
    private function _checkLogin(){
        $path_info_arr = explode('/', $this->path_info_str);
        $con = $path_info_arr[0];
        //        var_dump($con);
        if($con != 'index' && !(Yii::$app->session->get('user_info'))){
            //            echo $con;
            //            $this->redirect(array('/login/index'));
            //            $this->runController('Login/index');
            //            $this->goHome();
        }
        $bool = Yii::$app->user->isGuest;
        //         var_dump($_SESSION);
//        var_dump($bool);
        if(!\Yii::$app->user->isGuest){
            //            $this->goHome();
        }
    }
    
//     获取当前的PATH_INFO信息
    private function _getPathInfo(){
        $data = Yii::$app->request->getPathInfo();
        $data_arr = explode('/', $data);
    
        if($data_arr[0] == ''){
            $data_arr[0] = 'index';
        }
    
        if(count($data_arr) == 1){
            array_push($data_arr, 'index');
        }
        $this->path_info_str = implode('/', $data_arr);
    
    }
}

?>