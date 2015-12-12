<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\LinkPager;
AppAsset::register($this);
AppAsset::addScript($this,'@web/static/js/jqueryExtend.js');
AppAsset::addScript($this,'@web/static/js/jqueryFnExtend.js');
$this->title = '新增比赛问题';
$js = "\n" .'var insert_url = "'.Url::toRoute(['/que/insert']).'";';
$js .= "\n" .'var search_url = "'.Url::toRoute(['/que/searchs']).'";';
$js .= "\n" .'var csrf_value = "'.yii::$app->request->csrfToken.'";';
$js .= "\n" .'var csrf_name = "'.yii::$app->request->csrfParam.'";';
// var_dump($info);die;
$js .=<<<JS
$(function(){
    submitTable();
    checkOption();
    checkQue();
    // checkTimeOption();

});
//问题提交
function submitTable(){
    $('#submit_data').click(function() {
        var params=$('#table_list [id^=data_]').getVal();
        for(var v in params){
            params[v] =params[v].replace(/^\s+|\s+$/g,"");
        }
        var bools = validateData(params);
        if(!(bools)){ return false;}
        // console.log(params);
        params[csrf_name] = csrf_value;
        $.post(insert_url,params,function(data){
            console.log(data);
        },'json');
    })
}

function validateData(data){
    if(data['que_content'] == '' || data['que_option1'] == '' || data['que_option2'] == '' || data['que_answer1'] == '' || data['que_answer2'] == ''){
        alert('此为必选字段，请填写');
        return false;
    }
    if(data['time'] == 0 && (data['start_time'] == '' || data['end_time'] == '')){
        alert('请填写时间,或者时间段');
        return false;
    }

    return true;
}
//处理问题答案
function checkOption(){
    $('#data_que_option1').next().children('.textbox-text').bind('focusout',function(){

            var que_info = $('#data_que_content').textbox('getValue');
            var opt1_info = $(this).val();
            opt1_info =opt1_info.replace(/^\s+|\s+$/g,"");
            if (!opt1_info) {
                return;
            }
            else{
                var opt2_info = '不'+opt1_info;
                var que_xuanze = opt1_info+opt2_info
                $('#data_que_option2').textbox('setValue',opt2_info);
                var opt1_ans_info = que_info.replace(que_xuanze,opt1_info);
                $('#data_que_answer1').textbox('setValue',opt1_ans_info);
                var opt2_ans_info = que_info.replace(que_xuanze,opt2_info);
                $('#data_que_answer2').textbox('setValue',opt2_ans_info);
            }

        });
}
//重置选项
function checkQue(){
    $('#data_que_content').next().children('.textbox-text').bind('focusout',function(){
        var data=['是不是','可不可以','能不能','可以不可以','行不行','会不会'];
        var content=$(this).val();

        if (!content) {
            $('#data_que_option1,#data_que_option2,#data_que_answer1,#data_que_answer2').textbox('setValue',' ');
        }
        else{
            data.forEach(function(index){
                if (content.indexOf(index)>-1) {
                    if (index=='可不可以'||index=='可以不可以') {
                        $('#data_que_option1').textbox('setValue','可以');
                        $('#data_que_option2').textbox('setValue','不可以');
                        $('#data_que_answer1').textbox('setValue',content.replace(index,'可以'));
                        $('#data_que_answer2').textbox('setValue',content.replace(index,'不可以'));
                    }
                    else if(index=='是不是'||index=='能不能'||index=='行不行'||index=='会不会'){
                        $('#data_que_option1').textbox('setValue',index.substring(0,1));
                        $('#data_que_option2').textbox('setValue',index.substring(1,3));
                        $('#data_que_answer1').textbox('setValue',content.replace(index,index.substring(0,1)));
                        $('#data_que_answer2').textbox('setValue',content.replace(index,index.substring(1,3)));
                    }
                    else{
                        $('#data_que_option1,#data_que_option2,#data_que_answer1,#data_que_answer2').textbox('setValue',' ');
                    }
                }
                else{
                    $('#data_que_option1,#data_que_option2,#data_que_answer1,#data_que_answer2').textbox('setValue',' ');
                }
            });
        }

    });
}
//处理时间选择
function checkTimeOption(){
    var html_ball=[{value: '0',text: '--请选择--'},{value: '-1',text: '15-45分钟'},{value: '-2',text: '45-60分钟'},{value: '-3',text: '60-90分钟'}];
    var html_bask=[{value: '0',text: '--请选择--'},{value: '1',text: '第二节'},{value: '2',text: '第三节'},{value: '3',text: '第四节'}];
    var data = $('#data_sportsType').combobox('getValue') == 1 ? html_ball : html_bask;
    $('#data_time').combobox('loadData',data);
}
//搜索比赛
function searchMatch(){
        var data_dump = $('#data_related_match').textbox('getValue');
        if(data_dump == ''){return ;}
        var data = {match_name: $('#data_related_match').textbox('getValue')};
        $.getJSON(search_url,data,function(data){
            var comboboxData=[];
            data.forEach(function(index){
                var match_id = !index['MatchInfoID'] ? index['BaskMatchInfoID'] : index['MatchInfoID'] ;
                comboboxData.push({'value':index['id']+','+match_id,'text':index['Name']});
            });
            // console.log(comboboxData);
            $('#select_match').combobox('loadData',comboboxData);
            $('#select_match').show();
        });

}
//处理比赛
function checkMatch(){
    var match_id = $('#select_match').combobox('getValue');
    var match_id_arr = match_id.split(',');
    $('#data_zhi_id').val(match_id_arr[0]);
    $('#data_match_id').textbox('setValue',match_id_arr[1]);
    $('#data_related_match').textbox('setValue',$('#select_match').combobox('getText'));
}
JS;

