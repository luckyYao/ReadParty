<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
	// var_dump($_SESSION);exit();

?>
@include('front.common.header')
<section class="padding tabs" style="background:#fff">
    <div class="label-box">
	  <a class="label big current" href="/book">书架</a>
	  <span class="label middle">or</span>
	  <a class="label big" href="/help">消息</a>
	</div>
	<div>
		<span class="label current">全部</span>
		<span class="label">借出</span>
		<span class="label">送人</span>
		<span class="label">借入</span>
		<span class="label">求帮忙</span>
	</div>
</section>
<section>
	<!-- 书籍列表 -->
    <div class="item">
	  	<a href="/borrow/1">
		    <img src="/img/book.jpg" class="block">
		    <h3>托斯塔纳艳阳下</h3>
		    <p>希望你会喜欢</p>
		    <p>主人：lucky</p>
	  	</a>
	  	<hr>
	</div>
</section>
@include('front.common.footer')