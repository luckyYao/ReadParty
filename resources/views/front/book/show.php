<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读派对";
    $description  = "你带着书，书带着你，开启图书漂流之旅";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读派对";
    // var_dump($result);exit();
?>
<?php include("../resources/views/front/header.php");?>
<style type="text/css">
    body{
        background: #fff;
    }
</style>
<section class="bookInfo">
	<p class="<?= empty($result->timeline)?'ownerWords mcolor-help':'ownerWords'?>"><?=$result->words?></p>
    <p class="<?= empty($result->timeline)?'ownerName mcolor-help':'ownerName'?>">主人：<?=$result->user_name?></p>
    <img class="bookCover"  src="<?=$result->book_img?>">
    <a class="<?= empty($result->timeline)?'bookAction right mcolor-help':'bookAction right '?>" href="https://book.douban.com/subject_search?search_text=<?=$result->isbn?>&cat=1001">+豆瓣书评</a>
    <?php if(!empty($result->timeline)):?>    
        <div class="<?= empty($result->timeline)?'bookValue clear mcolor-help-bg':'bookValue clear'?>">本书已累计借阅<?=count($result->timeline)?>次</div>
        <div class="timeLine">
            <div class="timeLineItem">
                <div class="left time">
                    <p class="day"><?=date("Y-m-d")?></p>
                    <p class="hour"><?=date("h:i")?></p>
                </div>
                <div class="left userIcon"><img src="/img/plus.png" style="width: 20px;margin: 5px;"></div>
                <div class="leftLine right">
                    <a class="userState" id="addTimeline" href="<?=handleUrl('/borrow/'.$result->id,true)?>" style="display:block" onclick="return showDialogue('<?=$result->id?>')">
                        <div class="userInfo"><span class="userName">点击借阅+</span></div>
                        <p class="ownerWords">开启图书漂流之旅</p>
                    </a>
                </div>
            </div>
            <?php foreach ($result->timeline as $key => $value) :?>
            <?php 
                $day = substr($value->create_at,0,10); 
                $time = substr($value->create_at,11,5); 
                switch ($value->state)
                {
                case '0':
                  $state = "想读";
                  break;  
                case '1':
                  $state = "在读";
                  break;
                case '2':
                  $state = "漂流(from)";
                  break;
                case '3':
                  $state = "漂流(to)";
                  break;
                default:
                  $state = "读过";
                }
            ?>
    		<div class="timeLineItem">
    			<div class="left time">
        			<p class="day"><?=$day?></p>
        			<p class="hour"><?=$time?></p>
        		</div>
        		<div class="left userIcon"><img src="<?='http://timepicker.cn/'.$value->user_pic?>"></div>
                <div class="leftLine right">
                    <div class="<?=in_array($value->state,[1,2,3])?'userState mcolor':'userState'?>">
                        <div class="userInfo"><span class="userName">[<?=$state?>]<?=$value->user_name?>&nbsp;</span></div>
                        <p class="ownerWords"><?=$value->words?><?=$result->is_owner?'tel:'.$value->user_phone:''?> </br></p>
                    </div>
                </div>
    		</div>
            <?php endforeach?>
        </div>
    <?php else:?>
        <?php if(!$result->is_done):?>
        <div class="bookValue clear mcolor-help-bg" onclick="borrow(<?=$result->id?>)">有这本书？借给他</div>
        <?php else:?>
        <div class="bookValue clear mcolor-help-bg">书已经借到，谢谢各位！</div>
        <?php endif?>
        <p>帮忙借书的人越多，借到书的概率越大哦~，目前已有&nbsp;<span class="mcolor-help" id="helperNum" style="font-size:18px"><?=$result->times?></span>&nbsp;人帮他借书了</p>
        <p style="margin: 10px 0 5px;" class="mcolor-help">以下同学正在帮他借书：</p>
        <hr>
        <?php foreach ($result->helpers as $key => $value):?>
        <div class="header-box">
            <img class="header" src="http://timepicker.cn/<?=$value->icon?>?imageView2/2/w/120">
            <span class="name"><?=$value->name?></span>
        </div>
        <?php endforeach?>
        <?php if(!$result->is_done):?>
        <div class="header-box" id="toHelp" onclick="help(<?=$result->id?>)">
            <img class="header" src="/img/plus.png" style="padding: 7px;box-sizing: border-box;box-shadow: 0px 0px 5px #76862e ;">
            <span class="name" >帮助他</span>
        </div>
        <?php endif?>
    <?php endif?>
