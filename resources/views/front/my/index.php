<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
    // var_dump($result);exit();
?>
<?php include("../resources/views/front/header.php");?>
<section class="padding tabs" style="background:#fff">
	<?php if(!$result['user']):?>
		<style type="text/css">
			body{
				padding-bottom: 0;
		    	box-shadow: none;
			}
		</style>
		<img style="width:100px;margin:80px auto 20px;display:block;" src="http://o859gakxp.bkt.clouddn.com/static/img/readParty.png?imageView/2/w/200">
		<p class="mcolor" style="width:200px;margin:5px auto;">亲爱的游客:</p>
		<p style="width:200px;margin:5px auto;">
			您好,欢迎来到阅读派对！</br>
			请您登陆获取派对邀请卡以享受图书漂流之旅~
		</p>
		<p class="mcolor" style="text-align:right;width:200px;margin:5px auto">来自：阅读派对</p>
		<a style="display:block;width:200px;height:30px;text-align:center;line-height:30px;background:#76862e;color:#fff;margin:20px auto;" href="<?=handleUrl($_SERVER['REQUEST_URI'],true)?>">立即登陆</a>
	<?php else:?>
		<div class="label-box">
		  <a class="label big current" href="/myBook">我的书架</a>
		  <span class="label middle">or</span>
		  <a class="label big" href="/myNews">我的消息</a>
		</div>
		<div>
			<span class="label current" name="all">全部</span>
			<span class="label" name="book_mine">借出</span>
			<!-- <span class="label">送人</span> -->
			<span class="label" name="book_borrow">借入</span>
			<span class="label" name="book_help">求帮忙</span>
		</div>
	<?php endif?>
</section>
<section>
	<!-- 借出的书籍 -->
	<div id="book_mine"> 
		<?php if(!empty($result['book_mine'])):?>
		<?php foreach ($result['book_mine'] as $key => $value) :?>
	    <div class="item">
		  	<a href="/borrow/<?=$value->id?>">
			    <img src="<?=$value->book_img?>" class="block">
			    <h3><?=$value->book_name?></h3>
			    <?php if(!empty($value->reader_current)) :?>
				    <?php if(count($value->reader_current)==2):?>
				    	<p>漂流中</br>
						from：<?=$value->reader_current[0]->user_name?>&nbsp;&nbsp;&nbsp;<?=$value->reader_current[0]->user_phone?></br>
						to：<?=$value->reader_current[1]->user_name?>&nbsp;&nbsp;&nbsp;<?=$value->reader_current[1]->user_phone?>
					    </p>
					<?php else:?>
						<p>当前位置</br>
						<?=$value->reader_current[0]->user_name?>&nbsp;&nbsp;&nbsp;<?=$value->reader_current[0]->user_phone?>
					    </p>
					<?php endif?>
				<?php else:?>
				<p>还没有人借阅此书哦</p>
				<?php endif?>
		  	</a>
		  	<hr>
		  	<span class="label bg-mcolor">借出</span>
		  	<span class="label">等待<?=count($value->reader_next)?>人</span>
		</div>
		<?php endforeach?>
		<?php endif?>		
	</div>
	<!-- 借入的书籍 -->
	<div id="book_borrow">
		<?php if(!empty($result['book_borrow'])):?>
		<?php foreach ($result['book_borrow'] as $key => $value) :?>
	    <div class="item" style="background:<?=in_array($value->state,[1,2,3])?'#ececec':'#fff'?>">
		  	<a href="/borrow/<?=$value->id?>">
			    <img src="<?=$value->book_img?>" class="block">
			    <h3 ><?=$value->book_name?></h3>
			    <?php if(count($value->reader_current)==2):?>
			    	<p>漂流中</br>
					from：<?=$value->reader_current[0]->user_name?>&nbsp;&nbsp;&nbsp;<?=$value->reader_current[0]->user_phone?></br>
					to：<?=$value->reader_current[1]->user_name?>&nbsp;&nbsp;&nbsp;<?=$value->reader_current[1]->user_phone?>
				    </p>
				<?php else:?>
					<p>当前位置</br>
					<?=$value->reader_current[0]->user_name?>&nbsp;&nbsp;&nbsp;<?=$value->reader_current[0]->user_phone?>
				    </p>
				<?php endif?>
		  	</a>
		  	<hr>
		  	<span class="label bg-mcolor">借入</span>
		  	<?php 
	            switch ($value->state)
	            {
	            case '0':
	              $state = "想读";
	              break;  
	            case '1':
	              $state = "在读";
	              break;
	            case '2':
	              $state = "漂流(from)";
	              break;
	            case '3':
	              $state = "漂流(to)";
	              break;
	            default:
	              $state = "读过";
	            }
	        ?>
		  	<span class="label bg-mcolor"><?=$state?></span>
			<?php if($value->state=='1'):?>
			<span class="button mcolor mid  right" onclick="showDialogue('<?=$value->id?>','<?=!empty($value->reader_next)?$value->reader_next[0]->user_id:"0"?>',1)">开始漂流</span>
			<?php elseif($value->state=='3'):?>
			<span class="button mcolor mid  right" onclick="showDialogue('<?=$value->id?>','0',3)">接收</span>
			<span class="button mcolor mid  right" onclick="showDialogue('<?=$value->id?>','0',2)">过了</span>
			<?php endif?>
		</div>
		<?php endforeach?>
		<!-- confirm -->
		<div class="shadow" id="dialogue">
		    <div class="shadowContent">
		        <h4 class="mcolor">您好，<?=$result['user']['user_name']?></h4>
		        <p id="state"></p>
		        <p id="attention"></p>
		        <h4 class="btn"><span onclick="hide('dialogue')" id="close">真麻烦，算了</span></h4>
		    </div>
		</div>
		<?php endif?>
	</div>
	<!-- 求帮忙的书籍 -->
	<div id="book_help">
		<?php if(!empty($result['book_help'])):?>
		<?php foreach ($result['book_help'] as $key => $value) :?>
	    <div class="item ">
		  	<a href="/help/<?=$value->id?>">
			    <img src="<?=$value->book_img?>" class="block">
			    <h3 class="mcolor-help"><?=$value->book_name?></h3>
			    <p><?=$value->is_done?'借书者</br>'.$value->helper_name.'&nbsp;&nbsp;'.$value->helper_phone:$value->words?></p>
		  	</a>
		  	<hr>
		  	<?php if($result['user']['user_id']!=$value->user_id):?>
		  	<span class="label mcolor-help-bg" >助人为乐</span>
		  	<?php else:?>
		  	<span class="label mcolor-help-bg" ><?=$value->is_done?'已借到':'求帮忙'?></span>
		  	<?php endif?>
		</div>
		<?php endforeach?>
		<?php endif?>
	</div>
