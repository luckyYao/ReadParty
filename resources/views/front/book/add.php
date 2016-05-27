<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
    // var_dump($result);exit();
?>
<?php include("../resources/views/front/common/header.php");?>
<section id="addBook">
	<section class="padding tabs" style="background:#fff">
		<div class="label-box">
		  <span class="label big current" v-on:click = "handleSwitch">分享书</span>
		  <span class="label middle">or</span>
		  <span class="label big" v-on:click = "handleSwitch">求帮忙</span>
		</div>
		<p style="color:#76862e;text-align:center;line-height:24px;" v-if="!is_help">
			好书不应躺在书架上，</br>
			堆在角落里。</br>
			在阅读派对上分享一本书，</br>
			开启他的漂流之旅</br>
			让他成为一本有故事的好书！
		</p>
		<p style="color:#76862e;text-align:center;line-height:24px;" v-if="is_help">
			书非借不能读也，</br>
			发送借书申请</br>
			我们一起找好书
		</p>
	</section>
	<div class="item" style="margin:10px 0;" v-if="ok">
	  	<a href="/borrow/1">
		    <img v-bind:src="bookInfo['image']" class="block">
		    <h3>{{bookInfo['title']}}</h3>
		    <p>{{words}}</p>
		    <p>主人：<?=$result->name?></p>
	  	</a>
	  	<hr>
	  	<form action="/book" style="display:inline-block" method="post" v-for="item in bookInfo['tags']">
	  		<input name='tag' type="text" class="hidden" value="{{item.name}}">
			<input class="label" type="submit" value="{{item.name}}">
	  	</form>
	</div>
	<section class="padding" style="background:#fff;">
		<form class="commentBox" style="margin-bottom:0;width:80%;">
			<h3 style="color:#76862e;text-align:center">step1:输入isbn号</h3>
			<p class="highLight">isbn:书籍封面条形码附近</p>
			<input type="text" placeholder="isbn号" v-model="isbn" v-on:blur = "getBookInfo" style="width:100%;border:1px solid #c2ce8c;box-sizing: border-box;">
			<h3 style="color:#76862e;text-align:center">step2:说点什么吧</h3>
			<textarea rows="4" placeholder="说点什么吧~" v-model="words" ></textarea>
			<input type="submit" value="好了" class="right" style="font-size:12px;padding:0 15px;line-height:24px;margin-bottom:20px;">
		</form>
	</section>
</section>

<?php include("../resources/views/front/common/footer.php");?>
<script type="text/javascript">
	hide("editButton");
	new Vue({
	  	el: '#addBook',
	  	data: {
	  		ok:false,
	    	is_help: false,
	    	isbn:'',
	    	words:'说点什么吧~',
	    	bookInfo:[],  
	  	methods:{
	  		handleSwitch:function (){
	  			this.is_help = !this.is_help;
	  		},
	  		getBookInfo:function(){
	  			if (this.isbn.length==11||this.isbn.length==13) {
	  				var ok='';
	  				var bookInfo=[];
	  				$.ajax({
	  					url:"/douban",
	                    type:"post",
	                    data:{isbn:this.isbn},
	                    async:false,
	                    dataType:'json',
	                    success:function(data){
	                    	if (!data.error) {
	                    		ok = true;
	                    		bookInfo['title'] = data.result.title;
	                    		bookInfo['image'] = data.result.image;
	                    		bookInfo['tags'] = data.result.tags;
	                    	};
	                    },
	                    error:function(data){       
	                    	console.log(data);
	                    }
	  				});
	  				this.ok = ok;
	  				this.bookInfo = bookInfo;
	  				console.log(this.bookInfo);
	  			};
	  		}
	  	}
	})
</script>