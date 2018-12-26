<?php
$db_host="47.107.247.85";	//数据库主机
$db_user="root";	//数据库用户名
$db_pass="S8a3fWHLtDnhEF4s";	//数据库密码
$db_name="user";	//选择的数据库
$timezone="Asia/Shanghai";  //设置时区
$conn = new mysqli($db_host,$db_user,$db_pass);   //连接数据库
$conn->select_db($db_name);	//选择数据库
$conn->query("SET names UTF8");	//设置数据库编码
header("Content-Type: text/html; charset=utf-8");	//设置网页编码
date_default_timezone_set($timezone); //北京时间