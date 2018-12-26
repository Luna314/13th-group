<html>
  <style>    
body{background:url(https://p5.ssl.qhimgs1.com/bdr/_240_/t0168ff8e0410969edb.jpg);}
</style>
<script src="style/js/jquery.cookie.js"></script>
  	<script type="text/javascript">
		window.alert = function (txt, time) {
            if (document.getElementById("alertFram")) {
                return;
            }
            var alertDiv = document.createElement("DIV");
            alertDiv.id = "alertFram";
            alertDiv.style.position = "absolute";
            alertDiv.style.left = "50%";
            alertDiv.style.top = "40%";
            alertDiv.style.marginLeft = "-225px";
            alertDiv.style.marginTop = "-75px";
            alertDiv.style.width = "450px";
            alertDiv.style.height = "150px";
            alertDiv.style.background = "#ccc";
            alertDiv.style.textAlign = "center";
            alertDiv.style.lineHeight = "150px";
            alertDiv.style.zIndex = "10000";
            alertDiv.innerHTML = "<ul style='list-style:none;margin:0px;padding:0px;width:100%'><li style='background:#DD828D;text-align:left;padding-left:10px;font-size:14px;font-weight:bold;height:27px;line-height:25px;border:1px solid #F9CADE;'>MovieLook提示</li><li style='background:#fff;text-align:center;font-size:17px;height:120px;line-height:120px;border-left:1px solid #F9CADE;border-right:1px solid #F9CADE;'>" + txt + "</li><li style='background:#FDEEF4;text-align:center;font-weight:bold;height:27px;line-height:25px; border:1px solid #F9CADE;'onclick='doOk()'><input type='button' style='background-color: #FDEEF4;border: none;outline:none;' value='确 定'/></li></ul>";
                            //                                                                                                                                                                  提示框高度 ;提示文本高度位置;                                                                                          文本字体大小  ;                               左边框颜色                   ;右边框颜色                    ;                                                                    
			document.body.appendChild(alertDiv);
            var c = 0;
            this.timer = function () {
                if (c++ > time) {
                    clearInterval(ad);
                    document.body.removeChild(alertDiv);
                }
            }
            var ad = setInterval("timer()", 1000);
            this.doOk = function () {
                document.body.removeChild(alertDiv);
            }
            alertDiv.focus();
            document.body.onselectstart = function () {
                return false;
            };
        }
	</script>
  </html>
<?php
include_once("connect.php");
include("index.html");
$verify = stripslashes(trim($_GET['verify']));
$nowtime = time();
$query = $conn->query("select id,token_exptime from t_user where status='0' and `token`='$verify'");
$row = mysqli_fetch_array($query);
//echo'<script type="text/javascript" src="./style/js/jquery-1.8.3.min.js"></script>';  //加载jquery
//echo'<script type="text/javascript" src="./style/js/layer.js"></script>';  //加载layer弹出层插件

if($row){
	if($nowtime>$row['token_exptime']){ //30min
		$msg = '您的激活有效期已过，请登录您的帐号重新发送激活邮件.';
	}else{
		$conn->query("update t_user set status=1 where id=".$row['id']);
		if(mysqli_affected_rows($conn)!=1) die(0);
		$msg = '激活成功！';
	}
}
else{
     $msg = '已激活！无需重复操作！';
}
echo'<script>alert("'.$msg.'");</script>';