<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\LinkPager;

AppAsset::register($this);
AppAsset::addCss($this,'@web/static/css/buff.css');
// echo Html::cssFile('@web/static/css/buff.css');
$js = "\n" . 'var buff_url = "'.Url::toRoute(['/buff/checkbuff']).'";';
$js .="\n" . 'var comment_url = "'.Url::toRoute(['/buff/checkcomment']).'";';
$js .="\n" . 'var check_url = "'.Url::toRoute(['/buff/checkpass']).'";';
$js .="\n" . 'var top_url = "'.Url::toRoute(['/buff/checktop']).'";';
$js .="\n" . 'var search_url = "'.Url::toRoute(['/buff/index']).'";';
$js .="\n" . 'var start_time = "'.($search['start_time'] ? $search['start_time'] : '').'";';
$js .="\n" . 'var end_time = "'.($search['end_time'] ? $search['end_time'] : '').'";';

$js .=<<<JS
var top_num = null;
function checkbox(){
    //点击td选中input
    $('.checkbox-out-td').click(function(){
        var obj=$(this);
        var input=obj.children('input');
        if (input.prop('checked')) {
            input.prop('checked',false);
        }
        else{
            input.prop('checked',true);
        }
        if (obj.attr('id')=='checkall-out-td') {
            chooseAllOrNot(input);
        }

    });
    //input阻止冒泡
    $('.checkbox-out-td input').click(function(e){
        stopEventBubble(e);

        var obj=$(this);
        if(obj.attr('id')=='checkall'){
            chooseAllOrNot(obj);
        }

    });

    function chooseAllOrNot(obj){
        if (obj.prop('checked')) {
            $('input[type="checkbox"]').prop('checked',true);
        }
        else{
            $('input[type="checkbox"]').prop('checked',false);
        }
    }

}

function titleEvent(obj){
    var obj=$(obj);
    var tr=obj.parents('tr');
    var nextTr=tr.next('tr');
    var viewButton=tr.children('td').children('.button-view');
    if (obj.hasClass('title-td-select')) {//文章显示
        nextTr.remove();
        obj.removeClass('title-td-select').addClass('title-td-unselect');
    }
    else{
        obj.removeClass('title-td-unselect').addClass('title-td-select');
        var html='';
        //ajax请求 文章内容,赋值html
        var data = {id:$(obj).parent().attr('val')};
        // console.log(data);
        $.getJSON(buff_url,data,function(data){
            var thumb_img = data.img_thumb.split(',');
            html = '<div class="atical-detail">';
            html += '<h3>'+data.title+'</h3>';
            html += '<div class="about-atical clearfix"><span class="writer">'+$(obj).prev().html()+'</span><span class="add-time">'+$(obj).next().html()+'</span></div>';
            html += '<p class="content-area">'+data.content+'</p>';
            html += '<p class="image-area clearfix">';
            thumb_img.forEach(function(img){
                html += '<img src="http://www.qiuwin.com/Uploads/Circle/'+img+'" alt="">';
            })
            html += '</p></div>';


        if (artical_comment_tr_is_show(tr)) {
            viewButton.removeClass('viewed').addClass('unview');
            nextTr.html('<td colspan="8" class="atical-td">'+html+'</td>');
        }
        else{
            tr.after('<tr><td colspan="8" class="atical-td">'+html+'</td></tr>');
        }
        })
    }
}

