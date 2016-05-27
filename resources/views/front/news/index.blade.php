<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
    // var_dump($result);exit();
?>
@include('front.common.header')
<section class="padding" style="background:#fff">
	<form class="commentBox" style="margin-bottom:0;">
		<textarea rows="4" placeholder="读书+分享，简直美极了"></textarea>
		<input type="submit" value="发布" class="right" style="font-size:12px;padding:0 15px;line-height:24px;">
	</form>
</section>
<section>
	<?php foreach ($result as $key => $value) :?>
		<div class="item" style="padding-bottom:10px;">
	    	<div class="newsItem">
	    		<img class="userIcon" src="<?=$value->user_pic?>" >
				<span><?=$value->user_name?></span>
				<span style="font-size:12px;"><?=$value->create_at?></span>
		    	<p style="margin:5px 0"><?=$value->content?></p>
		    	<img class="bookCover" src="<?=$value->pic?>">
		    	<span class="right">+喜欢<?=$value->like?></span>
		    	<span class="right highLight">+评论<?=$value->comments?></span>
	    	</div>
	    	<div class="commentList clear">
	    		<hr>
				<span style="font-size:12px;">要笑娟</span>
				<span style="font-size:12px;">2016-05-03  14:00</span>

				<p style="margin:5px 0">你好，你你好，你你好，你你好，你你好，你你好，你你好，你你好，你你好，你你好，你好</p>
		    	<p class="clear highLight">共2条回复</p>
		    	<hr>
				<p>lucky:hah a</p>
				<p>lucky:hah a</p>
	    	</div>
	    	<form class="commentBox">
				<input type="text" name="content" class="left">
				<input type="submit" value="ok" class="left">
			</form>
	    </div>
	<?php endforeach?>

</section>
@include('front.common.footer')