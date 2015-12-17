<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\LinkPager;

AppAsset::register($this);
AppAsset::addScript($this,'@web/static/js/jqueryExtend.js');
AppAsset::addScript($this,'@web/static/js/jqueryFnExtend.js');
$this->title = '编辑竞猜问题';
$js = "\n".'var url="'.Url::toRoute(['/que/checkinfo'],true).'?zhi_id='.$zhi_id.'";';
$js .= "\n".'var edit_url="'.Url::toRoute(['/que/edit'],true).'";';
$js .= "\n".'var del_url="'.Url::toRoute(['/que/del'],true).'";';
$js .= "\n".'var check_url="'.Url::toRoute(['/que/check'],true).'";';
$js .= "\n".'var remove_url="'.Url::toRoute(['/que/remove'],true).'";';
$js .= "\n".'var Answer_url="'.Url::toRoute(['/que/answer'],true).'";';
$js .= "\n".'var lottery_url="'.Url::toRoute(['/que/lottery'],true).'";';
$js .= "\n".'var lotteryRe_url="'.Url::toRoute(['/que/lotteryrestart'],true).'";';

$js .= "\n";
$js .=<<<JS
$(function(){
    getTable1(url);
});

function getTable1(url,params){
    var columns=[[
        {field:'ck',checkbox:true },
        {field:'type_name',title:'体育类型',width:60,align:'center'},
        {field:'group_name',title:'问题形式',width:60,align:'center'},
        {field:'que_content',title:'问题',width:400,align:'center'},
        {field:'match_name',title:'比赛名称',width:250,align:'center'},
        {field:'time_name',title:'时间段',width:250,align:'center'},
        {field:'check_state',title:'状态',width:80,formatter:formatterCheck,align:'center'},
        {field:'que_answer',title:'答案',width:80,formatter:formatterAnswer,align:'center'},
        {field:'lottery_state',title:'开奖',width:80,formatter:formatterLottery,align:'center'},
        {field:'id',title:'编辑', width:100,formatter:formatterEdit,align:'center'}
    ]];
    var title='比赛问题列表';
    var rownumber=true;
    $('#dataList').getTable(url,columns,params,title,rownumber);
}

function formatterCheck(value,row){

    var message = value == 1 ? (row.stop_state == 1 ? ['#',row.stop_state,'已取消','red']:[remove_url,value,'已审核','blue']):[check_url,value,'未审核','block'];
    return '<a href="javascript:CheckOrRemove(\''+message[0]+'\' ,'+row.id+','+message[1]+')" id="checkOrRemove_'+row.id+'" style="color:blue">'+message[2]+'</a>';
}

function formatterAnswer(val,row){
    var message = '';
    if (row.que_group == 2 && val == '') {
        message = '<a href="javascript:WriterAnswer('+row.id+',\''+row.que_option+'\')" id="answer_'+row.id+'" style="border:1px auto red;color:red" >填写</a>';
    }else{
        var option_Arr = row.que_option.split(',');
        message = val == '' ? '' :(val == 0 ? option_Arr[0] : option_Arr[1] );
    }
    return message;

}
function WriterAnswer(id,option){
    $.messager.prompt('填写答案', '请填写 '+option, function(r){
        var option_Arr = option.split(',');
        var answer_int = 0;
        // console.log(option_Arr);
        if (r && r == option_Arr[1]){
            answer_int = 1;
        }else if (r && r == option_Arr[0]) {
            answer_int = 0;
        }else{
            alert('答案不符合要求');
            return ;
        }

        $.get(Answer_url,{id:id,answer:answer_int},function(data){
            $('#answer_'+id).parent().html(r);
            if(confirm('是否开奖')){
                $.get(lottery_url,{id:id},function(data){
                    var res = data === true ? '开奖成功' : data;
                    alert(res);
                });
            }else{
                return $('#lottery_'+id).html('开奖');
            }
        });
    });

}

function formatterLottery(val,row){
    // return val;
    var message = '';
    if(row.que_answer != ''){
        var data = val == 1 ? ['已','Re'] : ['',''] ;
        message = '<a href="javascript:Lottery'+data[1]+'Start('+row.id+')" id="lottery_'+row.id+'" style="color:red">'+data[0]+'开奖</a>';
    }else{
        message = '<a href="javascript:LotteryStart('+row.id+')" id="lottery_'+row.id+'" style="color:red"></a>';
    }
    return message;
}

function LotteryStart(id){
    $.get(lottery_url,{id:id},function(data){
        var res = data === true ? '开奖成功' : data;
        alert(res);
        $('#lottery_'+id).html('已开奖');
        $('#lottery_'+id).attr('href','javascript:LotteryReStart('+id+')');
    })
}

function LotteryReStart(id){
//    var option = $();
    $.messager.prompt('重新填写答案', '请重新填写本题的答案 ', function(r){
        $.get(lotteryRe_url,{id:id,answer:r},function(data){
            console.log(data);
        })
    })

}

function CheckOrRemove(url,id){

    $.get(url,{id:id},function(data){

        var message = null;
        if(data[1] == 'check' && data[0] == true){
             message = ['已审核',id,remove_url];
        }else if(data[1]=='remove' && data[0] == true){
             message = ['已取消',id,'#'];
        }
        $('#checkOrRemove_'+id).html(message[0]);
        $('#checkOrRemove_'+id).prop('href','javascript:CheckOrRemove("'+message[2]+','+message[1]+'")');

        // console.log(d);
    },'json');
}

function formatterEdit(value,row){
    return  '<a href="javascript:addOrEdit('+row.id+')" style="color:green;">编辑</a>&emsp;&emsp;<a href="javascript:deleteRow('+row.id+')" style="color:red;">删除</a>'
}

function getCheckBox(){
    //获取ids 格式为 1,2,3,4
    var ids=$('#dataList').getEasyuiChecked();
}

function addOrEdit(row){
    var rows = row ? row : '';
    
    window.location.href=edit_url+'?id='+row;
}

function deleteRow(row){
    //ajax删除,row为id
    if(confirm('你确定要删除吗？')){
        $.get(del_url,{id:row} , function(data){
            var message = ['删除成功','删除失败','已审核不能删除'];
            alert(message[data]);
        })
    }
}

function search(){
    //搜索，获取input time ajax提交
    var start_time = $('#start_time').datetimebox('getValue');
    var end_time = $('#end_time').datetimebox('getValue');
    var match_name = $('#match_name').textbox('getValue');
    var parmas={'start_time':start_time,'end_time':end_time,'match_name':match_name};
    //成功后执行 data 格式同 data.json
    getTable1(url,parmas);
}

JS;
$this->registerJs($js,  View::POS_END);
?>

    <div id="toolbar" style="padding:10px 0 10px 10px;">
        按比赛 <input class="easyui-textbox" style="height:24px" id="match_name">&nbsp;
        按添加时间 <input class="easyui-datetimebox" style="width:200px" id="start_time"> - <input class="easyui-datetimebox" style="width:200px" id="end_time">&nbsp;
        <a href="javascript:search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'" style="width:80px">搜索</a>&nbsp;
        <a href="<?=Url::toRoute(['/que/add'])?>" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加比赛问题</a>
        <a href="<?=Url::toRoute(['/que/team'])?>" class="easyui-linkbutton">查看最近球赛</a>

    </div>
    <div id="dataList"></div>
