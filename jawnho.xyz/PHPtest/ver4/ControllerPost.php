<?php
    require "./service/model.php";

    $Img = $_POST["img"];
    $username = $_COOKIE["name"];

    # 数据库连接建立
    $sqlCon = connectSQL();

    # 写内容到数据库上
    $ret = imageStore($sqlCon, $username, $Img);

    # echo json_encode(array($ret, $username, $Img));
    echo json_encode($ret);
    
?>