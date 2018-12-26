<?php
    # require "./service/sqlHandler.php";
    require "/www/wwwroot/jawnho.xyz/PHPtest/ver2/sqlHandler.php";

    function dateGen($isVague, $year, $month="", $day="") {
        $wildcard = "%";
        $date = "'";
        $date .= strval($year)."-";
        if ($month)
            $date .= strval($month)."-";
        if ($day)
            $date .= strval($day)."-";
        if ($isVague)
            $date .= $wildcard;
        $date .= "'";
        return $date;
    }
    
    function model_1($con, $Params, $sqlGen) {
        $keyParams = new SQLKeys();

        # 有效时间字段的选择
        if ($Params["valid"] == 1)
            # 只有年份为有效字段
            $time = [$Params["year"], []];
        elseif ($Params["valid"] == 2) {
            # 年份和季度为有效字段
            $time = [$Params["year"]];
            $s = $Params["season"];
            if ($s == "第一季")
                array_push($time, ["01", "02", "03"]);
            elseif ($s == "第二季")
                array_push($time, ["04", "05", "06"]);
            elseif ($s == "第三季")
                array_push($time, ["07", "08", "09"]);
            elseif ($s == "第四季")
                array_push($time, ["10", "11", "12"]);
            else
                $time = 0;
        }
        elseif ($Params["valid"] == 3)
            # 年份和月份为有效字段
            $time = [$Params["year"], [$Params["month"]]];
        else
            # 不合格字段
            $time = 0;

        if ($time == 0)
            return array("No message captured!");

        # 生成参数表
        $whr = array();
        foreach ($time[1] as $m) {
            array_push($whr, ["release_date", 
                dateGen(true, $time[0], $m), "LIKE"]);
        }
        $keyParams->setKeys(
            $Main = "movie_type, total_box", 
            $Where = array($whr)
        );
        
        # 生成SQL语句
        $sqlGen->setParams(collective_table, $keyParams);
        $sql = $sqlGen->getSQL($con);
        # echo $sql;
        
        # 统计不同题材的电影数据
        $sum_box = 0;
        $dict = array();
        foreach ($con->query($sql) as $row) {
            $sum_box += $row["total_box"];
            $types = explode(",", $row["movie_type"]);
            foreach ($types as $t) {
                if ($dict[$t])
                    $dict[$t] += $row["total_box"];
                else
                    $dict[$t] = $row["total_box"];
            }
        }
        arsort($dict);  # 对数组按照键值排序
        # print_r($dict);

        # 封装传递的数据
        $threshold = 5; # 百分数
        $other_box = 0;
        $other_ratio = 0;
        $sum_box = array_sum($dict);
        $cat = array();
        $box = array();
        $por = array();
        foreach (array_keys($dict) as $key) {
            $this_box = $dict[$key];
            $ratio = $this_box / $sum_box;

            # 统计票房占比 > 5%的题材
            if (($percent = $ratio*100) > $threshold) {
                array_push($cat, $key);
                array_push($box, $this_box);
                array_push($por, strval($percent)."%");
            }
            else
                $other_box += $dict[$key];
        }

        # 对其他部分进行统计和封装
        $other_ratio = $other_box / $sum_box;
        array_push($cat, "其他");
        array_push($box, $other_box);
        array_push($por, strval($other_ratio*100)."%");

        return array("category"=>$cat, 
                    "boxOffice"=>$box, 
                    "portion"=>$por);
    }

    function model_2($con, $year, $sqlGen) {
        $keyParams = new SQLKeys();

        # 第一步：找到对应年份的电影名
        $keyParams->setKeys(
            $Main = "movie_name", 
            $Where = array([["release_date", dateGen(true, $year), "LIKE"],
                        ["release_date", dateGen(true, $year, 12), "LIKE"],
                        ["release_date", dateGen(true, $year, 11), "LIKE"]
                    ])
        );
        $sql_1 = $sqlGen->setParams(collective_table, $keyParams);
        $sql_1 = $sqlGen->getSQL($con);
        # echo $sql_1;
        $names = array();
        foreach ($con->query($sql_1) as $row)
            array_push($names, $row[$keyParams->main]);
        
        # 第二步：针对每一条电影名
        # 去对应的电影表下找票房数据
        # 求和并加入到结果中
        $month_box = array("01"=>0.0, "02"=>0.0, "03"=>0.0, 
                        "04"=>0.0, "05"=>0.0, "06"=>0.0, 
                        "07"=>0.0, "08"=>0.0, "09"=>0.0, 
                        "10"=>0.0, "11"=>0.0, "12"=>0.0
                        );
        foreach ($names as $name) {
            for ($month = 1; $month <= 12; ++$month) {
                if ($month < 10)
                    $month = "0".$month;
                $keyParams->setKeys(
                    $Main = "box_office",
                    $Where = array([["box_date", 
                            dateGen(true, $year, $month), "LIKE"]]),
                    $Dist = true,
                    $func = "SUM"
                );
                $sql_n = $sqlGen->setParams($name, $keyParams);
                $sql_n = $sqlGen->getSQL($con);
                # echo $sql_n, "<br>";
                if ($sum = $con->query($sql_n)) {
                    $month_box[$month] += $sum->fetch()[0];
                }
            }
        }
        
        # 最后，返回月度票房数据列表
        return $month_box;
    }

    function model_3($con, $Params, $sqlGen) {
        $keyParams = new SQLKeys();

        $keyParams->setKeys(
            $Main = "movie_name, total_box",
            $Where = array([["release_date", 
                    dateGen(true, $Params["year"]), 
                    "LIKE"]]
            ),
            $Dist = true,
            $func = "", 
            $Odr = ["total_box", "desc"],
            $Lim = $Params["top"]
        );
        # 构建SQL语句
        $sqlGen->setParams(collective_table, $keyParams);
        $sql = $sqlGen->getSQL($con);
        # echo $sql;

        # 封装返回数据
        $ret = array();
        foreach ($con->query($sql) as $row) {
            $tuple = array("names"=>$row["movie_name"], 
                "total_box"=>$row["total_box"]
            );
            array_push($ret, $tuple);
        }
        return $ret;
    }

    function model_4($con, $Params, $sqlGen) {
        $keyParams = new SQLKeys();
        
        $keyParams ->setKeys(
            $Main = "performer, count(*)",
            $Where = array([["release_date", 
                dateGen(true, $Params["year"]), "LIKE"]]
            ),
            $Dist = true,
            $func = "", 
            $Odr = ["count(*)", "desc"],
            $Lim = $Params["top"],
            $GB = "performer"
        );
        # 构建SQL语句
        $sqlGen->setParams(performer_table, $keyParams);
        $sql = $sqlGen->getSQL($con);
        # echo $sql;

        # 封装返回数据
        $ret = array();
        foreach ($con->query($sql) as $row) {
            $tuple = array("name"=>$row["performer"], 
                "times"=>$row["count(*)"]
            );
            array_push($ret, $tuple);
        }
        
        return $ret;
    }

?>