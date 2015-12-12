<?php
// 问题时间插入

namespace app\commands;
use Yii;
use yii\console\Controller;
use app\models\QueModel;
use app\models\MatchInfoModel;
use app\models\BaskMatchInfoModel;

class QueController extends Controller{
    
    private $time = 7;     //获取准确的比赛时间偏差
    
    public function actionIndex(){
        
        echo 'start_time: '.date('Y-m-d H:i:s');
        $data = $this->_checkMatchTime();
        echo 'end_time:'.date('Y-m-d H:i:s');
        
    }
    //获取比赛的准确的开始时间和足球15-45赛场的竞猜开始时间
    private function _checkMatchTime(){
        $time = $this->time;
        $filed_que = ['id','match_time','match_id','type'];
        $where_que = ['and',['or','time_option=1','time_option=-1'],'match_time-'.time().'<= 3600*'.$time];
        
        $data = QueModel::find()->select($filed_que)->where($where_que)->asArray()->all();
        $ball_match_id = $bask_match_id = $ball_match_id_tump = $bask_match_id_tump = array();
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
        $ball_sql = 'start_time= CASE match_id ';
        $ball_sql1 = 'match_time= CASE match_id ';
        $bask_sql = 'match_time= CASE match_id ';
        
        foreach($data as $k=>$v){
            if($v['type']== 1 && $v['match_time'] != $ball_time[$v['match_id']]){
                $ball_sql .= ' WHEN '.$v['match_id'].' THEN '.($ball_time[$v['match_id']]-60*5);             //获取足球第一场的竞猜开始时间
                $ball_sql1 .= ' WHEN '.$v['match_id'].' THEN '. $ball_time[$v['match_id']];                 //获取足球比赛的准确开始时间
                $ball_match_id_tump[] = $v['match_id'];                                                     //获取需要修改足球的赛事ID
            } else if($v['type']== -1 && $v['match_time'] != $bask_time[$v['match_id']]){   
               $bask_sql1 .= ' WHEN '.$v['match_id'].' THEN '. $ball_time[$v['match_id']];                  //获取篮球比赛的准确开始时间
               $bask_match_id_tump[] = $v['match_id'];                                                      //获取需要修改的篮球的赛事ID
            }
        }
        
        if($ball_match_id_tump){
        
            $update_sql = $ball_sql.' END ';
            $match_id = implode(',', $ball_match_id_tump);
            echo '  足球更新的数量：'.($this->_updateDate($update_sql, $match_id));
            $update_sql = $ball_sql1.' END ';
            echo '  15-45场比赛更新比赛开始时间：'.($this->_updateDate($update_sql, $match_id,'and time_option=-1'));
        }else{
            echo '  没有需要更新的足球的问题';
        }
        if($bask_match_id_tump){
            
            $update_sql = $bask_sql. ' END ';
            $match_id = implode(',', $bask_match_id_tump);
            echo '  足球更新的数量：'.($this->_updateDate($update_sql, $match_id));
        }else{
            echo '  没有需要更新的篮球的问题';
        }
    }
    //更新比赛开始时间
    private function _updateDate($update_sql,$match_id,$addwhere = ''){
         $sql_true = 'UPDATE sl_que_content SET'.$update_sql.'WHERE match_id IN ('.$match_id.') '.$addwhere.';'; 
         return Yii::$app->db->createCommand($sql_true)->execute();
    }
    
    
}