</section>
<?php include("../resources/views/front/footer.php");?>
<script type="text/javascript">
	$(".label").on('click',function(){
		$(this).siblings().removeClass('current');
		$(this).addClass('current');
		if ($(this).attr('name')=='all') {
			$("#book_mine").show();
			$("#book_mine").siblings().show();
		}else{
			$("#"+$(this).attr('name')).siblings().hide();
			$("#"+$(this).attr('name')).show();
		};
	})
	function showDialogue(borrow_id,next_id,type){
		if (type==1) {
			var sql_state = '您即将开始图书漂流，请认真阅读以下内容：';
			var sql_attention = '1.请确认您的书籍已经读完。</br>\
						        2.图书漂流期间您依然要对图书的安全负责哦。</br>\
						        3.图书漂流到下一为读者手中的时候，请您尽快联系下一位读者转交图书</br>\
						        4.图书成功漂流后，请嘱托对方将阅读状态改为“在读”，图书安全就交给对方负责喽';
			var sql = '<span id="go" class="mcolor" onclick="changeSatate('+borrow_id+','+next_id+','+type+')">好的，了解</span>';
			$("#state").html(sql_state);
			$("#attention").html(sql_attention);
			$("#dialogue .btn").append(sql);
			$("#dialogue").show();
		}else if(type==2){
			var sql_state = '您即将错过阅读这本书哦：';
			var sql_attention = '1.确认后您将排在时间轴的最后面重新等待阅读';
			var sql = '<span id="go" class="mcolor" onclick="changeSatate('+borrow_id+','+next_id+','+type+')">好的，了解</span>';
			$("#state").html(sql_state);
			$("#attention").html(sql_attention);
			$("#dialogue .btn").append(sql);
			$("#dialogue").show();
		}else{
			var sql_state = '您即将结束这本书的漂流，请认真阅读以下内容：';
			var sql_attention = '1.确认您已经拿到这本书，如果没有请打电话联系上一本读者拿书。</br>\
								 2.在您阅读期间请对这本书的安全负责。';
			var sql = '<span id="go" class="mcolor" onclick="changeSatate('+borrow_id+','+next_id+','+type+')">好的，了解</span>';
			$("#state").html(sql_state);
			$("#attention").html(sql_attention);
			$("#dialogue .btn").append(sql);
			$("#dialogue").show();
		};
	}
	function changeSatate(borrow_id,next_id,type){
		console.log(type);
		hide('dialogue');
		$.ajax({
			url:'/state',
			type:'post',
			data:{'borrow_id':borrow_id,'next_id':next_id,'type':type},
			dataType:'json',
			success:function(data){
				toast(data.message);
				if (!data.error) window.location.reload();
			},
			error:function(data){
				toast(data.message);
			}
		})
	}
</script>
<div style="height:40px;"></div>