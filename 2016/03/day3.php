<?php
$input = file_get_contents("input.txt");


$dimsCola = $dimsColb = $dimsColc = [];
$dimslist = explode("\n", $input);
foreach ($dimslist as $dims) {
  list($a, $b, $c) = preg_split("/[ ]+/", trim($dims));
  $dimsCola[] = $a;
  $dimsColb[] = $b;
  $dimsColc[] = $c;
}
$dimsCols = array_merge($dimsCola, $dimsColb, $dimsColc);

$total = 0;


for($i = 0; $i < count($dimsCols); $i=$i+3) {
  $a = $dimsCols[$i];
  $b = $dimsCols[$i+1];
  $c = $dimsCols[$i+2];

  if ((int) $a  + (int) $b <= (int) $c || $a + $c <= $b || $b + $c <= $a) {
    echo "Invalid triangle\n";
  } else {
    $total++;
    echo "Valid triangle\n";
  }
}
var_dump($total);
 ?>
