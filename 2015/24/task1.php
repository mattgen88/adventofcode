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

$input = file_get_contents("input.txt");

$input = explode("\n", $input);
$sum = 0;
foreach ($input as $num) {
  $sum += (int)$num;
}

$target_weight = $sum/3;// 516, need to make 3 groups adding up to this amount
// by eye, 113 + 109 + 107 + 103 + 83 + 1 is the smallest set you can do
// which means we can start by sampling 6
// by eye

$sets = [];
$min_qe = null;
foreach (new Combinations("0123456789ABCDEFGHIJKLMNOPQR", 6 ) as $substring) {
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
    echo "$set ($qe)\n";
    if ($min_qe == null || $qe < $min_qe)
      $min_qe = $qe;
  }
}
echo $min_qe . "\n";