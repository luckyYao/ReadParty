<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
?>
<?php include("../resources/views/front/header.php");?>
<?php if(!empty($_SESSION['token'])):?>
<section class="padding" style="background:#fff">
	<div class="commentBox" style="margin-bottom:0;">
		<textarea rows="4" placeholder="读书+分享，简直美极了" name="content"></textarea>
		<input type="submit" value="发布" class="right" onclick="addNews()"  style="position:static;font-size:14px;float:right;line-height:24px;">
	</div>
</section>
<?php endif?>
<section style="padding-bottom:25px;">
	<?php if(!empty($result)):?>
		<?php foreach ($result as $key => $value) :?>
			<div class="item" style="padding-bottom:10px;">
		    	<div class="newsItem">
		    		<img class="userIcon" src="<?='http://timepicker.cn/'.$value->user_pic?>" >
					<span><?=$value->user_name?></span>
					<span style="font-size:12px;"><?=$value->create_at?></span>
			    	<p style="margin:5px 0;color:#333"><?=$value->content?></p>
			    	<!-- <img class="bookCover" src="<?=$value->pic?>"> -->
			    	<span class="right like <?=$value->commentsCount->is_stared?'like-active':'like-normal'?>" style="cursor:pointer;display:inline-block;padding-left:18px;" onclick="starCreate('<?=$value->id?>')"><span id="like_<?=$value->id?>"><?=$value->commentsCount->star_count?></span></span>
			    	<span class="right highLight">+评论<?=$value->commentsCount->comment_count?></span>
		    	</div>
		    	<?php if (count($value->comments)):?>
				<div class="commentList clear">
		    		<hr>
		    		<?php foreach ($value->comments as $key => $value2) :?>
					<p style="margin:5px 0;color:#666"><span class="mcolor">
						<?=$value2->user_name?></span>
						<?php if(!empty($value2->replied_user_name)):?>
						<?='回复&nbsp;<span class="mcolor">'.$value2->replied_user_name.'</span>：'.$value2->content?>
						<?php else:?>
						<?='：'.$value2->content?>
						<span class="mcolor right" style="cursor:pointer" onclick="commentRelate('<?=$value->id?>','<?=$value2->id?>','<?=$value2->user_name?>')" >&nbsp;&nbsp;回复</span>
						<?php endif?>
					</p>
					<?php endforeach?>
		    	</div>
		    	<?php endif?>
		    	<span class="hidden" style="padding: 5px;margin: 0px 5px 5px 0;font-size: 12px;background:#ececec;" id="replayBox_<?=$value->id?>" onclick="commentCancle('<?=$value->id?>')">回复<span id="commentUserName_<?=$value->id?>"></span>&nbsp;&nbsp;X</span>
		    	<form class="commentBox" style="margin-top:0">
					<input type="text" name="content" class="left" id="commentContent_<?=$value->id?>">
					<input type="submit" value="OK" class="left" id="commentSubmit" onclick="return comment('<?=$value->id?>',0)">
				</form>
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
    <script type="text/javascript">
		function addNews () {
			if ($('textarea[name="content"]').val()=='') {
				toast("内容不能为空")
			}else{
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
			};
		}
	</script>