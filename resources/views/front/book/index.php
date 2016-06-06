<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
    // var_dump($result);exit();
?>
<?php include("../resources/views/front/header.php");?>
<section class="padding tabs" style="background:#fff">
    <div class="label-box">
	  <a class="<?=$result['type']=='borrow'?'label big current':'label big'?>" href="/">借本书</a>
	  <span class="label middle">or</span>
	  <a class="<?=$result['type']=='help'?'label big current':'label big'?>" href="/help">帮帮忙</a>
	</div>
	<div>
		<span class="<?=$result['tags_current']=='all'?'label current':'label '?>">全部</span>
		<?php foreach ($result['tags'] as $key => $value) :?>
		<form action="/tag/book" style="display:inline-block" method="post">
	  		<input name='tag' type="text" class="hidden" value="<?=$value->name?>">
	  		<input name='type' type="text" class="hidden" value="<?=$result['type']?>">
			<input class="<?=$result['tags_current']==$value->name?'label current':'label '?>" type="submit" value="<?=$value->name?>">
	  	</form>
		<?php endforeach?>
	</div>
    <div class="searchBox">
        <input type="text" class="search">
        <div class="searchNotic ion-ios-search">1222,888&nbsp;books</div>
    </div>
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
	  	<form action="/book" style="display:inline-block" method="post">
	  		<input name='tag' type="text" class="hidden" value="<?=$value->name?>">
			<input class="label" type="submit" value="<?=$value->name?>">
	  	</form>
		<?php endforeach?>
	</div>
	<?php endforeach?>
	<?php endif?>
	<!-- 求帮忙的列表 -->
	<?php if(!empty($result['help'])):?>
	<?php foreach ($result['help'] as $key => $value) :?>
    <div class="item">
	  	<a href="/help/<?=$value->id?>">
		    <img src="<?=$value->book_img?>" class="block">
		    <h3><?=$value->book_name?></h3>
		    <p><?=$value->words?></p>
		    <p>主人：<?=$value->user_name?></p>
	  	</a>
	  	<hr>
	  	<span class="label bg-mcolor2">求助</span>
	  	<?php foreach ($value->tags as $key => $value):?>
	  	<form action="/book" style="display:inline-block" method="post">
	  		<input name='tag' type="text" class="hidden" value="<?=$value->name?>">
			<input class="label" type="submit" value="<?=$value->name?>">
	  	</form>
		<?php endforeach?>
	</div>
	<?php endforeach?>
	<?php endif?>
</section>
<?php include("../resources/views/front/footer.php");?>