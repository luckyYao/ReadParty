<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
     // var_dump($result);exit();
    
?>
<?php include("../resources/views/front/header.php");?>
<style type="text/css">
	body{
    	box-shadow: none;
    	background: #fff;
	}
</style>
<section>
	<div class="step" id="stepChoose">
		<h1>请选择要执行的操作</h1>
		<div class="current option" name="borrow">
			<h2>1.分享书</h2>
			<p>好书与其躺在书架上，</br>不如拿出来和大家一起分享</p>
		</div>
		<div class="option" name="help">
			<h2>2.求帮忙</h2>
			<p>想看的书在派对上没找到？</br>发布借书申请，大家一起帮你找书</p>
		</div>
		<div class="option" name="chat">
			<h2>3.聊心情</h2>
			<p>以书会友，总能找到志同道合的朋友~</br>随便聊，但不可放肆哦，派对保安一直在巡逻哦！</p>
		</div>
		<div class="step-button" onclick="step('stepIsbn')">
			<img src="http://o859gakxp.bkt.clouddn.com/static/img/readParty.png?imageView/2/w/60">
			<span>下一步</span>
		</div>
	</div>
	<div class="step" id="stepIsbn">
		<div>
			<h1>请输入书籍的&nbsp;isbn&nbsp;号</h1>
			<input type="text" name="isbn" oninput="getBookInfo()">
			<p class="tip">TIps：书籍的&nbsp;ISBN&nbsp;号在书籍背面二维码上方</p>
			<?php 
				$agent = getUserAgent();
				if ($agent=='other'):
			?>
			<p class="tip mcolor" onclick="codeScan">你也可以：扫描书籍条形码获取isbn号</p>
			<?php endif?>
		</div>
		<div class="bookInfo">
			
		</div>
		<div class="step-button" onclick="step('stepWords')">
			<img src="http://o859gakxp.bkt.clouddn.com/static/img/readParty.png?imageView/2/w/60">
			<span>下一步</span>
		</div>
		<div class="reback" onclick="step('stepChoose')">←</div>
	</div>
	<div class="step" id="stepWords" >
		<h1>说点什么给看到这本书的人吧</h1>
		<textarea  name="words" rows="4" placeholder="你好，陌生人，我最喜欢的书分享给你，请认真阅读哦"></textarea>
		<p class="tip">TIps：书籍的&nbsp;ISBN&nbsp;号在书籍背面二维码上方</p>
		<div class="step-button" onclick="showDialogue()">
			<img src="http://o859gakxp.bkt.clouddn.com/static/img/readParty.png?imageView/2/w/60">
			<span>好了</span>
		</div>
		<div class="reback" onclick="step('stepIsbn')">←</div>
	</div>
</section>
<!-- 课表详情对话框 -->
<div class="shadow" id="dialogue">
	<div class="shadowContent">
		<h4 class="mcolor">您好，<?=$user_name?></h4>
		<p id="state"></p>
		<p id="attention">
		</p>
		<h4 class="btn"><span onclick="hide('dialogue')" id="close"></span><span id="go" class="mcolor" onclick="addBook()">好的，了解</span></h4>
	</div>
