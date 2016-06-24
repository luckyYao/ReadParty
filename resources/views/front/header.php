<?php include("../resources/views/front/function.php");?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <title><?=$title?></title>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection"content="telephone=no, email=no" />
    <meta name="renderer" content="webkit">

    <meta name="title" content="<?=$title?>" />
    <meta name="description" content="<?=$description?>" />
    <!--icon-->
    <link rel="shortcut icon" href="http://o859gakxp.bkt.clouddn.com/static/img/readParty.png?imageView/2/w/120" />
    <!--拾光css-->
    <link rel="stylesheet" href="http://source.timepicker.cn/static/css/ptime_v0.4.min.css">
    <link rel="stylesheet" href="/stylesheets/ionicons.css">
    <link rel="stylesheet" href="/stylesheets/screen.css">
    <style type="text/css">
    html{
        height: 100%;
    }
    body{
        min-height: 100%;
    }
    </style>
</head>
<body class="agent-<?=getUserAgent()?>">
    <!--头部-->
    <header class="bg-mcolor fixed top" >
        <h1><?=$header?></h1>
        <span class="button top-right white" onclick="share()">分享</span>
    </header>
    <!--导航-->
    <nav class="clearfix fixed bottom">
        <ul >
            <li><a class="<?=handleCurrent('/')?>" href="/"><span class="ion-ios-bookmarks"></span>&nbsp;读书</a></li>
            <li><a class="<?=handleCurrent('news')?>" href="/news"><span class="ion-chatbubbles"></span>&nbsp;分享</a></li>
            <li><a class="<?=handleCurrent('my')?>" href="/myBook"><span class="ion-android-person"></span>&nbsp;个人</a></li>
        </ul>
    </nav>