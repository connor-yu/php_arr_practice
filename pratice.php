<?php
/**
 * 1、
 * 多维数组排序
 */
$items = array(
    array('http://www.abc.com/a/', 100, 120),
    array('http://www.abc.com/b/index.php', 50, 80),
    array('http://www.abc.com/a/index.html', 90, 100),
    array('http://www.abc.com/a/?id=12345', 200, 33),
    array('http://www.abc.com/c/index.html', 10, 20),
    array('http://www.abc.com/abc/', 10, 30)
);
$map = [];
foreach($items as $key=>$item){
    $pos = strrpos($item[0],'/');
    $key = substr($item[0],0,$pos+1);
    $item[0] = $key;
    if(isset($map[$key])){
        $map[$key][1] += $item[1];
        $map[$key][2] += $item[2];
    }else{
        $map[$key] = $item;
    }
}
var_dump(array_values($map));

/**
 * 2、
 * 一群猴子排成一圈，按1，2，...，n依次编号。
 * 然后从第1只开始数，数到第m只,把它踢出圈，从它后面再开始数，再数到第m只，
 * 在把它踢出去...，如此不停的进行下去，直到最后只剩下一只猴子为止，那只猴子就叫做大王。要求编程模拟此过程，
 * 输入m、n, 输出最后那个大王的编号。
 */
function king($n, $m){
    $range = range(1, $n);
    $i = 0;
    while (count($range) > 1 ) {
        $i++;
        $head = array_pop($range);
        if ($i % $m != 0) {
            array_push($range, $head);
        }
    }
    return $range;
}
$king = king(2, 19);

/**
 * 3、
 * 得分计算，已知道选题提交的答案是
 * $commits= 'A,B,B,A,C,C,D,A,B,C,D,C,C,C,D,A,B,C,D,A';
 * 实际的答案是：
 * $answers= 'A,A,B,A,D,C,D,A,A,C,C,D,C,D,A,B,C,D,C,D'
 * 每题得分是5分，那么这个同学得分是多少？
 *
 * 这题主要计算带索引情况下数组的交集
 */
function test()
{
    $commits= 'A,B,B,A,C,C,D,A,B,C,D,C,C,C,D,A,B,C,D,A';
    $answers= 'A,A,B,A,D,C,D,A,A,C,C,D,C,D,A,B,C,D,C,D';
    $c = explode(',', $commits);
    $a = explode(',', $answers);
    $res_arr = array_intersect_assoc($c, $a);
    return count($res_arr) * 5;
}
var_dump(test());

/**
 * 4、
 * 应用：使用php://input接收post提交的参数，从db中获取数据，并使用var_export写入文件缓存，下次访问从文件中获取数据。
 */
function getInput()
{
    // 当请求头是x-www-form-urlencode,可以使用如下方式实现与直接使用$_POST效果相同，
    // 但是当请求头是raw格式（比如body中的内容是json字符串）$_POST无法获取，此时最好使用php://input
    $request_data = file_get_contents('php://input');
    $request_arr = explode('&', $request_data);
    foreach($request_arr as $item) {
        $item_arr = explode('=', $item);
        $_POST[$item_arr[0]] = $item_arr[1];
    }
}
function fileCache()
{
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=mysql', 'root', 'root');
        $sql = 'SELECT * FROM user';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $str = '<php return '. var_export($rows, true);
        file_put_contents('test.txt', $str);

        $dbh = null;
    } catch (PDOException $e) {
        echo 'Error:'.$e->getMessage();
        die;
    }

    if (file_exists('test.txt')) {
        require 'test.txt';
    }
}

/**
 * 5、
 * 对象数组式访问接口
 */
class ObjArray implements ArrayAccess
{
    private $container = [];

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->container[$offset] ?: NULL;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container['offset'] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }
}


/**
 * 6、
 * 有1000瓶水，其中有一瓶有毒，小白鼠只要尝一点带毒的水24小时后就会死亡，
 * 问至少要多少只小白鼠才能在24小时鉴别出哪瓶水有毒？
 * 这题计算2的多少次方等于1000
 */
function mouse($num) {
    // 先求以2为底，$num的对数，然后进一法取整
    return ceil(log($num, 2));
}
echo mouse(1000), PHP_EOL;

/**
 * 7、
 * 使用serialize序列化一个对象，并使用__sleep和__wakeup方法。
 */
class Connection
{
    protected $link;
    private $server, $username, $password, $db;

    public function __construct($server, $username, $password, $db)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->db = $db;
        $this->connect();
    }
    private function connect()
    {
        $this->link = mysql_connect($this->server, $this->username, $this->password);
        mysql_select_db($this->db, $this->link);
    }
    public function __sleep()
    {
        return array('server', 'username', 'password', 'db');
    }
    public function __wakeup()
    {
        $this->connect();
    }
}

/**
 * 8、
 * 利用数组栈实现翻转字符串功能
 */
function reversalArr($str){
    if(!is_string($str)) {
        return "Input must a string.";
    }
    $arr = explode(',', $str);
    $tmp = [];
    foreach ($arr as $value){
        $val = array_pop($arr);
        array_push($tmp, $val);
    }
    return implode(',',$tmp);
}

/**
 * 9、
 * 从m个数中选出n个数来 ( 0 < n <= m) ，要求n个数之间不能有重复，其和等于一个定值k，求一段程序，罗列所有的可能。
 * 例如备选的数字是：11, 18, 12, 1, -2, 20, 8, 10, 7, 6 ，和k等于：18
 */
define('K', 18);
$nums = array(11, 18, 12, 1, -2, 20, 8, 10, 7, 6);
$numscount = count($nums);
//每一次左移动都表示“乘以2”。
$subscount = 2 << ($numscount - 1);
for ($i = 1; $i < $subscount; $i++) {
    $subitem = array();
    $binstr = decbin($i);
    //填充左边0，实现0补全
    $binstr = str_pad($binstr, $numscount, '0', STR_PAD_LEFT);
    for ($j = 0; $j < $numscount; $j++) {
        if (1 == $binstr[$j]) {
            $subitem[] = $nums[$j];
        }
    }
    if (K == array_sum($subitem)) {
        echo json_encode($subitem) . "\n";
    }
}


