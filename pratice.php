<?php
/**
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
 * 计算数组差集，带索引
 */
function test()
{
    $commits= 'A,B,B,A,C,C,D,A,B,C,D,C,C,C,D,A,B,C,D,A';
    $answers= 'A,A,B,A,D,C,D,A,A,C,C,D,C,D,A,B,C,D,C,D';
    $c = explode(',', $commits);
    $a = explode(',', $answers);
    return array_diff_assoc($c, $a);
}
var_dump(test());


/**
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
 * 有1000瓶水，其中有一瓶有毒，小白鼠只要尝一点带毒的水24小时后就会死亡，
 * 问至少要多少只小白鼠才能在24小时鉴别出哪瓶水有毒？
 * @param int $num
 * @return int
 */
function mouse($num) {
    // 先求以2为底，$num的对数，然后进一法取整
    return ceil(log($num, 2));
}
echo mouse(1000), PHP_EOL;


/**
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
