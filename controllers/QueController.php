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
use app\models\QueInitModel;
use app\models\QuePartModel;
use app\models\WallertModel;
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

        $field = ['id','que_content','type','que_group','time_option','lottery_state','start_time','stop_time','stop_state','que_option','que_answer','check_state','add_time','zhi_id'];
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
        $que_info['wen_info1'] = explode('|', $que_info['wen_info'])[0];
        $que_info['wen_info2'] = explode('|', $que_info['wen_info'])[1];

        return $this->render('AddQue',['info'=>$que_info]);
    }

    //搜索比赛
    public function actionSearchs(){
        if(Yii::$app->request->isAjax){
            $match_list = $this->_getMatchId(array('id','Name','Time','MatchInfoID','BaskMatchInfoID') , 'all',date('Y-m-d'),array('or','MatchInfoID <> ""','BaskMatchInfoID <> ""'));
            return json_encode($match_list);


        }
    }

    //处理提交的问题
    public function actionInsert(){
        $post_field = ['id'=>'Id','sportsType'=>'type','que_group','que_content','que_option1','que_option2','que_answer1','que_answer2','time'=>'time_option','start_time','end_time'=>'stop_time','match_id','zhi_id'];
        $data = $this->_checkAjaxdate($post_field, 'post');
        $data['que_option'] = $data['que_option1'].','.$data['que_option2'];
        $data['wen_info'] = $data['que_answer1'].'|'.$data['que_answer2'];
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
            $state = QueModel::findOne($id);
            if($state->check_state){return 2;}
            $res = QueModel::findOne($id)->delete();
            return $res;
        }
    }
    //审核比赛
    public function actionCheck(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $id = intval($request->get('id'));
            $type = intval($request->get('type'));
            $model = QueModel::findOne($id);
            $model->scenario = 'check';
            $model->check_state = 1;
            $res = $model->save();
            echo json_encode([$res,'check']);
        }
    }
    //取消比赛
    public function actionRemove(){
        $request = \Yii::$app->request;
        if(!$request->isAjax){ return 0;}
        $id = intval($request->get('id'));
        $check = QueModel::find()->select(['id','check_state'])->where(['id'=>$id])->asArray()->one();
        if($check['check_state']== 1){
            if(!($this->_returnFen($id))){ return false;}
            $model = QueModel::findOne($id);
            $model->scenario = 'remove';
            $model->stop_state = 1;
            $res = $model->save();
        }
        return true;

    }
    //返回积分
    private function _returnFen($id){
        $init_id_tump = QueInitModel::find()->select(['id','money','user_id'])->where(['que_id'=>$id])->asArray()->all();

        $part_id_tump = QuePartModel::find()->select(['id','init_id','user_id'])->where(['que_id'=>$id])->asArray()->all();
        return $this->_FanHuiJiFen($part_id_tump, $init_id_tump);

    }
    //返回积分
    private function _FanHuiJiFen($part_id_tump,$init_id_tump){
        foreach ($part_id_tump as $v){
            $part_user_sql[$v['init_id']][] = ' WHEN '.$v['user_id'].' THEN credit+';
            $part_user_id[$v['init_id']][] = $v['user_id'];
            $part_id [$v['init_id']][] = $v['id'];
        }
        $query_user = Yii::$app->db;
        $res_id =array();
        foreach($init_id_tump as $v){
            $sql = 'UPDATE business_wallet SET credit = CASE id';
            $sql .= implode($v['money'], $part_user_sql[$v['id']]);
            $sql .= $v['money'] . ' WHEN '.$v['user_id'].' THEN '.($v['money']*2).' END ';
            $sql .= 'WHERE id IN ('.(implode(',', $part_user_id[$v['id']])).','.$v['user_id'].')';
            $res[$v['id']] = $query_user->createCommand($sql)->execute();
            if(!$res[$v['id']]){
                $res_id[] = $v['id'];
                // continue;
            }
            $this->_checkUserDeal($part_id[$v['id']],$part_user_id[$v['id']], $v['user_id'],$v['id'], $v['money'],-1);
        }
        if(!$res_id){
            file_put_contents('../log/cuowu.txt', serialize($res_id));
            return FALSE;

        }
        return true;
    }

