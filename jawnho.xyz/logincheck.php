<?php 

session_start();
	//登录处理界面 logincheck.php
	//判断是否按下提交按钮
 if(isset($_POST["submit"]) && $_POST["submit"] == "Sign in") 
 { 
		//将用户名和密码存入变量中，供后续使用
		$user = trim($_POST["username"]); 
		$psw = md5(trim($_POST["password"])); 
		$code = $_POST["code"];
	/*if($user == "" || $psw == "") 
	{
	//用户名或者密码其中之一为空，则弹出对话框，确定后返回当前页的上一页 
		alert("请输入用户名或密码！");myfrom.user.focus();return false;
	} */
	
	//else 
	//{ //确认用户名密码不为空，则连接数据库
        if($code != $_SESSION['ver_code']){
			include("low_back.html");
			echo "<script>
			alert('验证码不正确，请重新输入!'); 
			setTimeout(function(){
				window.location.href='log.html';
			},1000);
			</script>";
		}
		else{
		$conn = mysqli_connect("47.107.247.85","root","S8a3fWHLtDnhEF4s");//数据库帐号密码为安装数据库时设置
		if(mysqli_errno($conn)){
			echo mysqli_errno($conn);
			exit;
		}
		mysqli_select_db($conn,"user"); 
		mysqli_set_charset($conn,'utf8'); 
		$sql = "select username,password from t_user where username = '$user' and password = '$psw'"; 
		$result = mysqli_query($conn,$sql); 
		$num = mysqli_num_rows($result); 
		if($num) 
		{   		
	        $sql = "select username,password,status from t_user where username = '$user' and password = '$psw' and status = '1'"; 
			$Result = mysqli_query($conn,$sql); 
			$Num = mysqli_num_rows($Result); 
			if($Num){
				setcookie('name',$user);
				include("index.html");
				echo "<script>window.location.href='index.html';</script>";
			    //$smarty->assign('content',$user);
				//$smarty->display('sidebar.html');
		    /*echo "<form style='display:none;' action='homepage.html' method='post' name='form_ac'>
		             <input name='user_name' type='text' value='".$user."'>
		          </form>
				  <script type='text/javascript'>function load_submit(){document.form_ac.submit()}load_submit();</script>";*/
			}

			else{
				include("low_back.html");
				echo "<script>
					alert('请确认邮箱激活码验证账号！');
					setTimeout(function(){
						window.location.href='log.html';
					},1000);
					</script>"; 
			}
		}
		else 
		{   include("low_back.html");
			echo "<script>
				alert('用户名或密码不正确！');
				setTimeout(function(){
					window.location.href='log.html';
				},1000);
				</script>"; 
		} 
		}
 }
 else 
 { 
	echo "<script>alert('提交未成功！');</script>"; 
 } 
?> 