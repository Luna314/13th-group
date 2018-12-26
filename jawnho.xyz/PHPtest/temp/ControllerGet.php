<?php
    require "./service/model.php";
    $func_id = $_GET["func_id"];
    $Img = $_POST["img"];

    # 数据库连接建立
    $sqlCon = connectSQL();
    
    $gen = new SQLGenerator();

    

    # 业务选择
    switch($func_id) {
        case "1": {
            # 转入业务1
            $valid = $_GET["valid"];
            $year = $_GET["year"];
            $season = $_GET["season"];
            $month = $_GET["month"];
            
            $Params = array("valid"=>$valid,
                            "year"=>$year,  
                            "season"=>$season, 
                            "month"=>$month);
            $ret = TypeMovie($sqlCon, $Params, $gen);
            $msg = json_encode($ret);   # 返回信息转码

            break;
        } case "2": {
            # 转入业务2
            $year1 = $_GET["year1"];
            $year2 = $_GET["year2"];
            $ret_1 = lineGraph($sqlCon, $year1, $gen);
            $ret_2 = lineGraph($sqlCon, $year2, $gen);

            # 返回信息转码
            $msg = json_encode(array("year1"=>$ret_1, 
                                    "year2"=>$ret_2));
            break;
        } case "3": {
            # 转入业务3
            $year = $_GET["year"];
            $top = $_GET["top"];
            $Params = array("year"=>$year, "top"=>$top);
            
            $ret = topMovie($sqlCon, $Params, $gen);
            $msg = json_encode($ret);   # 返回信息转码
            
            break;
        } case "4": {
            # 转入业务4
            $year = $_GET["year"];
            $top = $_GET["top"];
            $sex = $_GET["sex"] ? "男" : "女";
            # 构造参数表
            $Params = array("year"=>$year, "top"=>$top);
            $ret = topPerformers($sqlCon, $Params, $gen);
            $msg = json_encode($ret);   # 返回信息转码
            
            break;
        } case "5": {
            # 转入业务5

            break;
        } default: {
            echo "Invalid function id: ", $func_id, "!<br>";
            exit(0);
        }
    }

    # 返回结果
    # print_r($ret);
    # print_r($ret_1);
    # print_r($ret_2);
    echo $msg;

    # 数据库连接关闭
    closeSQL($sqlCon);
?>