<?php
// 问题时间插入

namespace app\commands;
use Yii;
use yii\console\Controller;
use app\models\QueModel;
use app\models\MatchInfoModel;
use app\models\BaskMatchInfoModel;

class QueController extends Controller{

    private $time = 2;     //获取准确的比赛时间偏差

    public function actionIndex(){
        $log = '';
        $log .= 'start_time: '.date('Y-m-d H:i:s');
        $log .= $this->_checkMatchTime();
        $log .= 'end_time:'.date('Y-m-d H:i:s')."\n\r";
        file_put_contents('./log/log.txt', $log,FILE_APPEND);

    }

    //获取比赛的准确的开始时间和足球15-45赛场的竞猜开始时间
    private function _checkMatchTime(){
        $time = $this->time;
        $filed_que = ['id','match_time','match_id','type'];
        $where_que = ['and',['or','time_option=1','time_option=-1'],'match_time-'.time().'<= 3600*'.$time];

        $data = QueModel::find()->select($filed_que)->where($where_que)->asArray()->all();          //查询两个小时内将要直播的比赛
        $ball_match_id = $bask_match_id = $ball_match_id_tump = $bask_match_id_tump = array();
        //将篮球和足球区分开
        foreach($data as $k=>$v){
            if($v['type'] == 1){
                $ball_match_id[] = $v['match_id'];
            }else{
                $bask_match_id[] = $v['match_id'];
            }
            $match_time[$v['match_id']] = $v['match_time'];

        }
        $filed_match = ['MatchID','MatchDateTime'];

        $ball_time_arr = MatchInfoModel::find()->select($filed_match)->where(['MatchID'=>$ball_match_id])->asArray()->all();
        $bask_time_arr = BaskMatchInfoModel::find()->select($filed_match)->where(['MatchID'=>$bask_match_id])->asArray()->all();

        foreach($ball_time_arr as $v){ $ball_time[$v['MatchID']] = strtotime($v['MatchDateTime']);}
        foreach($bask_time_arr as $v){ $bask_time[$v['MatchID']] = strtotime($v['MatchDateTime']);}
        $ball_sql = 'match_time= CASE match_id ';           //足球开始时间
        $ball_15_45_start_sql = 'start_time= CASE match_id ';          //足球15-45场竞猜开始时间
        $ball_15_45_end_sql = 'end_time= CASE match_id ';          //足球15-45场竞猜结束时间
        $ball_45_60_start_sql = 'start_time= CASE match_id ';          //足球45-60场竞猜开始时间
        $ball_45_60_end_sql = 'end_time= CASE match_id ';          //足球45-60场竞猜结束时间
        $bask_sql = 'match_time= CASE match_id ';           //篮球开始时间
        $bask_2_sql = 'start_time= CASE match_id ';           //篮球第二节场竞猜开始时间

        foreach($data as $k=>$v){
            if($v['type']== 1 && $v['match_time'] != $ball_time[$v['match_id']]){
                $ball_15_45_start_sql .= ' WHEN '.$v['match_id'].' THEN '.($ball_time[$v['match_id']]-60*5);             //获取足球15-45场的竞猜开始时间
                $ball_15_45_end_sql .= ' WHEN '.$v['match_id'].' THEN '.($ball_time[$v['match_id']]+60*13);             //获取足球15-45场的竞猜开始时间
                $ball_45_60_start_sql .= ' WHEN '.$v['match_id'].' THEN '.($ball_time[$v['match_id']]+60*14);             //获取足球15-45场的竞猜开始时间
                $ball_45_60_end_sql .= ' WHEN '.$v['match_id'].' THEN '.($ball_time[$v['match_id']]+60*55);             //获取足球15-45场的竞猜开始时间
                $ball_sql.= ' WHEN '.$v['match_id'].' THEN '. $ball_time[$v['match_id']];                 //获取足球比赛的准确开始时间
                $ball_match_id_tump[] = $v['match_id'];                                                     //获取需要修改足球的赛事ID
            } else if($v['type']== -1 && $v['match_time'] != $bask_time[$v['match_id']]){
               $bask_sql .= ' WHEN '.$v['match_id'].' THEN '. $bask_time[$v['match_id']];                  //获取篮球比赛的准确开始时间
               $bask_sql1 .= ' WHEN '.$v['match_id'].' THEN '. ($bask_time[$v['match_id']]-60*5);                  //获取篮球比赛第二节竞猜的开始时间
               $bask_match_id_tump[] = $v['match_id'];                                                      //获取需要修改的篮球的赛事ID


            }
        }
        $log = '';
        if($ball_match_id_tump){

            $match_id = implode(',', $ball_match_id_tump);
            $log .= '  足球更新的数量：'.($this->_updateDate($ball_sql.' END ', $match_id));
            $log .= '  15-45场比赛竞猜时间：'.($this->_updateDate($ball_15_45_start_sql.' END '.$ball_15_45_end_sql.' END ', $match_id,'and time_option=-1'));
            $log .= '  45-60场比赛竞猜时间：'.($this->_updateDate($ball_45_60_start_sql.' END '.$ball_45_60_end_sql.' END ', $match_id,'and time_option=-2'));
        }else{
            $log .= '  没有需要更新的足球的问题';
        }
        if($bask_match_id_tump){
            $update_sql = $bask_sql. ' END ';
            $match_id = implode(',', $bask_match_id_tump);
            $log .= '  篮球更新的数量：'.($this->_updateDate($update_sql, $match_id));
            $update_sql = $ball_sql1.' END ';
            $log .= '  第二节场比赛竞猜开始时间：'.($this->_updateDate($update_sql, $match_id,'and time_option=1'));
        }else{
            $log .= '  没有需要更新的篮球的问题';
        }
        return $log;

    }


    //实验
    private function _checksql($time_field,$true_time,$data,$time_con,$type){
        $sql = $time_field.'= CASE match_id ';
            
        foreach($data as $k=>$v){
            if($v['type'] == $type && $v['match_time'] != $ball_time[$v['match_id']]){
                $sql .= ' WHEN '.$v['match_id'].' THEN '.$time_con;
                $match_id[] = $v['match_id'];
            }
        }
        
        return [$sql,$match_id];
    }
    

    //更新比赛时间
    private function _updateDate($update_sql,$match_id,$addwhere = ''){
         $sql_true = 'UPDATE sl_que_content SET'.$update_sql.'WHERE match_id IN ('.$match_id.') and check_state=1 and stop_state=0  '.$addwhere.';';
         return Yii::$app->db->createCommand($sql_true)->execute();
    }
    
    public function actionTime6190(){
//        $field = [''];
        $where = ['and','time_option=-3','start_time <> 0','match_time-'.(time()).'< 90'];
        $match_id = QueModel::find()->select('match_id')->distinct()->where($where)->asArray()->column();
        
    }


}
