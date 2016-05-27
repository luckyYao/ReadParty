<?php 
    $title        = "拾光&nbsp;&bull;&nbsp;阅读";
    $description  = "燕大图书漂流";
    $header       = "拾光&nbsp;&bull;&nbsp;阅读"; 
    // var_dump($result);exit();
?>
@include('front.common.header')
<section class="bookInfo">
	<p class="ownerWords"><?=$result->words?></p>
    <p class="ownerName">主人：<?=$result->user_name?></p>
    <img class="bookCover"  src="<?=$result->book_img?>">
    <p class="bookAction right">+本书简介</p>
    <div class="bookValue clear">本书已累计借阅<?=$result->times?>次，为他人节约<?=$result->value?>元</div>
    <div class="timeLine">
        <div class="timeLineItem">
            <div class="left time">
                <p class="day"><?=date("Y-m-d")?></p>
                <p class="hour"><?=date("h:i")?></p>
            </div>
            <div class="left userIcon"><img src="/img/plus.png" style="width: 20px;margin: 5px;"></div>
            <div class="leftLine right">
                <div class="left userState">
                    <div class="userInfo"><span class="userName">点击借阅+</span></div>
                </div>
            </div>
        </div>
        <?php foreach ($result->timeline as $key => $value) :?>
        <?php 
            $day = substr($value->create_at,0,10); 
            $time = substr($value->create_at,11,5); 
            switch ($value->state)
            {
            case '1':
              $state = "想读";
              break;  
            case '2':
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
    		<div class="left userIcon"><img src="<?=$value->pic?>"></div>
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
@include('front.common.footer')