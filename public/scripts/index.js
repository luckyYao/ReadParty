function commentRelate(object_id,comment_id,user_name){
	$("#commentUserName").html(user_name);
	$("#replayBox").css('display','inline-block');
	$("#commentContent").focus();
	$("#commentSubmit").attr('onclick',"return comment('"+object_id+"','"+comment_id+"')");
}
function commentCancle(object_id){
	$("#replayBox").css('display','none');
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
			content   :  $('#commentContent').val(),
			object_id :  object_id
		};
	}else{
		var data = {
			content   :  $('#commentContent').val(),
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