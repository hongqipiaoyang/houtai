<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\LinkPager;

AppAsset::register($this);
AppAsset::addScript($this,'@web/static/js/jqueryExtend.js');
AppAsset::addScript($this,'@web/static/js/jqueryFnExtend.js');
$this->title = '管理比赛';
$js = "\n".'var url = "'.Url::toRoute(['/que/teaminfo']).'";';
$js .= "\n".'var add_url = "'.Url::toRoute(['/que/add']).'";';
$js .= "\n".'var que_url = "'.Url::toRoute(['/que']).'";';
$js .= "\n";
$js .=<<<JS

$(function(){
    getTable(url);
});
function getTable(url,params){
    var columns=[[
        {field:'ck',checkbox:true },
        {field:'Time',title:'时间',width:200,align:'center'},
        {field:'Name',title:'赛事',width:300,align:'center'},
        {field:'edit',title:' ',width:200,align:'center',formatter:formatterEdit}
    ]];
    var title='赛事列表';
    var rownumber=true;
    $('#dataList').getTable(url,columns,params,title,rownumber);
}

function formatterEdit(value,row){
    var matchID = row.MessageType == 1 ? row.MatchInfoID : row.BaskMatchInfoID;
    var edit_info = '<a href="javascript:addOrEdit('+row.ID+','+row.MessageType+','+matchID+',\''+row.Name+'\')" style="color:green;">新增问题</a>&emsp;&emsp;';
    edit_info += '<a href="'+que_url+'?zhi_id='+row.ID+'" style="color:blue;">查看问题</a>';
    return edit_info;
}
function addOrEdit(id,type,matchid,name){
    var url_d = add_url+'?id='+id+'&type='+type+'&matchID='+matchid+'&Name='+name;
    window.location.href=url_d;
}
function search(){
        //搜索，获取input time ajax提交
        var time = $('#data_time').datebox('getValue');
        var type = $('#data_sportsType').combobox('getValue');
        var parmas={'time':time,'type':type};

        //成功后执行 data 格式同 data.json
        getTable(url,parmas);
    }
JS;
$this->registerJs($js,  View::POS_END);

?>

<div id="toolbar"style="padding:10px 0 10px 10px;">
    按时间 &emsp;<input id="data_time" class="easyui-datebox" style="width:200px">
    <select class="easyui-combobox" id="data_sportsType" panelHeight='auto' style="width:100px;">
        <option value="0">全部</option>
        <option value="1">足球</option>
        <option value="2">篮球</option>
    </select>
    <a href="javascript:search()" class="easyui-linkbutton">获取当日精彩赛事</a>&nbsp;
</div>
<div id="dataList"></div>
<div class="center">

</div>
