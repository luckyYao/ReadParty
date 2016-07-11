<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读派对";
    $description  = "你带着书，书带着你，开启图书漂流之旅";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读派对"; 
?>
<?php include("../resources/views/front/header.php");?>
<section class="padding tabs" style="background:#fff">
    <div class="label-box">
	  <a class="<?=$result['type']=='borrow'?'label big current':'label big'?>" href="/">借本书</a>
	  <span class="label middle">or</span>
	  <a class="<?=$result['type']=='help'?'label big mcolor-help-bg':'label big'?>" href="/help">帮帮忙</a>
	</div>
	<?php if(!empty($result['borrow'])):?>
    <form class="searchBox" action="/search" method="post">
        <input type="text" name="input" id="searchContent" class="search" value="<?=!empty($result['input'])?$result['input']:''?>" placeholder="search&nbsp;<?=count($result['borrow'])?>&nbsp;books" onkeypress="if(event.keyCode==13) {btn.click();return false;}">
    	<input type="submit" name="btn" id="btn" value="提交" style="display:none"/>
    </form>
	<?php endif?>
</section>
<section>
	<!-- 书籍列表 -->
	<?php if(!empty($result['borrow'])):?>
		<?php foreach ($result['borrow'] as $key => $value) :?>
	    <div class="item">
		  	<a href="/borrow/<?=$value->id?>">
			    <img src="<?=$value->book_img?>" class="block">
			    <h3><?=$value->book_name?></h3>
			    <p><?=$value->words?></p>
			    <p>主人：<?=$value->user_name?></p>
		  	</a>
		  	<hr>
		  	<span class="label bg-mcolor">可借</span>
		  	<?php foreach ($value->tags as $key => $value):?>
		  	<form action="/book-tag" style="display:inline-block" method="post">
		  		<input name='tag' type="text" class="hidden" value="<?=$value->name?>">
		  		<input name='type' type="text" class="hidden" value="borrow" style="width:0">
				<input class="label" type="submit" value="<?=$value->name?>">
		  	</form>
			<?php endforeach?>
		</div>
		<?php endforeach?>
	<?php elseif(!empty($result['help'])):?>
		<?php foreach ($result['help'] as $key => $value) :?>
	    <div class="item">
		  	<a href="/help/<?=$value->id?>">
			    <img src="<?=$value->book_img?>" class="block">
			    <h3 class="mcolor-help"><?=$value->book_name?></h3>
			    <p><?=$value->words?></p>
			    <p>主人：<?=$value->user_name?></p>
		  	</a>
		  	<hr>
		  	<span class="label mcolor-help-bg"><?=$value->is_done?'已借到':'求助'?></span>
		  	<?php foreach ($value->tags as $key => $value):?>
		  	<form action="/book-tag" style="display:inline-block" method="post">
		  		<input name='tag' type="text" class="hidden" value="<?=$value->name?>">
		  		<input name='type' type="text" class="hidden" value="help" style="width:0">

				<input class="label" type="submit" value="<?=$value->name?>">
		  	</form>
			<?php endforeach?>
		</div>
		<?php endforeach?>
	<?php else:?>
	<section class="emptyPage">
		<img src="http://o859gakxp.bkt.clouddn.com/static/img/readParty.png?imageView/2/w/120">
		<h3 class="mcolor">数据为空，快抢沙发吧</h3>
	</section>
	<?php endif?>
</section>
<?php include("../resources/views/front/footer.php");?>
