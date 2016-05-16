-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016-04-29 09:37:03
-- 服务器版本: 5.6.19-0ubuntu0.14.04.1
-- PHP 版本: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `readparty`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(100) NOT NULL COMMENT '管理员name',
  `password` varchar(200) NOT NULL COMMENT '密码',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`, `create_at`) VALUES
(1, 'admin', '123123', '2016-04-29 05:43:15');

-- --------------------------------------------------------

--
-- 表的结构 `book`
--

CREATE TABLE IF NOT EXISTS `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `isbn` int(13) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '书名',
  `price` float NOT NULL,
  `pages` int(11) NOT NULL,
  `publisher` varchar(50) CHARACTER SET utf8 NOT NULL,
  `cover_url` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '封面',
  `publish_time` varchar(50) CHARACTER SET utf8 NOT NULL,
  `catalogue` varchar(200) CHARACTER SET utf8 NOT NULL,
  `introduction` varchar(200) CHARACTER SET utf8 NOT NULL,
  `author` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '作者',
  `author_introduction` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '作者简介',
  `douban_url` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '豆瓣书评地址',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_show` tinyint(1) NOT NULL COMMENT '是否可见',
  `is_delete` tinyint(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `book`
--

INSERT INTO `book` (`id`, `isbn`, `name`, `price`, `pages`, `publisher`, `cover_url`, `publish_time`, `catalogue`, `introduction`, `author`, `author_introduction`, `douban_url`, `create_at`, `is_show`, `is_delete`) VALUES
(6, 2147483647, '托斯卡纳艳阳下', 29.8, 229, '北方文艺出版社', 'https://img1.doubanio.com/lpic/s27252708.jpg', '2006年8月', '生命中总会有不顺利，当弗朗西丝.梅耶斯遭遇一切不幸时，她只身到意大利古镇托斯卡纳，用所有的钱买下一座老别墅。未知的新生活开始了，语言不通，生活习惯不同，没有熟悉的亲人朋友，她在彷徨、疑惑中坚持着，最终等到了完满的一切。托斯卡纳的山川风貌，民俗风情，香醇的葡萄酒，诱人的橄榄油以及历史古迹，让全世界都爱上了那个古老的地方。', '生命中总会有不顺利，当弗朗西丝.梅耶斯遭遇一切不幸时，她只身到意大利古镇托斯卡纳，用所有的钱买下一座老别墅。未知的新生活开始了，语言不通，生活习惯不同，没有熟悉的亲人朋友，她在彷徨、疑惑中坚持着，最终等到了完满的一切。托斯卡纳的山川风貌，民俗风情，香醇的葡萄酒，诱人的橄榄油以及历史古迹，让全世界都爱上了那个古老的地方。', '弗朗西丝·梅尔斯', '生命中总会有不顺利，当弗朗西丝.梅耶斯遭遇一切不幸时，她只身到意大利古镇托斯卡纳，用所有的钱买下一座老别墅。未知的新生活开始了，语言不通，生活习惯不同，没有熟悉的亲人朋友，她在彷徨、疑惑中坚持着，最终等到了完满的一切。托斯卡纳的山川风貌，民俗风情，香醇的葡萄酒，诱人的橄榄油以及历史古迹，让全世界都爱上了那个古老的地方。', '生命中总会有不顺利，当弗朗西丝.梅耶斯遭遇一切不幸时，她只身到意大利古镇托斯卡纳，用所有的钱买下一座老别墅。未知的新生活开始了，语言不通，生活习惯不同，没有熟悉的亲人朋友，她在彷徨、疑惑中坚持着，最终等到了完满的一切。托斯卡纳的山川风貌，民俗风情，香醇的葡萄酒，诱人的橄榄油以及历史古迹，让全世界都爱上了那个古老的地方。', '2016-04-29 05:12:50', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `book_tab`
--

CREATE TABLE IF NOT EXISTS `book_tab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL COMMENT '书籍id',
  `category_id` int(11) NOT NULL COMMENT '分类id',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_show` int(11) NOT NULL COMMENT '是否显示',
  `is_delete` int(11) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `book_tab`
--

INSERT INTO `book_tab` (`id`, `book_id`, `category_id`, `create_at`, `is_show`, `is_delete`) VALUES
(1, 6, 1, '2016-04-29 07:14:27', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `borrow`
--

CREATE TABLE IF NOT EXISTS `borrow` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(11) NOT NULL COMMENT '主人',
  `book_id` int(11) NOT NULL COMMENT '书籍id',
  `words` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '主人寄语',
  `read_times` int(11) NOT NULL COMMENT '借阅次数',
  `value` float NOT NULL COMMENT '公益价值',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '时间',
  `is_show` int(1) NOT NULL COMMENT '是否显示',
  `is_delete` int(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `borrow`
--

INSERT INTO `borrow` (`id`, `user_id`, `book_id`, `words`, `read_times`, `value`, `create_at`, `is_show`, `is_delete`) VALUES
(1, 1, 6, '借你一本书，请帮我飘流下去', 0, 0, '2016-04-29 05:47:34', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `news_id` int(11) NOT NULL COMMENT '动态id',
  `comment_id` int(11) DEFAULT NULL COMMENT '父级评论id',
  `content` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '内容',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_show` int(1) NOT NULL COMMENT '是否显示',
  `is_delete` int(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `comments`
--

INSERT INTO `comments` (`id`, `news_id`, `comment_id`, `content`, `create_at`, `is_show`, `is_delete`) VALUES
(1, 1, 0, '开篇引用的话很振奋人心呵。而且明示此书的真谛。\r\n我正在尝试按我想的去生活，趣味无穷。', '2016-04-29 06:24:56', 1, 0),
(2, 1, 1, '很令人羡慕和向往,可是想离开现在的生活,却又不够勇敢~!!\r\nP.S:小K的生活能否分享下啦...??', '2016-04-29 06:25:23', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `help`
--

CREATE TABLE IF NOT EXISTS `help` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(11) NOT NULL COMMENT '申请人',
  `book_id` int(11) NOT NULL COMMENT '书籍id',
  `words` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '申请词',
  `apply_times` int(11) NOT NULL COMMENT '申请人数',
  `value` float NOT NULL COMMENT '公益价值',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申请时间',
  `is_show` int(1) NOT NULL COMMENT '是否显示',
  `is_delete` int(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `help`
--

INSERT INTO `help` (`id`, `user_id`, `book_id`, `words`, `apply_times`, `value`, `create_at`, `is_show`, `is_delete`) VALUES
(1, 1, 6, '我想借托斯塔纳艳阳下，谁有？求分享，喜欢意大利！', 0, 0, '2016-04-29 06:12:13', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(11) NOT NULL COMMENT '作者',
  `content` varchar(300) CHARACTER SET utf8 NOT NULL COMMENT '内容',
  `pic` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '图片地址',
  `like` int(11) NOT NULL COMMENT '赞',
  `comments` int(11) NOT NULL COMMENT '评论个数',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_show` int(1) NOT NULL COMMENT '是否显示',
  `is_delete` int(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `news`
--

INSERT INTO `news` (`id`, `user_id`, `content`, `pic`, `like`, `comments`, `create_at`, `is_show`, `is_delete`) VALUES
(1, 1, '著名的连岳说过：“每次我最爱引用这句话‘你要按你所想的去生活，否则，你迟早会按你生活的去想’，但总有人说，你想就能行吗？中国这种地方太多无奈！请注意，这类人就是那类‘迟早按你生活去想’的例证。尖锐敏感如连岳这样的文字工作者都能没有放弃追求理想梦想、过上属于自己内心的活法，我们这些习惯了人云亦云的凡人更没有理由放弃，而这其中的高人当属美国旧金山州立大学女教授，同时也是美国一位著名诗人、作家弗朗西斯 ......', 'https://img1.doubanio.com/lpic/s27252708.jpg', 0, 0, '2016-04-29 06:23:24', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `tab`
--

CREATE TABLE IF NOT EXISTS `tab` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(200) NOT NULL COMMENT '分类名称',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_show` tinyint(1) NOT NULL COMMENT '是否可见',
  `is_delete` tinyint(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `tab`
--

INSERT INTO `tab` (`id`, `name`, `create_at`, `is_show`, `is_delete`) VALUES
(1, '历史', '2016-04-29 05:42:38', 1, 0),
(2, '宗教', '2016-04-29 06:30:39', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `timeline_borrow`
--

CREATE TABLE IF NOT EXISTS `timeline_borrow` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `book_id` int(11) NOT NULL COMMENT '关联的书',
  `user_id` int(11) NOT NULL COMMENT '关联用户',
  `state` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '借阅状态',
  `words` varchar(500) CHARACTER SET utf8 NOT NULL COMMENT '书评',
  `pic` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '状态图片',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_show` int(1) NOT NULL COMMENT '是否显示',
  `is_delete` int(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `timeline_borrow`
--

INSERT INTO `timeline_borrow` (`id`, `book_id`, `user_id`, `state`, `words`, `pic`, `create_at`, `is_show`, `is_delete`) VALUES
(1, 6, 1, '1', '阅读派对，以书的名义与你爱·分享', 'https://img1.doubanio.com/lpic/s27252708.jpg', '2016-04-29 06:18:17', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `timeline_help`
--

CREATE TABLE IF NOT EXISTS `timeline_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `book_id` int(11) NOT NULL COMMENT '关联的书',
  `user_id` int(11) NOT NULL COMMENT '关联用户',
  `state` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '借阅状态',
  `words` varchar(500) CHARACTER SET utf8 NOT NULL COMMENT '书评',
  `pic` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '状态图片',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_show` int(1) NOT NULL COMMENT '是否显示',
  `is_delete` int(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `timeline_help`
--

INSERT INTO `timeline_help` (`id`, `book_id`, `user_id`, `state`, `words`, `pic`, `create_at`, `is_show`, `is_delete`) VALUES
(2, 6, 1, '1', '求借书，帮帮忙！', 'https://img1.doubanio.com/lpic/s27252708.jpg', '2016-04-29 06:18:35', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `name` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '用户名',
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `pwd` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '密码',
  `sex` int(1) NOT NULL,
  `class` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '班级',
  `head_pic` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '头像',
  `introduction` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '自我介绍',
  `wechat` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '微信号',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '加入时间',
  `is_show` int(1) NOT NULL COMMENT '是否显示',
  `is_delete` int(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `name`, `phone`, `pwd`, `sex`, `class`, `head_pic`, `introduction`, `wechat`, `create_at`, `is_show`, `is_delete`) VALUES
(1, '四姑娘', '18230273602', 'dsfsdfdg', 1, '阅读派对', 'http://pic.brushes8.com/uploads/2012/01/danbo-cardboard-robot.jpg', '爱·分享', '燕大阅读派对', '2016-04-29 06:07:02', 1, 0),
(2, 'lucky', '18230273602', 'dsfsdfdg', 1, '阅读派对', 'http://pic.brushes8.com/uploads/2012/01/a-sunny-day.jpg', '阅读·约读·月读·悦读', 'lucky920418', '2016-04-29 06:07:16', 1, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
