<?php
include("register.html");
include("connect.php");
if(isset($_POST["submit"]) && $_POST["submit"] == "Register") 
{ 
	$username = stripslashes(trim($_POST['username']));
	// 检测用户名是否存在
	$query = $conn->query("select id from t_user where username='$username'");
    $num = mysqli_num_rows($query);
	if($num){
		echo '<script>alert("用户名已存在，请换个其他的用户名");registerform.log.focus();;</script>';
		exit;
	}
	$password = md5(trim($_POST['password']));
	$email = trim($_POST['email']);
	$regtime = time();
	$token = md5($username.$password.$regtime); //创建用于激活识别码
	$token_exptime = time()+60*60*24;//过期时间为24小时后
	$sql = "insert into `t_user` (`username`,`password`,`email`,`token`,`token_exptime`,`regtime`) values ('$username','$password','$email','$token','$token_exptime','$regtime')";
	$conn->query($sql);
	if(mysqli_insert_id($conn)){//写入数据库成功，发邮件
		require_once("email.class.php");
		$MailServer = "ssl://smtp.qq.com"; //SMTP服务器
		$MailPort = 465; //SMTP服务器端口
		$smtpMail = "849974258@qq.com"; //SMTP服务器的用户邮箱
		$smtpuser = "849974258@qq.com"; //SMTP服务器的用户帐号
		$smtppass = "slyusnouqtogbedj"; //SMTP服务器的用户密码
		//创建$smtp对象 这里面的一个true是表示使用身份验证,否则不使用身份验证.
		$smtp = new Smtp($MailServer, $MailPort, true, $smtpuser, $smtppass); 
		$mailType = "HTML"; //信件类型，文本:text；网页：HTML
		$email = $email;  //收件人邮箱
		$emailTitle = "MovieLook用户帐号激活"; //邮件主题
		$emailBody = "亲爱的".$username."：<br/>感谢您在我站注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='http://www.jawnho.xyz/active.php?verify=".$token."' target='_blank'>http://www.jawnho.xyz/active.php?verify=".$token."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>-------- MovieLook 敬上</p>";
		// sendmail方法
	
		// 参数1是收件人邮箱
	
		// 参数2是发件人邮箱
	
		// 参数3是主题（标题）
	
		// 参数4是邮件主题（标题）
	
		// 参数4是邮件内容  参数是内容类型文本:text 网页:HTML
		$smtp->debug = false;
		$rs = $smtp->sendmail($email, $smtpMail, $emailTitle, $emailBody, $mailType);
	
		if($rs==true){
			echo '<script>alert("恭喜您，注册成功！请登录到您的邮箱及时激活您的帐号！");</script>';
		}else{
			echo '<script>alert("注册失败")</script>';
		}
	}
}
?>