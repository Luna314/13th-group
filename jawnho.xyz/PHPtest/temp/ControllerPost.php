<?php
    # require "./service/model.php";
    require "/www/wwwroot/jawnho.xyz/PHPtest/ver3/model.php";

    $Img = $_POST["img"];

    # 数据库连接建立
    $sqlCon = connectSQL();
    $ret = imageStore($sqlCon, $Img);
    echo json_encode($ret);
?>