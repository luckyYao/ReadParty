<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
    // var_dump($result);exit();
?>
<?php include("../resources/views/front/header.php");?>
<section class="bookInfo">
	<p class="ownerWords"><?=$result->words?></p>
    <p class="ownerName">主人：<?=$result->user_name?></p>
    <img class="bookCover"  src="<?=$result->book_img?>">
    <a class="bookAction right" href="https://book.douban.com/subject_search?search_text=<?=$result->isbn?>&cat=1001">+豆瓣书评</a>
    <div class="bookValue clear">本书已累计借阅<?=$result->times?>次</div>
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
            default:
              $state = "读过";
            }
        ?>
		<div class="timeLineItem">
			<div class="left time">
    			<p class="day"><?=$day?></p>
    			<p class="hour"><?=$time?></p>
    		</div>
    		<div class="left userIcon"><img src="<?=$value->user_pic?>"></div>
            <div class="leftLine right">
                <div class=" userState">
                    <div class="userInfo"><span class="userName">[<?=$state?>]<?=$value->user_name?>&nbsp;</span></div>
                    <p class="ownerWords"><?=$value->words?></p>
                </div>
            </div>
    		
		</div>
        <?php endforeach?>
    </div>
</section>
<!-- 课表详情对话框 -->
<div class="shadow" id="dialogue">
    <div class="shadowContent">
        <h4 class="mcolor">您好，<?=$result->user_current?></h4>
        <p id="state">您即将借阅书籍《<?=$result->book_name?>》，请认真阅读以下内容：</p>
        <p id="attention">
        1.读书顺序按时间轴排序，请您耐心等待哦。</br>
        2.确定借书后，您将会加入到拾光团队“readParty·<?=$result->book_name?>”，所有借本书的人都会在里面，供大家交流使用。</br>
        3有关本书的任何疑问，请到拾光团队“readParty·<?=$result->book_name?>”交流。
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
        $.ajax({
            url:"/borrow/"+id+"/timeline",
            type:"post",
            async:false,
            data:data,
            dataType:'json',
            success:function(data){
                toast(data.message);
                if (!data.error) {
                    window.location.reload();
                }else{
                    $("#dialogueForm").hide();
                };
            },
            error:function(data){
                toast(data.message);
            }
        })
    }
</script>