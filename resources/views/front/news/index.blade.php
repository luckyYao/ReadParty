<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
    // var_dump($result);exit();
?>
<?php include("../resources/views/front/header.php");?>
<?php if(!empty($_SESSION['token'])):?>
<section class="padding" style="background:#fff">
	<div class="commentBox" style="margin-bottom:0;">
		<textarea rows="4" placeholder="读书+分享，简直美极了" name="content"></textarea>
		<input type="submit" value="发布" class="right" onclick="addNews()"  style="font-size:12px;padding:0 15px;line-height:24px;">
	</div>
</section>
<?php endif?>
<section>
	<?php foreach ($result as $key => $value) :?>
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
	    	<form class="commentBox">
				<input type="text" name="content" class="left">
				<input type="submit" value="ok" class="left">
			</form>
	    </div>
	<?php endforeach?>
</section>
<?php include("../resources/views/front/footer.php");?>
<script type="text/javascript">
	function addNews () {
		toast('提交...');
		$.ajax({
			url:'/news',
			type:'post',
			dataType:'json',
			data:{content:$('textarea[name="content"]').val()},
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