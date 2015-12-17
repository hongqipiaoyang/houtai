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
    //    $this->_getPathInfo();
    //    $this->_checkLogin();
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

    //分页
    protected function _checkPage($totalCount){
        $request = Yii::$app->request;
        $page = $request->get('page') ? intval($request->get('page')):1;
        $defaultPageSize = 10;
        // $defaultPageSize = $request->get('rows') ? intval($request->get('rows')):10;
        $totalPage = ceil($totalCount/$defaultPageSize);
        $limit = $defaultPageSize;
        $offect = ($page-1)*$defaultPageSize;
        return ['totalPage'=>$totalPage,'limit'=>$limit,'offset'=>$offect];
    }

    //获取提交的数据
    protected function _checkAjaxdate($field,$type){
        $request = Yii::$app->request;
        if(!is_array($field)) return ;
        foreach($field as $k=>$v){

            $data[$v] =  $request->$type((is_int($k) ? $v : $k));

        }
        if(isset($data['Id']) && !$data['Id'] ) { unset($data['Id']); }
        return $data;
    }

    //添加修改问题
    protected function _checkAddOrEditData($data,$model){

        if(isset($data['Id'])){
            $model = $model::findOne($data['Id']);
        }
        foreach($data as $k=>$v){
            $model->$k = $v;
        }
        $res = $model->save();
        return $res;
    }
    
     //将一个值为数组的中一个值转化为外围数组的一个键
    protected function _ZhiZhuanJian($data,$jian,$zhi = 0){
        if($zhi){
            foreach($data as $k=>$v){
                foreach($zhi as $key => $val){
                    $keys = is_int($key) ? $val : $key ;
                    $new_data[$v[$jian]][$keys] = $v[$val];
                }
            }
        }else{
            foreach ($data as $k => $v) {
                $new_data[$v[$jian]] = $v;
            }
        }
        return $new_data;
    }
}

?>
