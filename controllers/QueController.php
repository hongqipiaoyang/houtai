<?php
/**
 * 问题控制器
 */
namespace app\controllers;
use Yii;
use app\controllers\BaseController;
use yii\data\Pagination;
use app\models\QueModel;
use app\models\MatchInfoModel;
use app\models\ZhiboModel;
use yii\helpers\Url;

class QueController extends BaseController
{
    //阶段分类
    private $time_option = ['-3'=>'60-90分钟','-2'=>'45-60分钟','-1'=>'15-45分钟','1'=>'第二节','2'=>'第三节','3'=>'第四节'];

    //问题管理页面
    public function actionIndex(){
        $request = Yii::$app->request;
        $zhi_id = $request->get('zhi_id') ?$request->get('zhi_id') : 0 ;
        Yii::$app->view->params['return']=Url::toRoute(['/que/team']);
        return $this->render('index',['zhi_id'=>$zhi_id]);
    }

    //获取问题数据加搜索
    public function actionCheckinfo(){
        $request = Yii::$app->request;
        if(!$request->isAjax){ return ;}
        $zhi_id_where= $request->get('zhi_id') !=0 ? 'zhi_id='.$request->get('zhi_id') : '1=1' ;
        //比赛名称
        $match_where = '1=1';
        if($request->get('match_name')){

            // $match_id = ZhiboModel::find()->select('id')->where(['and',['like','Name',$match_name],'DATE_FORMAT(Time,"%Y-%m-%d") >= "2015-12-01"'])->asArray()->column();
            $match_id = _getMatchId('id','column');

            $match_where = ['in','zhi_id',$match_id];
        }
        //时间
        $start_where = $request->get('start_time') ? 'add_time >='.strtotime($request->get('start_time')) : '1=1';
        $end_where = $request->get('end_time') ? 'add_time <='.strtotime($request->get('end_time')) : '1=1';
        $where = ['and',$zhi_id_where,$match_where,$start_where,$end_where];

        $query = QueModel::find();
        //分页
        $data_2['total'] = $query->where($where)->asArray()->count();

        $page = $this->_checkPage($data_2['total']);

        $field = ['id','que_content','type','que_group','time_option','start_time','stop_time','check_state','add_time','zhi_id'];
        $data = $query->select($field)->where($where)->offset($page['offset'])->limit($page['limit'])->asArray()->all();
        $data_1 = '';

        if($data != array()){
            foreach ($data as $v) {
                $zhi_id[] = $v['zhi_id'];
            }
            $match_name_tump = ZhiboModel::find()->select(['id','Name'])->where(['id'=>$zhi_id])->asArray()->all();
            foreach ($match_name_tump as $value) {
                $match_names[$value['id']] = $value['Name'];

            }

            foreach($data as $k => $v){
                $data_1[$k] = $v;
                $data_1[$k]['match_name'] = isset($match_names[$v['zhi_id']]) ? $match_names[$v['zhi_id']] : '';
                $data_1[$k]['group_name'] = $v['que_group']==1 ? '通用' : '唯一';
                $data_1[$k]['type_name'] = $v['type']==1 ? '足球' : '篮球';
                $data_1[$k]['check_name'] = $v['check_state'] ? '已审核' : '未审核';
                $data_1[$k]['time_name'] = array_key_exists($v['time_option'],$this->time_option) ? $this->time_option[$v['time_option']] : date('Y-m-d H:i:s',$v['start_time']).'-'.date('Y-m-d H:i:s',$v['stop_time']);
            }
        }
        $data_2['rows']= $data_1;
        return json_encode($data_2);
    }

    //获取相应的比赛信息
    private function _getMatchId($field,$type,$dates = "2015-12-01",$where = ''){
        $match_name = Yii::$app->request->get('match_name');
        return  ZhiboModel::find()->select($field)->where(['and',['like','Name',$match_name],'DATE_FORMAT(Time,"%Y-%m-%d") >= "'.$dates.'"',$where])->asArray()->$type();
    }


