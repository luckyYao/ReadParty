<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
	// var_dump($result['news_mine'][0]);exit();
?>
<?php include("../resources/views/front/header.php");?>
<section class="padding tabs" style="background:#fff">
	<?php if(!$result['user']):?>
		<style type="text/css">
			body{
				padding-bottom: 0;
		    	box-shadow: none;
		    	background: #fff;
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
		  <a class="label big " href="/myBook">我的书架</a>
		  <span class="label middle">or</span>
		  <a class="label big current" href="/myNews">我的消息</a>
		</div>
	<?php endif?>
</section>
<section>
	<!-- 我发布的消息 -->
	<div id="news_mine"> 
		<?php if(!empty($result['news_mine'])):?>
		<?php foreach ($result['news_mine'] as $key => $value) :?>
	    <div class="item" style="padding-bottom:10px;">
	    	<div class="newsItem">
	    		<img class="userIcon" src="<?='http://timepicker.cn/'.$value->user_pic?>" >
				<span><?=$value->user_name?></span>
				<span style="font-size:12px;"><?=$value->create_at?></span>
				<span class="right mcolor" onclick="commentDelete('<?=$value->id?>')">删除</span>
		    	<p style="margin:5px 0"><?=$value->content?></p>
		    	<!-- <img class="bookCover" src="<?=$value->pic?>"> -->
		    	<span class="right" onclick="starCreate('<?=$value->id?>')">喜欢&nbsp;<span id="like_<?=$value->id?>"><?=$value->commentsCount->star_count?></span></span>
		    	<span class="right highLight">评论&nbsp;<?=$value->commentsCount->comment_count?></span>
	    	</div>
			<div class="commentList clear">
	    		<hr>
	    		<?php foreach ($value->comments as $key => $value2) :?>
				<p style="margin:5px 0;color:#000"><span style="font-size:12px;" class="mcolor">
					<?=$value2->user_name?></span>
					<?php if(!empty($value2->replied_user_name)):?>
					<?='回复&nbsp;<span class="mcolor">'.$value2->replied_user_name.'</span>：'.$value2->content?>
					<?php else:?>
					<?='：'.$value2->content?>
					<span class="mcolor right" onclick="commentRelate('<?=$value->id?>','<?=$value2->id?>','<?=$value2->user_name?>')" >&nbsp;&nbsp;回复</span>
					<?php endif?>
				</p>
				<?php endforeach?>
	    	</div>
	    	<form class="commentBox">
	    		<span class="hidden" style="padding: 5px;margin: 0px 5px 5px 0;font-size: 12px;background:#ececec;" id="replayBox_<?=$value->id?>" onclick="commentCancle('<?=$value->id?>')">回复<span id="commentUserName_<?=$value->id?>"></span>&nbsp;&nbsp;X</span>
				<input type="text" name="content" class="left" id="commentContent_<?=$value->id?>">
				<input type="submit" value="ok" class="left" id="commentSubmit" onclick="return comment('<?=$value->id?>')">
			</form>
	    </div>
		<?php endforeach?>
		<?php endif?>		
	</div>
</section>
<?php include("../resources/views/front/footer.php");?>
<script type="text/javascript">
	function commentDelete(object_id){
		toast('提交...');
		$.ajax({	
			url:'/news/'+object_id,
			type:'post',
			dataType:'json',
			success:function(data){
				toast(data.message);
				window.location.reload();
			},
			error:function(data){
				toast(data.message);
			}
		})
	}
</script>