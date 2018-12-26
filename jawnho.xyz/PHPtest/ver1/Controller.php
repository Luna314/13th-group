<?php
    require "./service/model.php";
    # require "/www/wwwroot/jawnho.xyz/PHPtest//model.php";
    global $sqlCon;
    $func_id = $_GET["func_id"];
    $func_id;

    # 数据库连接建立
    connectSQL($sqlCon);
    
    $gen = new SQLGenerator();

    # 业务选择
    # $func_id = 4;
    switch($func_id) {
        case "1": {
            # ==========================================
            # |        显示季度/月度题材电影票房占比        |
            # ==========================================
            $valid = $_GET["valid"];
            $year = $_GET["year"];
            $season = $_GET["season"];
            $month = $_GET["month"];
            // # =========== 测试用 ===========
            // $valid = 2;
            // $year = 2016;
            // $season = "第一季";
            // $month = 11;
            // # =========== 测试用 ===========
            $Params = array("valid"=>$valid,
                            "year"=>$year,  
                            "season"=>$season, 
                            "month"=>$month);
            $ret = model_1($sqlCon, $Params, $gen);
            $msg = json_encode($ret);
            break;
        } case "2": {
            # ==========================================
            # |           按年份显示月票房折线图           |
            # ==========================================
            // $year1 = $_GET["year1"];
            // $year2 = $_GET["year2"];
            # =========== 测试用 ===========
            $year1 = 2017;
            $year2 = 2018;
            # =========== 测试用 ===========
            $ret_1 = model_2($sqlCon, $year1, $gen);
            $ret_2 = model_2($sqlCon, $year2, $gen);
            $msg = json_encode(array("year1"=>$ret_1, 
                                    "year2"=>$ret_2));
            break;
        } case "3": {
            # ==========================================
            # |             显示票房Top电影              |
            # ==========================================
            $year = $_GET["year"];
            $top = $_GET["top"];
            # =========== 测试用 ===========
            // $year = strval(2018);
            // $top = "5";
            # =========== 测试用 ===========
            $Params = array("year"=>$year, "top"=>$top);
            $ret = model_3($sqlCon, $Params, $gen);
            $msg = json_encode(array("top"=>$ret));
            break;
        } case "4": {
            # ==========================================
            # |               显示劳模演员               |
            # ==========================================
            $year = $_GET["year"];
            $top = $_GET["top"];
            $sex = $_GET["sex"] ? "男" : "女";
            # =========== 测试用 ===========
            // $year = strval(2018);
            // $top = "7";
            // $sex = "男";
            # =========== 测试用 ===========
            $Params = array("year"=>$year, "top"=>$top, 
                            "sex"=>$sex);
            $ret = model_4($sqlCon, $Params, $gen);
            $msg = json_encode($ret);
            
            break;
        } default: {
            $func_id = 0;
            echo "Invalid function id!<br>";
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