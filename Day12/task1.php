<?php
$input = file_get_contents("input");

preg_match_all("/(?!\")(-?\d+)/", $input, $matches);
$sum = 0;
foreach ($matches[0] as $num) {
  $sum += (int) $num;
}
echo $sum ."\n";
 ?>