//    处理交易记录
    private function _checkUserDeal($part_id,$part_user_id,$init_user_id,$init_id,$money,$type){
        // $type = -1;                 //取消开奖
        $field = ['user_id','type_id','from_id','money','add_time'];
        $sql = 'insert into sl_user_deal values ';
        foreach ($part_user_id as $k => $v){
            $sql .= '(null,'.$v.','.$type.','.$part_id[$k].','.$money.','.(time()).'),';

        }
        $sql .= '(null,'.$init_user_id.','.$type.','.$init_id.','.($money*2).','.(time()).')';

        return Yii::$app->db->createCommand($sql)->execute();

    }
    //开奖
    public function actionLottery(){
        ini_set('max_execution_time', 0);
        $request = Yii::$app->request;
        if($request->isAjax){
            $que_id = intval($request->get('id'));
            $answer = QueModel::find()->select(['que_answer'])->where(['id'=>$que_id])->asArray()->scalar();
            if($answer == '') {return '请填写答案,请先填写答案';}

            $init_user_tump = QueInitModel::find()->select(['id','user_id','money','par_person','f_par_persons','que_answer'])->where(['que_id'=>$que_id,'lottery_state'=>0])->asArray()->all();
            if(!$init_user_tump) return '没有发起者';

            $answer_arr = $answer ? [1,0,0,1] : [0,0,1,1];
            $part_user_tump = QuePartModel::find()->select(['id'=>'sl_que_part.id','init_id','init_option','user_id'=>'sl_que_part.user_id'])->join('LEFT JOIN','sl_que_init','sl_que_init.id=init_id')->where(['and','sl_que_init.que_id='.$que_id,'1=1'])->asArray()->all();           //获取参与者=
            if(!$part_user_tump) return '没有参与者,请取消该问题';

            $init_user = $this->_ZhiZhuanJian($init_user_tump, 'id');
            $part_user = $this->_GroupArray($part_user_tump, 'init_id');

            foreach($part_user as $k=>$v){
                foreach($v as $key=>$val){
                    if($val['init_option'] == 0){
                        $part_user_zan_fan[$k][0][] = $val;
                    }else{
                        $part_user_zan_fan[$k][1][] = $val;
                    }
                }
            }
            //将没有参与者的剔除来
            $data_tump = $this->_TiChuLiuPai($init_user, $part_user_zan_fan,$answer_arr);

            //将流拍的返回积分；
            if(!$data_tump[0] == ''){
                // var_dump($data_tump[0]);die;
                $part_id_tump = QuePartModel::find()->select(['id','init_id','user_id'])->where(['init_id'=>$data_tump[0]])->asArray()->all();
                $init_id_tump = QueInitModel::find()->select(['id','money','user_id'])->where(['id'=>$data_tump[0]])->asArray()->all();
                $this->_FanHuiJiFen($part_id_tump, $init_id_tump);
            }
            //发送积分
            $this->_FenFaJiFen($data_tump[1], $answer_arr, $init_user);
            QueModel::updateAll(['lottery_state'=>1],['id'=>$que_id]);
            return true;
        }
    }
    //分发积分
    private function _FenFaJiFen($part_data,$answer,$init_data){
        $query = Yii::$app->db;
        foreach($part_data as $k => $v){
            $sql = 'UPDATE business_wallet SET credit = CASE id';
            $answer_tump = array_keys($v);
            $answer_str = $answer_tump[0];  //获取当前发起者选项
            $user_num = $answer_str ? ($init_data[$k]['par_person']+2) : $init_data[$k]['f_par_persons'];
            $money_one = ($user_num * $init_data[$k]['money']/($answer_str ?  ($init_data[$k]['f_par_persons']) : ($init_data[$k]['par_person']+2)));
            $user_id = $part_id = array();
            if(is_array($v[$answer_str])){
                foreach($v[$answer_str] as $va){
                    $sql .= ' WHEN '.$va['user_id'].' THEN (credit+'.($money_one+$init_data[$k]['money']).')';
                    $user_id[] = $va['user_id'];
                    $part_id[] = $va['id'];
                }
            }
            if(!$answer_str){
                $user_id[] = $init_data[$k]['user_id'];
                $sql .= ' WHEN '.($init_data[$k]['user_id']).' THEN (credit+'.(($money_one+$init_data[$k]['money'])*2).')';
            }
            $sql .= ' END WHERE id IN ('.(implode(',', $user_id)).')';

            $res = $query->createCommand($sql)->execute();
            if(!$answer_str) array_pop($user_id);
            $this->_checkUserDeal($part_id, $user_id, $init_data[$k]['user_id'],$init_data[$k]['id'], ($money_one+$init_data[$k]['money']),3);
            QueInitModel::updateAll(['lottery_state'=>1],['id'=>$init_data[$k]['id']]);
        }
         if(!$res) return false;
    }

    //剔除流拍的
    private function _TiChuLiuPai($init_user,$part_user,$answer_arr){

        $init_fan_fen = $part_zan_fan = array();
        foreach($init_user as $key => $value){
            if($value['f_par_persons'] == 0){
                $init_fan_fen[] = $value['id'];
            }else{
                $tump = $value['que_answer'] == $answer_arr[0] ? 1 : 3;             //查看当前发起人的发起的选项
                $part_zan_fan[$key][$answer_arr[$tump]] = array();
                foreach($part_user[$key] as $k => $v){

                    if($k==$answer_arr[$tump]){
                        $part_zan_fan[$key][$k] = $v;
                    }
                }

            }
        }
        return [$init_fan_fen,$part_zan_fan];
    }
    //分组
    protected function _GroupArray($data,$where,$zhi=false){
        if($zhi){
            foreach($data as $k=>$v){
                foreach($zhi as $key => $val){
                    $keys = is_int($key) ? $val : $key ;
                    $new_data[$v[$where]][$k][$keys] = $v[$val];
                }
            }
        }else{
            foreach ($data as $k => $v) {
                $new_data[$v[$where]][$k] = $v;
            }
        }
        return $new_data;
    }


    //填写答案
   public function actionAnswer(){
       $request = Yii::$app->request;
       if($request->isAjax){
           $id = intval($request->get('id'));
           $answer = intval($request->get('answer'));
           $model = QueModel::findOne($id);
           $model->scenario = 'answer';
           $model->que_answer = $answer;
           $res = $model->save();

           return $res;
       }
   }
   //重新开奖
    public function actionLotteryrestart(){
       $request = Yii::$app->request;
       if($request->isAjax){
           $id = $request->get('id');
           $answer_new = $request->get('answer');
           $answer_old = QueModel::findOne($id);
           if($answer_old->que_answer == $answer_new){
               return '答案一样，请重新填写';
           }
           return '答案一样，请重新填写';
       }
   }
}