$this->registerJs($js,  View::POS_END);
?>

<div id="p" class="easyui-panel" title="新增比赛问题" style="padding:10px;">
    <form id="table_list" method="post">
        <input type="hidden" id="data_zhi_id" value="<?php echo isset($info['zhi_id']) ? $info['zhi_id'] : '' ?>" />
        <input type="hidden" id="data_id" value="<?php echo isset($info['id']) ? $info['id'] : '' ?>" />
       <table style="border-collapse: initial;border-spacing: 20px;width:600px;margin:0 auto">
           <tr>
               <td>体育类别:</td>
               <td>
                    <select class="easyui-combobox" id="data_sportsType" data-options='onSelect:checkTimeOption' panelHeight='auto' style="width:100px;">
                        <option value="1" <?php echo  isset($info['type']) && $info['type'] == 1 ? 'selected="selected"' : '' ?>>足球</option>
                        <option value="2" <?php echo  isset($info['type']) && $info['type'] == 2 ? 'selected="selected"' : '' ?>>篮球</option>
                    </select>
                </td>
           </tr>
           <tr>
               <td>问题形式:</td>
               <td>
                    <select class="easyui-combobox" id="data_que_group" panelHeight='auto' style="width:100px;"disabled="disabled">
                        <option value="2" >唯一</option>
                        <option value="1" >通用</option>
                    </select>
                </td>
           </tr>
           <tr>
               <td>题目:</td>
               <td><input class="easyui-textbox" type="text" id="data_que_content" style="width:400px;" value="<?php echo isset($info['que_content']) ? $info['que_content'] :''?>"></input></td>
           </tr>
           <tr>
               <td></td>
               <td>
                    <input class="easyui-textbox" type="text"  id="data_que_option1" style="width:50px;"value="<?php echo isset($info['que_option1']) ? $info['que_option1'] :''?>"></input>
                    <input class="easyui-textbox" type="text" id="data_que_answer1" style="width:344px;"value="<?php echo isset($info['wen_info1']) ? $info['wen_info1'] :''?>"></input>
               </td>
           </tr>
           <tr>
               <td></td>
               <td>
                   <input class="easyui-textbox" type="text"  id="data_que_option2" style="width:50px;"value="<?php echo isset($info['que_option2']) ? $info['que_option2'] :''?>"></input>
                   <input class="easyui-textbox" type="text" id="data_que_answer2" style="width:344px;"value="<?php echo isset($info['wen_info2']) ? $info['wen_info2'] :''?>"></input>
               </td>
           </tr>
           <tr>
               <td>时间段:</td>
               <td>
                    <select class="easyui-combobox" id="data_time"  data-options='valueField: "value",textField: "text"' panelHeight='auto' style="width:200px;">
                        <option value="0"<?php echo  isset($info['time_option']) && $info['time_option'] == 0 ? 'selected="selected"' : '' ?>>--请选择--</option>
                    <?php if(isset($info['type']) && $info['type'] == 2){?>
                        <option value="1"<?php echo  isset($info['time_option']) && $info['time_option'] == 1 ? 'selected="selected"' : '' ?>>第二节</option>
                        <option value="2"<?php echo  isset($info['time_option']) && $info['time_option'] == 2 ? 'selected="selected"' : '' ?>>第三节</option>
                        <option value="3"<?php echo  isset($info['time_option']) && $info['time_option'] == 3 ? 'selected="selected"' : '' ?>>第四节</option>
                    <?php }else{?>
                        <option value="-1"<?php echo  isset($info['time_option']) && $info['time_option'] == '-1' ? 'selected="selected"' : '' ?>>15-45分钟</option>
                        <option value="-2"<?php echo  isset($info['time_option']) && $info['time_option'] == '-2' ? 'selected="selected"' : '' ?>>45-60分钟</option>
                        <option value="-3"<?php echo  isset($info['time_option']) && $info['time_option'] == '-3' ? 'selected="selected"' : '' ?>>60-90分钟</option>
                    <?php }?>
                </select><br />
                    <br />从<input class="easyui-datetimebox" id="data_start_time" value="<?php echo isset($info['start_time']) ? $info['start_time'] :''?>" style="width:200px">-<input class="easyui-datetimebox" id="data_end_time" value="<?php echo isset($info['stop_time']) ? $info['stop_time'] :''?>" style="width:200px">
                </td>
           </tr>
           <tr>
               <td>关联比赛:</td>
               <td>

                   <input class="easyui-textbox" type="text" id="data_related_match" name="data_related_match" style="width:310px;"<?php echo isset($info['Name']) ? 'disabled="disabled"' : ''?> value="<?php echo isset($info['Name']) ? $info['Name'] : ''?>"></input>
                   <?php if(!isset($info['Name'])){?>
                   <a href="javascript:searchMatch()" class="easyui-linkbutton" data-options="iconCls:'icon-search'" id="searchs" style="width:80px">搜索</a>
                   <?php }?>
               </td>
           </tr>
           <tr>
               <td></td>
               <td>

                   <select class="easyui-combobox" id="select_match" data-options='onSelect:checkMatch'  style="width:400px;">

                   </select>
               </td>
           </tr>
           <tr>
               <td>关联比赛ID:</td>
               <td>
                   <input class="easyui-textbox" type="text" id="data_match_id" disabled="disabled" value="<?php echo isset($info['match_id']) ? $info['match_id'] :''?>"></input>
               </td>
           </tr>
           <tr>
               <td colspan="2" align="center">
                   <a href="#" class="easyui-linkbutton" id="submit_data" data-options="iconCls:'icon-save'">提交</a>
               </td>
           </tr>

       </table>
   </form>
</div>