</section>
<!-- 课表详情对话框 -->
<div class="shadow" id="dialogue">
    <div class="shadowContent">
        <h4 class="mcolor">您好，<?=$result->user_current?></h4>
        <p id="state">您即将借阅书籍《<?=$result->book_name?>》，请认真阅读以下内容：</p>
        <p id="attention">
        1.读书顺序按时间轴排序，请您耐心等待哦。</br>
        2.在个人书架可以看到图书状态哦~</br>
        </p>
        <h4 class="btn"><span onclick="hide('dialogue')" id="close">真麻烦，不借了</span><span id="go" class="mcolor" onclick="showDialogueForm()">好的，了解</span></h4>
    </div>
</div>

<!-- 课表详情对话框 -->
<div class="shadow" id="dialogueForm">
    <div class="shadowContent" style="height:190px;">
        <h4 class="mcolor">在时间轴上留下你心情如何？</h4>
        <textarea  name="words" rows="4" placeholder="我也带着书漂流一下~" style="width: 100%;resize: none;"></textarea>
        <h4 class="btn"><span onclick="hide('dialogue-form')" id="close">真麻烦，不借了</span><span id="go" class="mcolor" onclick="addTimeLine(<?=$result->id?>)">填好了</span></h4>
    </div>
</div>

<?php include("../resources/views/front/footer.php");?>
<script type="text/javascript">
    function showDialogue(id){
        if ($("#addTimeline").attr('href')=='/borrow/'+id) {
            $("#dialogue").show();
            return false;
        }else{
            return true;
        };
    }
    function showDialogueForm(){
        $("#dialogue").hide();
        $("#dialogueForm").show();
    }
    function addTimeLine(id){
        var data = {state:0,words:$('textarea').val()};
        if ($('textarea').val().length==0) {
            toast("请在书籍的时间上留言！")
        }else{
            $.ajax({
                url:"/borrow/"+id+"/timeline",
                type:"post",
                async:false,
                data:data,
                dataType:'json',
                success:function(data){
                    toast(data.message);
                    if (!data.error) {
                        if(data.result==3){
                            var r=confirm("恭喜你，这本书漂流到你这里喽，结束漂流，开始阅读？");
                            if (r==true){
                                window.location.href="/myBook";
                            }else{
                               window.location.reload(); 
                            }
                        }else{
                            window.location.reload();
                        }
                    }else{
                        $("#dialogueForm").hide();
                    };
                },
                error:function(data){
                    toast(data.message);
                }
            })
        };
        
    }
    function help(id){
        $.ajax({
            url:"/help/"+id,
            type:"post",
            async:false,
            success:function(data){
                if (!data.error){
                    $("#toHelp").before('<div class="header-box">\
                        <img class="header" src="http://timepicker.cn/'+data.result.icon+'?imageView2/2/w/120">\
                        <span class="name">'+data.result.name+'</span>\
                    </div>\
                    ');
                    var num = parseInt($("#helperNum").html())+1;
                    $("#helperNum").html(num);
                }else{
                    window.location.href = data.result;
                } 
                toast(data.message);
            },
            error:function(){
                toast(data.message);
            }
        })
    }
    function borrow(id){
        var r=confirm("确定要借书给他？");
        if (r==true){
            $.ajax({
                url:"/borrow/"+id,
                type:"post",
                async:false,
                success:function(data){
                    if (data.error) window.location.href = data.result
                    else window.location.reload();
                    toast(data.message);
                },
                error:function(){
                    toast(data.message);
                }
            })
        }
    }
</script>