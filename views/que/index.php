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

$js .= "\n";
$js .=<<<JS
$(function(){
    getTable1(url);
});
function getTable1(url,params){
    var columns=[[
        {field:'ck',checkbox:true },
        {field:'type_name',title:'体育类型',width:100,align:'center'},
        {field:'group_name',title:'问题形式',width:100,align:'center'},
        {field:'que_content',title:'问题',width:400,align:'center'},
        {field:'match_name',title:'比赛名称',width:250,align:'center'},
        {field:'time_name',title:'时间段',width:250,align:'center'},
        {field:'check_name',title:'是否审核',width:80,align:'center'},
        {field:'id',title:'编辑', width:200,formatter:formatterEdit,align:'center'}
    ]];
    var title='比赛问题列表';
    var rownumber=true;
    $('#dataList').getTable(url,columns,params,title,rownumber);
}

function formatterEdit(value,row){
    return  '<a href="" style="color:blue;">审核</a>&emsp;&emsp;<a href="javascript:addOrEdit('+row.id+')" style="color:green;">编辑</a>&emsp;&emsp;<a href="javascript:deleteRow('+row.id+')" style="color:red;">删除</a>'
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
    $.get(del_url,{id:row} , function(data){
        console.log(data);
    })
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
