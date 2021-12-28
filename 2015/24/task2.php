<?php

class Combinations implements Iterator
{
    protected $c = null;
    protected $s = null;
    protected $n = 0;
    protected $k = 0;
    protected $pos = 0;

    function __construct($s, $k) {
        if(is_array($s)) {
            $this->s = array_values($s);
            $this->n = count($this->s);
        } else {
            $this->s = (string) $s;
            $this->n = strlen($this->s);
        }
        $this->k = $k;
        $this->rewind();
    }
    function key() {
        return $this->pos;
    }
    function current() {
        $r = array();
        for($i = 0; $i < $this->k; $i++)
            $r[] = $this->s[$this->c[$i]];
        return is_array($this->s) ? $r : implode('', $r);
    }
    function next() {
        if($this->_next())
            $this->pos++;
        else
            $this->pos = -1;
    }
    function rewind() {
        $this->c = range(0, $this->k);
        $this->pos = 0;
    }
    function valid() {
        return $this->pos >= 0;
    }

    protected function _next() {
        $i = $this->k - 1;
        while ($i >= 0 && $this->c[$i] == $this->n - $this->k + $i)
            $i--;
        if($i < 0)
            return false;
        $this->c[$i]++;
        while($i++ < $this->k - 1)
            $this->c[$i] = $this->c[$i - 1] + 1;
        return true;
    }
}

$input = "1
3
5
11
13
17
19
23
29
31
41
43
47
53
59
61
67
71
73
79
83
89
97
101
103
107
109
113";

$input = explode("\n", $input);
$sum = 0;
foreach ($input as $num) {
    $sum += (int)$num;
}

$target_weight = $sum/4;// 387, need to make 4 groups adding up to this amount

$sets = [];
$min_qe = null;
for($i = 3; $i < 10; $i++)
foreach (new Combinations("0123456789ABCDEFGHIJKLMNOPQR", $i ) as $substring) {
    $sum = 0;
    $qe = 1;
    $set = "";
    foreach(str_split($substring) as $char) {
        $weight = (int)$input[(int)base_convert($char, 36, 10)];
        $qe *= $weight;
        $sum += $weight;
        $set .= $input[(int)base_convert($char, 36, 10)] . ' ';
    }
    if ($sum == $target_weight) {
        $sets[] = $set;
        echo "$set ($qe) ";
        if ($min_qe == null || $qe < $min_qe) {
            $min_qe = $qe;
        }
        echo "Min QE: $min_qe\n";
    }
}
echo $min_qe . "\n";