</div>
<?php include("../resources/views/front/footer.php");?>
<script type="text/javascript">
    function kset(key,value){window.localStorage.setItem(key,value);}
    function kget(key){return window.localStorage.getItem(key);}
    function kremove(key){window.localStorage.removeItem(key);}
	$("#editButton").hide();	
	function step(id){
		if(kget('option')!='chat'){
			if (id=='stepWords'&& kget('isbn')==null) {
				$(".tip").html('Tips：请输入书籍的isbn号');
	           	$(".tip").addClass("error");
			}else{
				$("#"+id).siblings().hide();
				$("#"+id).show();
			};
		}else{
			window.location.href = 'http://127.0.0.1:85/news';
		};
	}
	function getBookInfo(){
		var isbn = $("input[name='isbn']").val();
		if (isbn.length==13) {
			$("input[name='isbn']").blur();
			$.ajax({
				url:"/douban",
	            type:"post",
	            data:{isbn:isbn},
	            async:false,
	            dataType:'json',
	            success:function(data){
	            	if (!data.error) {
	            		if (data.result.id) {
	            			kset('isbn',data.result.isbn13);
	            			kset('book_name',data.result.title);
	            			var tpl = '<h3>是这本书吗？</h3>\
										<img class="left" src="'+data.result.image+'">\
										<p class="right">作者: '+data.result.author+'</br>\
										译者：'+data.result.translator+'</br>\
										出版社: '+data.result.publisher+'</br>\
										出版年: '+data.result.pubdate+'</br>\
										页数: '+data.result.pages+'</br>\
										定价: '+data.result.price+'</br>\
										装帧: '+data.result.binding+'</br>\
										</p>';
							$('.bookInfo').append(tpl);
							$('.bookInfo').show();
							$(".tip").html('Tips：找到啦~');
	            			$(".tip").removeClass("error");
	            		}else{
	            			$(".tip").html('Tips：书籍没有找到，检查isbn号是否有误');
	            			$(".tip").addClass("error");
	            		};
	            	}else{
	            		$(".tip").html('Tips：'+data.message);
	            		$(".tip").addClass("error");
	            	};
	            },
	            error:function(data){       
	            	$(".tip").html('Tips：'+data.message);
	            	$(".tip").addClass("error");
	            }
			});
		};
	}
	$('.option').on('click',function(){
		$(this).addClass('current');
		$(this).siblings().removeClass('current');
		kset('option',$(this).attr('name'));
	})
	function addBook(){
		var data = {
			'isbn':kget('isbn'),
			'words':$('textarea[name="words"]').val()
		};
		$.ajax({
			url:"/book/"+kget('option'),
	        type:"post",
	        data:data,
	        async:false,
	        dataType:'json',
	        success:function(data){
	        	if (!data.error) {
	        		var r=confirm(data.message+"去看看？");
					if (r==true){
						window.location.href=data.result.url;
					}
	        	}else{
	        		var r=confirm(data.message+"去看看？");
					if (r==true){
						window.location.href=data.result.url;
					}else{
						
					}
	        	};
	        },
	        error:function(data){     
	        	toast(data.message);
	        }
		});
	}
	function showDialogue(){
		$("#state").html('');
		$("#attention").html('');
		$("#close").html('');
		$("#go").html('');
		
		var close = '算了，不分享了';
		var go = '好的，了解';
		$("#close").append(close);
		$("#go").append(go);
		if (kget('option')=='borrow') {
			var state = '您即将分享书籍《'+kget('book_name')+'》，请认真阅读以下内容:';
			var attention = '1.您的书籍在阅读派对上至少要漂流3个月哦，3个月后您可以随时收回您的书。</br>\
							 2.点击确定后派对将会帮你创建一个“readParty·'+kget('book_name')+'”的拾光团队，以后借在这本书的用户会自动加入到这个团队，方便你们联系。</br>';
			
			$("#state").append(state);
			$("#attention").append(attention);
		}else{
			var state = '您即将申请借阅书籍《'+kget('book_name')+'》，请认真阅读以下内容:';
			var attention = '1.您的<span class="mcolor">帮忙借书</span>的人越多，借到的可能越大哦~</br>\
							 2.申请成功后可以分享给好友让他们<span class="mcolor">帮忙借书</span>，超过30人帮忙还没有人借给你书的话，阅读派对会买一本《'+kget('book_name')+'》借给你哦';
			$("#state").append(state);
			$("#attention").append(attention);
		};
		$("#dialogue").show();
	}

	// 一维码/二维码扫描
	function codeScan () {
		var payload = {
			type: 'CodeScan',
			content: {
			}
		};
		window.webkit.messageHandlers.webViewApp.postMessage(payload); //ios
	}

	// 一维码/二维码扫描结果
	function codeScanResult (result) {
		console.log(result);
		$("input[name='isbn']").val(result);
		getBookInfo();
	}
</script>