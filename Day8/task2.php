<?php
$input = file('input');
$chars_total = 0;
$actual_total = 0;
foreach ($input as $line) {
  $chars = strlen("\"" . addslashes(trim($line)) . "\"");
  $chars_total += $chars;
  $chars = strlen($line) - 1;
  $actual_total += $chars;
}
echo "$chars_total - $actual_total\n";
echo $chars_total - $actual_total;
 ?>
