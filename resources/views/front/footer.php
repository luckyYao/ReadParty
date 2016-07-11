	<!-- toast -->
	<div id="toast-box" >
	    <span>操作成功</span>
	</div>
	<a id="editButton" href="/book/add"></a>
    <!--拾光js-->
    <script type="text/javascript" src="http://source.timepicker.cn/static/js/ptime_v0.4.min.js"></script>
    <!-- 分享 -->
	<div id="share-box">
	       <div id="share-top-box">
	            <h4>分享至：</h4>   
	            <div class="bdsharebuttonbox">
	                <a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a>
	                <a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
	                <a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
	            </div>
	       </div>
	</div>
	<?php if(getUserAgent()=='other'):?>
	    <script type="text/javascript">
	        var img_url = '';
	        if ($('meta[name="img"]')[0].content) {
	            img_url = $('meta[name="img"]')[0].content;
	        }else{
	            img_url = typeof($('img')[0])!='undefined'?$('img')[0].src:$('link[rel="shortcut icon"]')[0].href;
	        };
	        window._bd_share_config = {
	            common : {
	                bdText : $('meta[name="title"]')[0].content, 
	                bdDesc : $('meta[name="description"]')[0].content, 
	                bdUrl  : window.location.href,   
	                bdPic  : img_url
	            },
	            share : [{
	                "bdSize" : 24
	            }]
	        }
	        with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
	    </script>
	<?php endif?>
    <script type="text/javascript">
    	function commentRelate(object_id,comment_id,user_name){

			$("#commentUserName_"+object_id).html(user_name);
			$("#replayBox_"+object_id).css('display','inline-block');
			$("#commentContent_"+object_id).focus();
			$("#commentSubmit_"+object_id ).attr('onclick',"return comment('"+object_id+"','"+comment_id+"')");
		}
		function commentCancle(object_id){
			$("#replayBox_"+object_id).css('display','none');
			$("#commentSubmit").attr('onclick',"return comment('"+object_id+"')");
		}
		function starCreate(object_id){
			var data = {object_id:object_id};
			$.ajax({
				url:'/star',
				type:'post',
				dataType:'json',
				data:data,
				success:function(data){
					toast(data.message);
					if (!data.error) {
						if (!data.result.is_login) {
							window.location.href = "<?=handleUrl('/news',true)?>";
						}else{
							$("#like_"+object_id).html(parseInt($("#like_"+object_id).html())+1);
						}
					};
				},
				error:function(data){
					toast(data.message);
				}
			})
		}
		function comment(object_id,comment_id){
			toast('提交...');
			if (comment_id==0) {
				var data = {
					content   :  $('#commentContent_'+object_id).val(),
					object_id :  object_id
				};
			}else{
				var data = {
					content   :  $('#commentContent_'+object_id).val(),
					object_id :  object_id,
					replied_comment_id : comment_id
				};
			};
			$.ajax({
				url:'/comment',
				type:'post',
				dataType:'json',
				data:data,
				success:function(data){
					toast(data.message);
					if (!data.result.is_login) {
						window.location.href = "<?=handleUrl('/news',true)?>";
					};
					// window.location.reload();
				},
				error:function(data){
					toast(data.message);
				}
			})
			return false;
		}
    </script>
</body>
</html>