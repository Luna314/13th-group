<?php
    header('Content-Type: text/html; charset=utf-8');
    #header('Content-Type: application/json; charset=utf-8');

    # =================== 数据库的基本信息 ===================
    CONST host = "localhost";
    CONST username = "jawnho_xyz";
    CONST passwd = "M2sJR5QSJy";
    CONST db = "jawnho_xyz";

    # 两张特殊的表
    CONST collective_table = "movie_basic";
    CONST performer_table = "performers";
    CONST image_table = "Images";
    CONST None = "";

    # ====================================================
    # |                     数据库操作                     |
    # ====================================================
    function connectSQL() {
        /* 
         * 功能: 连接到指定的数据库
         * 返回: MySQL的PDO连接
         */
        $dsn = "mysql:host=".host.";dbname=".db;
    
        # MySQL的连接错误检测
        # 注意：连接失败的事件流待补充
        try {
            $con = new PDO($dsn, username, passwd, 
                array(PDO::ATTR_PERSISTENT => true));
        } catch (Exception $e) {
            die("Connection Failed: ".$con->connect_error);
        }
        # echo "<br>"."连接成功"."<br>";
        return $con;
    }

    function closeSQL(&$con) {
        /* 
         * $con: 数据库连接变量
         * 功能: 关闭MySQL的连接
         * 返回: 无
         */
        $con = null;
        # echo "<br>"."连接关闭"."<br>";
    }

    # ====================================================
    # |                   SQL参数设定器                    |
    # ====================================================
    class SQLKeys
    {
        public $main = "";
        public $distinct = "";
        public $func = "";
        public $order = ["", ""];
        public $group_by = "";
        public $limit = "";
        public $where = array();
        
        public function setKeys($Main, $Where, $Dist="", 
            $func="", $Odr="", $Lim="", $GB="")
        {
            /*
             * 功能: 设置SQL字段的值
             * 返回: 无
             */ 
            $this->main = $Main;
            $this->where = $Where;
            $this->distinct = $Dist;
            $this->func = $func;
            $this->order = $Odr;
            $this->limit = $Lim;
            $this->group_by = $GB;
            $this->__checkWhereKey();
        }

        private function __checkWhereKey() {
            /*
             * 功能: 检查WHERE字段的规范性
             * 返回: 无
             */ 
            try {
                if (!is_array($this->where)){
                    $e = "WHERE参数的类型有误！".
                            "必须为array类型<br>";
                    throw new Exception($e);
                }
                foreach ($this->where as $keys) {
                    if (!is_array($keys)) {
                        $e = $key."字段的类型有误！".
                                    "必须为array类型<br>";
                        throw new Exception($e);
                    }
                    foreach ($keys as $pairs) {
                        if (!is_array($pairs)) {
                            $e = $pairs."字段的类型有误！";
                            $e .= "必须为array类型<br>";
                            throw new Exception($e);
                        }
                        if (count($pairs) < 2 or 
                                count($pairs) > 4)
                        {
                            $e = "请检查".$pairs[0]."字段的格式！";
                            throw new Exception($e);
                        }
                    }
                }
            } catch (Exception $e) {
                echo $e;
                echo "\"WHERE\"字段格式有误！请按规范写入。<br>\n";
                echo "字段输入规范：<br>";
                echo "\"Main\": 主键，空字串则默认为\"*\"<br>\n";
                echo "\"Dist\": 是否采用DISTINCT字段，true或false<br>\n";
                echo "\"func\": 采用的函数字段，SUM 或 MAX 或 MIN<br>\n";
                echo "\"Odr\": ORDER BY的排序字段<br>\n";
                echo "\"GB\": GROUP BY的分组字段<br>\n";
                echo "\"Lim\": LIMIT的限定字段，";
                echo        "以[头，尾]或\"尾\"的形式输入<br>\n";
                echo "\"Where\": WHERE的范围指定字段，输入格式如下<br>\n";
                echo "\tarray([A类条件1], [A类条件2], ...)<br>\n";
                echo "其中，A类条件之间将做AND运算，A类条件表示为：<br>\n";
                echo "\tarray([B类条件1], [B类条件2], ...)<br>\n";
                echo "其中，B类条件之间将做OR运算，B类条件表示为：<br>\n";
                echo "\tarray(字段名，字段值，查询方式) ",
                            "或 array(字段名，字段值)<br>\n";
                echo "范例：<br>\nWHERE=array(".
                    "[['a', 2], ['b', '192.%', 'LIKE']], ['c', 10, '='])<br>\n".
                    "表示 WHERE (a=2 OR b LIKE '192.%') AND (c=10)<br>\n";
                die();
            }
        }
    }
    
    # ====================================================
    # |                     SQL生成器                     |
    # ====================================================
    class SQLGenerator
    {
        private $table = "";        # 所操作的SQL表名
        private $Keys = array();    # 查询的关键字
        private $sql = "";          # SQL语句

        public function __construct()
        {
            $this->Keys = new SQLKeys();
        }

        public function setParams($newTable, $newKeys) {
            /*
             * 功能: 设置类属性的值
             * $newTable: 要设置的SQL表名
             * $newKeys: 要设置的SQL字段参数
             * 返回: 无
             */ 
            $this->table = $newTable;
            $this->Keys = $newKeys;
        }

        public function getKeys() {
            /*
             * 功能: 返回类属性$Keys的值
             * 返回: 类属性$Keys的值
             */ 
            return $this->Keys;
        }

        public function getSQL($con) {
            /*
             * 功能: 返回类属性$sql存储的SQL语句
             *      如果$sql为空，那么立即生成一个SQL语句后再返回
             * 返回: 类属性$sql的值
             */
            $this->sql = $this->__generate();
            $this->__Prepare($con, $this->sql);
            return $this->sql;
        }

        private function __Prepare($con, $sql) {
            /*
             * 功能: 检查SQL语句是否合法
             * $con: 与SQL数据库的连接，可以是mysqli、PDO等对象
             * $sql: 被检查的SQL语句
             * 返回: 无
             */ 
            try {
                $stmt = $con->prepare($sql);
            }
            catch (Exception $e) {
                echo $this->sql;
                die('sql语句有问题，请重新检查!');  
            }
        }

        private function __generate() {
            /*
             * 功能: 生成SQL语句
             * 返回: 根据$Keys中的值生成的SQL语句
             */ 
            $sql = "";

            # 检查表名
            if (!$this->table) {
                die("ERROR: 表名不能为空！");
            }
            
            # 需要返回的主键部分
            # 添加：SELECT func(col_name) from tbl_name
            $KEYS = $this->Keys;
            # 设置默认主键
            if (!($main = $KEYS->main) )
                $main = "*";

            # 设置DISTINCT参数
            if ($distinct = $KEYS->distinct)
                $sql .= "SELECT DISTINCT ";
            else
                $sql .= "SELECT ";

            # 处理SUM、MAX、MIN等字段
            $fun = $KEYS->func;
            if ($fun)
                $sql .= $fun."(".$main.") FROM ".$this->table;
            else
                $sql .= $main." FROM ".$this->table;

            # WHERE参数
            if ($whr = $KEYS->where) {
                # 加入WHERE字段作为开头
                $sql .= " WHERE ";

                # WHERE的AND字段
                $total_keys = count($whr);
                $key_count = 0;
                foreach ($whr as $key) {
                    $sql .= "(";
                    $key_count += 1;
                    # WHERE的OR字段
                    $pairs = $key;
                    $total_pairs = count($pairs);
                    $pair_count = 0;
                    foreach ($pairs as $pair) {
                        $pair_count += 1;
                        # 字段名
                        $sql .= $pair[0]." "; 
                        # 字段值的查询方式  
                        if ($pair[2])
                            $sql .= $pair[2]." ";
                        else
                            $sql .= " = ";
                        # 字段值
                        $sql .= $pair[1]." ";
                        if ($total_pairs > 1 and 
                                $pair_count < $total_pairs)
                            $sql .= ") OR (";
                    }
                    if ($total_keys > 1 and 
                            $key_count < $total_keys)
                        $sql .= ") AND (";
                }
                $sql .= ") ";
            }

            # GROUP BY参数
            if ($gb = $KEYS->group_by) {
                $sql .= "GROUP BY ".$gb." ";
            }

            # ORDER参数
            if ($odr = $KEYS->order) {
                $sql .= "ORDER BY ".$odr[0]." ";
                if ($odr[1])
                    $sql .= $odr[1]." ";
            }

            # LIMIT参数
            if ($lim = $KEYS->limit) {
                if (is_array($lim))
                    $sql .= "LIMIT ".$lim[0].
                                ",".$lim[1];
                else
                    $sql .= "LIMIT 0,".$lim;
            }

            $sql .= ";";
            return $sql;
        }
    }

    function test() {
        /* 
         * 该PHP模块的测试用例
         * 需要测试部分都在这里修改
         */
        global $sqlCon;
        $sqlCon = connectSQL();
    
        $keys = new SQLKeys();
        $keys->setKeys(
            $Main = "movie_name", 
            $Where = array(
                [["release_date", "'2017-%'", "LIKE"]] # 
            ),
            $Dist = true, 
            $func = "", 
            $odr = ["total_box", "desc"], 
            $GB = "",
            $Lim = "3"
        );
        print_r($keys);
    
        $gen = new SQLGenerator();
        $gen->setParams(collective_table, $keys);
        $sql = $gen->getSQL($sqlCon);
        echo $sql;
    
        closeSQL($sqlCon);
    }

    # test();     # 测试命令，不需要时要注释掉
    
?>