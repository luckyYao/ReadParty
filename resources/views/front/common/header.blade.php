<!DOCTYPE html>
<html>
    <title>readParty</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> 
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection"content="telephone=no, email=no" />

    <link rel="shortcut icon" href="/img/favicon.png" />
    <link rel="stylesheet" href="/stylesheets/ionicons.css">
    <link rel="stylesheet" href="/stylesheets/screen.css">
    <!--[if IE 7]>
        <link href="/stylesheets/ie.css" media="screen, projection" rel="stylesheet" type="text/css" />
    <![endif]-->
    <!--[if IE 8]>
        <link href="/stylesheets/ie.css" media="screen, projection" rel="stylesheet" type="text/css" />
    <![endif]-->
    <!--[if IE 9]>
        <link href="/stylesheets/ie.css" media="screen, projection" rel="stylesheet" type="text/css" />
    <![endif]-->
</head>
<body>
    <header>
        <img src="/img/logo.png">
        <span class="ion-android-menu ion pointer" onclick="handleNav('tabBox')"></span>
        <span class="ion-funnel ion pointer" style="font-size:24px;" onclick="handleNav('navBox')"></span>
    </header>

    <section class="navBox" id="navBox">
        <ul class="nav">
            <li class="navItem"><a href="" class="ion-ios-bookmarks">读一读/Borrow</a></li>
            <li class="navItem"><a href="" class="ion-chatboxes">聊一聊/Share&nbsp;&nbsp;&nbsp;</a></li>
            <li class="navItem"><a href="" class="ion-coffee">休息一下/Mine&nbsp;</a></li>
            <li class="ion-close" style="color:#990000;line-height:35px;font-size:18px;" onclick="handleNav('navBox')"></li>
        </ul>
    </section>

    <section class="tabBox" id="tabBox">
        <ul class="tab">
            <li class="tabItem current">全部</li>
            <li class="tabItem level1">借本书</li>
            <li class="tabItem level1">帮帮忙</li>
        </ul>
        <ul class="tab">
            <li class="tabItem">历史</li>
            <li class="tabItem current">文学</li>
            <li class="tabItem">人文</li>
            <li class="tabItem">宗教</li>
            <li class="tabItem">设计</li>
            <li class="tabItem">技术</li>
        </ul>
        <div class="searchBox">
            <input type="text" class="search">
            <div class="searchNotic ion-ios-search">1222,888&nbsp;books</div>
        </div>
    </section>