function viewComment(obj){
    var obj=$(obj);
    var tr=obj.parents('tr');
    var nextTr=tr.next('tr');
    var titleTr=tr.children('.title-td');
    if (obj.hasClass('viewed')) {//文章显示
        nextTr.remove();
        obj.removeClass('viewed').addClass('unview');
    }
    else{
        obj.removeClass('unview').addClass('viewed');
        var html='';
        //ajax请求 文章内容,赋值html

        /*<div class="atical-detail"><div class="comment-area">
						<ul class="first-layer">
							<li>
								<div class="first-layer-comment clearfix">
									<div class="head-portrait">
										<img src="../../../material/10.jpg" alt="">
									</div>
									<div class="comment">
										<div class="comment-content">
											我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，
										</div>
										<div class="comment-time">
											我是评论时间
										</div>
									</div>
										<a class="comment-delete" href="">删除</a>
								</div>
								<ul class="second-layer">
									<li>
										<div class="second-layer-comment clearfix">
											<div class="head-portrait">
												<img src="../../../material/10.jpg" alt="">
											</div>
											<div class="comment">
												<div class="comment-content">
													我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，
												</div>
												<div class="comment-time">
													我是评论时间
												</div>
											</div>
											<a class="comment-delete" href="">删除</a>
										</div>
									</li>
									<li>
										<div class="second-layer-comment clearfix">
											<div class="head-portrait">
												<img src="../../../material/10.jpg" alt="">
											</div>
											<div class="comment">
												<div class="comment-content">
													我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，
												</div>
												<div class="comment-time">
													我是评论时间
												</div>
											</div>
											<a class="comment-delete" href="">删除</a>
										</div>
									</li>
								</ul>
							</li>
							<li>
								<div class="first-layer-comment clearfix">
									<div class="head-portrait">
										<img src="../../../material/10.jpg" alt="">
									</div>
									<div class="comment">
										<div class="comment-content">
											我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，
										</div>
										<div class="comment-time">
											我是评论时间
										</div>
									</div>
									<a class="comment-delete" href="">删除</a>
								</div>
								<ul class="second-layer">
									<li>
										<div class="second-layer-comment clearfix">
											<div class="head-portrait">
												<img src="../../../material/10.jpg" alt="">
											</div>
											<div class="comment">
												<div class="comment-content">
													我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，
												</div>
												<div class="comment-time">
													我是评论时间
												</div>
											</div>
											<a class="comment-delete" href="">删除</a>
										</div>
									</li>
									<li>
										<div class="second-layer-comment clearfix">
											<div class="head-portrait">
												<img src="../../../material/10.jpg" alt="">
											</div>
											<div class="comment">
												<div class="comment-content">
													我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，我是评论内容，
												</div>
												<div class="comment-time">
													我是评论时间
												</div>
											</div>
											<a class="comment-delete" href="">删除</a>
										</div>
									</li>
								</ul>
							</li>
						</ul>
					</div></div>*/
        var data = {buff_id:$(obj).parent().parent().attr('val')}
        console.log(data);
        $.getJSON(comment_url,data,function(data){
            console.log(data);
            html = '<div class="atical-detail"><div class="comment-area">';
            html += '<ul class="first-layer">';
            html += '';
            html += '</ul></div></div>';

            if (artical_comment_tr_is_show(tr)) {
                titleTr.removeClass('title-td-select').addClass('title-td-unselect');
                nextTr.html('<td colspan="7" class="atical-td">'+html+'</td>');
            }
            else{
                tr.after('<tr><td colspan="7" class="atical-td">'+html+'</td></tr>');
            }
    })
    }
}
//comment-detail//atical-detail
//判断是否存在 防止文章内容更或者评论的区域，并显示
function artical_comment_tr_is_show(obj){//atical-td
    var tr=obj.next('tr');
    var td=tr.children('td');

    if (td.hasClass('atical-td')) {
        return true;
    }
    return false;

}
//判断执行审核
function passOrNot(obj){
    var obj=$(obj);
    var buff_type_id = $(obj).attr('val');
    var data = {buff_id:$(obj).parent().parent().attr('val'),buff_type:buff_type_id};
    $.get(check_url,data,function(data){
        if(!data) {alert('审核出现问题，重新刷新页面审核！'); return ;}
        if(buff_type_id == 1){
            $(obj).parent().html('<a class="button button-notpass" onclick="passOrNot(this)"val="2">审核未通过</a>');
        }else {
            $(obj).parent().html('<a class="button button-pass" onclick="passOrNot(this)"val="2">审核通过</a>');
        }
    });
};


function topOrNot(obj){
    if(confirm('置顶后其他已置顶的题目将取消置顶')){
        top_num = $(obj).parent().parent().attr('val');
        var top_id = $(obj).attr('val');
        var data = {top_num:top_num};
        $.get(top_url,data,function(data){
            if(data){
                window.location.href = window.location.href;
            }
        })
    }else{
        return false;
    }

}

function deleteAtical(obj){
    var obj=$(obj);
    //ajax请求删除

    //回调删除gaihang
    obj.parents('tr').remove();

}

