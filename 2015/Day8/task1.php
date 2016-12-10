<?php
$input = file('input');
$chars_total = 0;
$actual_total = 0;
foreach ($input as $line) {
  $chars = strlen($line) - 1;
  $chars_total += $chars;
  $interpreted = eval("return $line;");
  $actual = strlen($interpreted);
  $actual_total += $actual;
}
echo "$chars_total - $actual_total\n";
echo $chars_total - $actual_total;
 ?>
