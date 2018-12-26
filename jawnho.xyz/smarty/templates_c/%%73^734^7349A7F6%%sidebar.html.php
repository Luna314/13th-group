<?php /* Smarty version 2.6.30, created on 2018-12-14 12:57:59
         compiled from sidebar.html */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sidebar</title>
    <!--Tab页图标-->
    <link rel="shortcut icon" type="image/x-icon" href="images/logo/logosmall.png" />
    <!--插入js脚本-->
    <script src="style/js/jquery-1.8.3.min.js"></script>
    <!--设置多级导航菜单的插件-->
    <script type="text/javascript" src="style/js/ddsmoothmenu.js"></script>
    <!--设置滚动传送的插件-->
    <!-- <![endif]-->
    <link rel="stylesheet" type="text/css" href="style/css/ddsmoothmenu.css" />
    <!--<script>-->
	   <?php echo '
        <!--jQuery(document).ready(function($) {-->
            <!--$(\'.logbtn\').click(function(){-->
                <!--$(\'.theme-popover-mask\').fadeIn(100);-->
                <!--$(\'.theme-popover\').slideDown(200);-->
            <!--})-->
            <!--$(\'.theme-poptit .close\').click(function(){-->
                <!--$(\'.theme-popover-mask\').fadeOut(100);-->
                <!--$(\'.theme-popover\').slideUp(200);-->
            <!--})-->
        <!--})-->
		'; ?>

    <!--</script>-->
</head>
<body>
    <!-- Begin Menu -->
    <!--<div id="menu-ajax">-->
        <!--<ul>-->
            <!--<li><a href="gallery.html">可视化画廊展示</a></li>         &lt;!&ndash;from portfolio2.html&ndash;&gt;-->
            <!--<li><a href="visualizationEntry.html">可视化功能入口</a>   &lt;!&ndash;from portfolio4.html&ndash;&gt;-->
                <!--&lt;!&ndash;票房份额饼状图，票房份额直方图&ndash;&gt;-->
                <!--&lt;!&ndash;票房走势折线图&ndash;&gt;-->
                <!--&lt;!&ndash;年份最佳电影云图&ndash;&gt;-->
                <!--&lt;!&ndash;劳模影星柱状图,点状图&ndash;&gt;-->
                <!--<ul>-->
                    <!--<li><a href="visualizationEntry.html">可视化功能汇总入口</a></li>-->
                    <!--<li><a href="visualization1.html">票房份额分析</a></li>         &lt;!&ndash;from post.html&ndash;&gt;-->
                    <!--<li><a href="visualization1.html">票房走势折线图</a></li>-->
                    <!--<li><a href="visualization1.html">最佳电影云图</a></li>-->
                    <!--<li><a href="visualization1.html">劳模影星分析</a></li>-->
                <!--</ul>-->
            <!--</li>-->
            <!--<li><a href="report.html">生成报表</a></li>                 &lt;!&ndash;from portfolio3.html&ndash;&gt;-->
            <!--<li><a href="contact.html">联系我们</a></li>                &lt;!&ndash;from contact.html&ndash;&gt;-->
        <!--</ul>-->
    <!--</div>-->
    <!--&lt;!&ndash; End Menu &ndash;&gt;-->
    <a class="animateddrawer" id="ddsmoothmenu-mobiletoggle" href="#">
        <span></span>
    </a>
    <div  id="smoothmenu1" >
        <ul>
            <li id="firstli"><img src="images/logo/logo1.jpg" id="logopic"></li>
            <li ><a href="homepage.html">首页</a></li>         <!--from portfolio2.html-->
            <li><a href="gallery.html">可视化画廊展示</a></li>         <!--from portfolio2.html-->
            <li><a href="visualizationEntry.html" >可视化功能入口</a>   <!--from portfolio4.html-->
                <!--票房份额饼状图，票房份额直方图-->
                <!--票房走势折线图-->
                <!--年份最佳电影云图-->
                <!--劳模影星柱状图,点状图-->
                <ul>
                    <li class="secli"><a href="visualizationEntry.html" >可视化功能汇总入口</a></li>
                    <li class="secli"><a href="visualization1.html" >票房份额分析</a></li>         <!--from post.html-->
                    <li class="secli"><a href="visualization1.html" >票房走势折线图</a></li>
                    <li class="secli"><a href="visualization1.html" >最佳电影云图</a></li>
                    <li class="secli"><a href="visualization1.html" >劳模影星分析</a></li>
                </ul>
            </li>
            <li><a href="report.html" >生成报表</a></li>                 <!--from portfolio3.html-->
            <li><a href="contact.html">关于我们</a></li>                <!--from contact.html-->
            <li>
			<input type="submit" name="submit" value=<?php echo $this->_tpl_vars['content']; ?>
>
			<!--<a href="log.html">登录</a>-->
			</li>                <!--from contact.html-->
		</ul>
        <br style="clear: left" />
    </div>

    <!--<div class="theme-popover">-->
        <!--<div class="theme-poptit">-->
            <!--<a href="javascript:" title="关闭" class="close">×</a>-->
            <!--<h3>登录</h3>-->
        <!--</div>-->
        <!--<div class="theme-popbod dform">-->
            <!--<form class="theme-signin" name="loginform" action="" method="post">-->
                <!--<ol>-->
                    <!--<li><h1 class="logwel">欢迎进入MovieLook电影视界</h1></li>-->
                    <!--<li><input class="ipt" type="text" name="log" placeholder="请输入邮箱" size="20" /></li>-->
                    <!--<li><input class="ipt" type="password" name="pwd" placeholder="请输入密码"  size="20" /></li>-->
                    <!--<li><p class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;新用户首次登录自动验证邮箱注册噢！</p></li>-->
                    <!--<li><input class="btn btn-primary" type="submit" name="submit" value=" 登 录 " /></li>-->
                <!--</ol>-->
            <!--</form>-->
        <!--</div>-->
    <!--</div>-->
    <!--<div class="theme-popover-mask"></div>-->
</body>
</html>