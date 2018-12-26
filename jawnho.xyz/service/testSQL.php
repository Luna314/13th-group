<?php
    require "./sqlHandler.php";

    function test() {
        /* 
         * 该PHP模块的测试用例
         * 需要测试部分都在这里修改
         */
        $sqlCon = connectSQL();
    
        $keys = new SQLKeys();
        $keys->setKeys(
            $Main = "performer, count(*)",
            $Where = array([["release_date", 
                "'2017%'", "LIKE"]]
            ),
            $Dist = true,
            $func = "", 
            $Odr = ["count(*)", "desc"],
            $Lim = 15,
            $GB = "performer"
        );
        # print_r($keys);
    
        $gen = new SQLGenerator();
        $gen->setParams(performer_table, $keys);
        $sql = $gen->getSQL($sqlCon);
        echo $sql;

        $ret = array();
        $counter = 1;
        foreach ($sqlCon->query($sql) as $row) {
            echo "$counter: ";
            print_r($row);
            echo "<br>";
            ++$counter;
            $tuple = array("name"=>$row["performer"], 
                "times"=>$row["count(*)"]
            );
            array_push($ret, $tuple);
        }
        print_r($ret);
    
        closeSQL($sqlCon);
    }

    test();
?>