    //球队管理页面
    public function actionTeaminfo(){
        $request = Yii::$app->request;

        if($request->isAjax){
            $time = $request->get('time') ? $request->get('time') : date('Y-m-d');
            $type = $request->get('type') ? 'MessageType='.$request->get('type') : '1=1';

            $query = ZhiboModel::find();
            $where = ['and',$type,'DATE_FORMAT(Time,"%Y-%m-%d")="'.$time.'"',['or','MatchInfoID <> ""','BaskMatchInfoID <> ""']];
            //分页
            $data_2['total'] = $query->where($where)->asArray()->count();
            $page = $this->_checkPage($data_2['total']);
            $field = ['ID','Time','Name','MessageType','MatchInfoID','BaskMatchInfoID'];
            $data_2['rows'] = $query->select($field)->where($where)->offset($page['offset'])->limit($page['limit'])->asArray()->all();
            return json_encode($data_2);
        }
    }
    //显示比赛界面
    public function actionTeam(){

        return $this->render('TeamManger');
    }

    //添加问题页面
    public function actionAdd(){
        $request = Yii::$app->request;
        $info_val =array();

        if($request->isGet){
        $info_key = ['id'=>'zhi_id','type','matchID'=>'match_id','Name'];
        $info_val = $this->_checkAjaxdate($info_key,'get');
        Yii::$app->view->params['return']=Url::toRoute(['/que/team']);
        }

        Yii::$app->view->params['return']=Url::toRoute(['/que']);
        return $this->render('AddQue',['info'=>$info_val]);
    }
    //修改问题界面
    public function actionEdit(){

        $id = $this->_checkAjaxdate(['id'],'get');
        $field = ['id','que_content','que_option','wen_info','zhi_id','match_id','time_option','start_time','stop_time','type'];
        $que_info = QueModel::find()->select($field)->where(['id'=>$id])->asArray()->one();
        $que_info['que_option1'] = explode(',', $que_info['que_option'])[0];
        $que_info['que_option2'] = explode(',', $que_info['que_option'])[1];
        $que_info['wen_info1'] = explode(',', $que_info['wen_info'])[0];
        $que_info['wen_info2'] = explode(',', $que_info['wen_info'])[1];

        return $this->render('AddQue',['info'=>$que_info]);
    }

    //搜索比赛
    public function actionSearchs(){
        if(Yii::$app->request->isAjax){
            $match_list = $this->_getMatchId(array('id','Name','MatchInfoID','BaskMatchInfoID') , 'all',date('Y-m-d'),array('or','MatchInfoID <> ""','BaskMatchInfoID <> ""'));
            return json_encode($match_list);


        }
    }

    //处理提交的问题
    public function actionInsert(){
        $post_field = ['id'=>'Id','sportsType'=>'type','que_group','que_content','que_option1','que_option2','que_answer1','que_answer2','time'=>'time_option','start_time','end_time'=>'stop_time','match_id','zhi_id'];
        $data = $this->_checkAjaxdate($post_field, 'post');
        $data['que_option'] = $data['que_option1'].','.$data['que_option2'];
        $data['wen_info'] = $data['que_answer1'].','.$data['que_answer2'];
        $del_arr = ['que_option1','que_option2','que_answer1','que_answer2'];
        $data['time_option'] == 0 ? $del_arr[]='time_option' : $del_arr = array_merge($del_arr,array('start_time','stop_time'));
        $data['start_time'] = $data['start_time'] != '' ? strtotime($data['start_time']) : $data['start_time'];
        $data['stop_time'] = $data['stop_time'] != '' ? strtotime($data['stop_time']) : $data['stop_time'];
        foreach($del_arr as $v){
            unset($data[$v]);
        }
        $data['match_time'] = $this->_getTrueTime($data['match_id'], $data['type']);
        $data['add_time'] = time();
        $query = new QueModel();
        return $this->_checkAddOrEditData($data,$query);

    }

    //获取相对准确的比赛时间
    private function _getTrueTime($MatchID,$type){
        $match_type = $type == 1 ? 'MatchInfo' : 'BaskMatchInfo';
        $sql = 'select MatchDateTime from '.$match_type.' where MatchID = '.$MatchID;
        $date = Yii::$app->db2->createCommand($sql)->queryScalar();
        return strtotime($date);

    }
    //删除问题
    public function actionDel(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $id = intval($request->get('id'));
            $res = QueModel::findOne($id)->delete();
            return $res;
        }
    }

}

 ?>
