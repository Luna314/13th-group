<?php
    # require "./service/model.php";
    require "/www/wwwroot/jawnho.xyz/PHPtest/ver3/model.php";

    $Img = $_POST["img"];
    $username = $_COOKIE["name"];

    # 数据库连接建立
    $sqlCon = connectSQL();
    $ret = imageStore($sqlCon, $username, $Img);
    print($ret);
    # echo json_encode($ret);
    
?>