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
		    	<p style="margin:5px 0"><?=$value->content?></p>
		    	<!-- <img class="bookCover" src="<?=$value->pic?>"> -->
		    	<span class="right">+喜欢<?=$value->like?></span>
		    	<span class="right highLight">+评论<?=$value->comments?></span>
	    	</div>
<!-- 	    	<div class="commentList clear">
	    		<hr>
				<span style="font-size:12px;">要笑娟</span>
				<span style="font-size:12px;">2016-05-03  14:00</span>

				<p style="margin:5px 0">你好，你你好，你你好，你你好，你你好，你你好，你你好，你你好，你你好，你你好，你好</p>
		    	<p class="clear highLight">共2条回复</p>
		    	<hr>
				<p>lucky:hah a</p>
				<p>lucky:hah a</p>
	    	</div> -->
	    </div>
		<?php endforeach?>
		<?php endif?>		
	</div>
</section>
<?php include("../resources/views/front/footer.php");?>