//搜索
function searchs(){
    var data = {};
    data.username = $('.username').val() ? $('.username').val() : '';
    data.title=$('.title').val() ? $('.title').val() : '';
    data.dt_start = $('#dt_start').datetimebox('getValue') ? (Date.parse(new Date($('#dt_start').datetimebox('getValue'))))/1000 : '';
    data.dt_end = $('#dt_end').datetimebox('getValue') ? (Date.parse(new Date($('#dt_end').datetimebox('getValue'))))/1000 : '';
    data.state = $('#state :checked').val() != '' ? $('#state :checked').val() : '';

    var url = search_url+'?user='+data.username+'&title='+data.title+'&start_time='+data.dt_start+'&end_time='+data.dt_end+'&state='+data.state;
     url = encodeURI(url);
    window.location.href = url;


}


$(function(){
    checkbox();
    if (start_time != '') { $('#dt_start').datetimebox('setValue',start_time);  }
    if (end_time != '') { $('#dt_end').datetimebox('setValue',end_time); }
});
JS;
$this->registerJs($js,  View::POS_END);
$num = 0;
?>
    <div class="seach-group">
    	用户名：<input class="username" type="text" value="<?php echo $search['user'] ? $search['user'] : ''?>">&emsp;
    	标题：<input class="title" type="text" value="<?php echo $search['title'] ? $search['title'] : ''?>">&emsp;
    	时间：<input class="time-pre easyui-datetimebox" type="text" id="dt_start" /> - <input class="time-next easyui-datetimebox" type="text" id="dt_end" />&emsp;
        状态：<select id="state">
                <option value="">--请选择--</option>
                <option value="0"<?php echo $search['state'] ==0 ? 'selected="selected"': ''?>>&emsp;未审核</option>
                <option value="1"<?php echo $search['state'] ==1 ? 'selected="selected"': ''?>>&ensp;审核通过</option>
                <option value="2"<?php echo $search['state'] ==2 ? 'selected="selected"': ''?>>审核未通过</option>
            </select>
    	<a class="button button-seach" onclick="searchs()">搜索</a>
    </div>
<div class="table-list">
	<table>
		<tr>
			<th width="50" class="checkbox-out-td" id="checkall-out-td"><input name="checkbox" id="checkall" type="checkbox"></th>
			<th width="50">序号</th>
			<th width="150">用户名</th>
			<th width="400">标题</th>
			<th width="200">添加时间</th>
			<th width="100">置顶</th>
			<th width="100">审核</th>
			<th width="200">编辑</th>
		</tr>
        <?php foreach ($data as $k => $v) {?>
		<tr val="<?php echo $v['ID']?>">
			<td class="checkbox-out-td"><input name="checkbox" type="checkbox" value="<?php echo $v['ID']?>"></td>
			<td><?php echo ++$num;?></td>
			<td><?php echo $v['nick_name']?></td>
			<td class="title-td title-td-unselect" onclick='titleEvent(this)'><?php echo $v['buff_title']?></td>
			<td><?php echo date('Y-m-d H:i:s',$v['add_time'])?></td>
			<td>
                <a class="button button-top" onclick="topOrNot(this)"><?php echo $v['is_top'] ? '取消' : ''; ?>置顶</a>

            </td>
            <td>
                <?php
                if ($v['is_check'] == 0) {
                    echo '<a class="button button-pass" onclick="passOrNot(this)" val="2">通过</a>&emsp;<a class="button button-notpass" onclick="passOrNot(this)"val="1">未通过</a>';
                }else{ ?>
                <a class="button button-<?php echo $v['is_check'] == 1 ? 'pass' : 'notpass' ?>"val="<?php echo $v['is_check'] ?>" onclick='passOrNot(this)'><?php echo '审核'.( $v['is_check'] == 1 ? '' : '未').'通过' ?></a>
                <?php }?>
            </td>
			<td>
				<a class="button button-view unview" onclick='viewComment(this)'>查看评论(<?php echo $v['comment_num']?>)</a>
				<a class="button button-delete" onclick='deleteAtical(this)'>删除</a>
			</td>
		</tr>
    <?php }?>
	</table>
</div>
<div class="center">
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>
