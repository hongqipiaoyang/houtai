<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;


class SiteController extends Controller
{
    
    protected $path_info_str;


    public function __construct($id, $module, $config = array()) {
       parent::__construct($id, $module, $config);
       $this->_getPathInfo();
       $this->_checkLogin();
    }
    
    /* public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    } */

    
/*     public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    } */
    //判断是否登陆
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
        var_dump($bool);
        if(!\Yii::$app->user->isGuest){
//            $this->goHome();
        }
    }

    //获取当前的pathinfo内容
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
