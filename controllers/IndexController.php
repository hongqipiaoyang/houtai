<?php
namespace app\controllers;

 use Yii;
use yii\web\Controller;
use app\controllers\BaseController;

/**
* 测试默认首页修改影响
*/
class IndexController extends BaseController
{
    
    
   

    public function actionIndex(){
               $data = Yii::$app->request->getPathInfo();
               $info['data'] = $data;
		       return $this->render('index',$info);
//                 var_dump($this->path_info_str);
                
    }
        
    
}