<?php
/*
 * 爆料管理
 */
namespace app\controllers;
use Yii;
use app\controllers\BaseController;
use app\models\BuffModel;
use app\models\CommentModel;
use app\models\ReplyModel;
use app\models\UserModel;
use yii\data\Pagination;

class BuffController extends BaseController{

    //爆料列表
    public  function actionIndex() {
        $query = BuffModel::find();
        //分页处理
        $pagination = new Pagination(['defaultPageSize' => 10,'totalCount' => $query->count()]);
        $filed_arr = ['sl_buff.ID','user_id','buff_title','is_check','is_top','add_time','comment_num'];
        $filed_str = implode(',', $filed_arr);
        $data = $query->select($filed_arr)->joinWith('business_customer')->orderBy('add_time')->offset($pagination->offset)->limit($pagination->limit)->all();
        echo $this->render('index',['data'=>$data,'pagination' => $pagination,]);
    }

    

    //通过Ajax获取爆料的详细信息
    public function actionCheckbuff(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $id = intval($request->get('id'));
            $filed_arr = ['ID'=>'id','buff_title'=>'title','buff_content'=>'content','img_file','thumb_img_file'=>'img_thumb'];
            $query = BuffModel::findone($id);
            $data = $this->_checkAjaxInfo($query,$filed_arr);
            return json_encode($data);

        }
    }
    //通过Ajax获取爆料相应的评论信息
    public function actionCheckcomment(){
        $request = Yii::$app->request;
        if(!$request->isAjax) return false;
        $id = intval($request->get('buff_id'));
        $filed_arr = ['id','buff_id','user_id','content','save_time'=>'time','nick_name'=>'username'];
        $data_thumb = CommentModel::find()->joinWith('business_customer')->where(['buff_id'=>$id])->all();
        foreach ($data_thumb as $k => $v){
            $data_comment[$k] = $this->_checkAjaxInfo($v, $filed_arr);
            $data_id[$k] = $data_comment[$k]['id'];
        }
        //获取评论相关的回复信息
        $data_reply = array();
        $data_reply_thumb = ReplyModel::find()->joinWith('business_customer')->where(['comment_id'=>$data_id])->all();
        if($data_reply_thumb) return json_encode('暂无评论');
        $filed_arr = ['id','comment_id'=>'com_id','user_id','content','p_user_id','p_reply_id','save_time','nick_name'=>'username'];
        foreach ($data_reply_thumb as $v){
            $data_reply[] = $this->_checkAjaxInfo($v, $filed_arr);
        }
    }
    //将评论和回复递归处理
    private function _CheckCommentInfo(){

    }

    //处理获取的信息
    private function _checkAjaxInfo($data,$filed){
            if(!is_array($filed))return false;
            foreach ($filed as $k => $v){
                $k = is_int($k) ? $v : $k ;
                $res[$v] =  isset($data[$k]) ? $data[$k] : $data->business_customer->$k;
            }

        return $res;
    }

    //删除相应的爆料和评论
    public function actionDel(){

    }
    //处理审核模式
    public function actionCheckpass(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $id = intval($request->get('buff_id'));
            $type_id = intval($request->get('buff_type'));
            $type_id_tump = $type_id == 1 ? 2 : 1;
            $buffInfo = BuffModel::findOne($id);
            $buffInfo->scenario = 'checkpass';
            $buffInfo->is_check = $type_id_tump;
            $res = $buffInfo->save();
            return json_encode($res);
        }
    }

    //处理置顶模式
    public function actionChecktop(){
        $request = Yii::$app->request;
        if($request->isAjax){
            // $type = intval($request->get('top_type'));
            $top_id = intval($request->get('top_num'));
            $top_res = BuffModel::updateAll(['is_top'=>0],'is_top <> 0');
            $buffInfo = BuffModel::findOne($top_id);
            $buffInfo->scenario = 'checktop';
            $buffInfo->is_top = $top_id;
            $res = $buffInfo->save();
            return json_encode($res);
        }
    }


}

?>
