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
			    <?php if(!empty($value->reading_name)) :?>
			    <p>当前借阅:</br>
				<?=$value->reading_name?>&nbsp;&nbsp;&nbsp;<?=$value->reading_phone?>
			    </p>
				<?php else:?>
				<p>还没有人借阅此书哦</p>
				<?php endif?>
		  	</a>
		  	<hr>
		  	<span class="label bg-mcolor">借出</span>
		  	<span class="label">已读<?=$value->read_done?>人</span>
		  	<span class="label">想读<?=$value->read_todo?>人</span>
		</div>
		<?php endforeach?>
		<?php endif?>		
	</div>
	<!-- 借入的书籍 -->
	<div id="book_borrow">
		<?php if(!empty($result['book_borrow'])):?>
		<?php foreach ($result['book_borrow'] as $key => $value) :?>
	    <div class="item" style="background:<?=$value->state=='1'?'#ececec':'#fff'?>">
		  	<a href="/borrow/<?=$value->id?>">
			    <img src="<?=$value->book_img?>" class="block">
			    <h3 style="color:#7d2a42 !important;"><?=$value->book_name?></h3>
			    <?php if($value->state=='1'):?>
				    <?php if($value->read_todo>0)?>
				    <p>下一个读者：</br>
					<?=$value->readnext_name?>&nbsp;&nbsp;&nbsp;<?=$value->readnext_phone?>
					</p>
					<?php else:?>
					<p>在您之后没有人排队</p>
					<?php endif?>
				<?php else:?>
				<p>当前借阅:</br>
				<?=$value->reading_name?>&nbsp;&nbsp;&nbsp;<?=$value->reading_phone?>
			    </p>
				<?php endif ?>
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
	            default:
	              $state = "读过";
	            }
	        ?>
		  	<span class="label bg-mcolor"><?=$state?></span>
			<?php if($value->state=='1'):?>
			<span class="button mcolor mid  right" >切换状态</span>
			<?php endif?>
		</div>
		<?php endforeach?>
		<?php endif?>
	</div>
	<!-- 求帮忙的书籍 -->
	<div id="book_help">
		<?php if(!empty($result['book_help'])):?>
		<?php foreach ($result['book_help'] as $key => $value) :?>
	    <div class="item ">
		  	<a href="/help/<?=$value->id?>">
			    <img src="<?=$value->book_img?>" class="block">
			    <h3 ><?=$value->book_name?></h3>
			    <p><?=$value->words?></p>
		  	</a>
		  	<hr>
		  	<span class="label bg-mcolor" >求帮忙</span>
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
</script>