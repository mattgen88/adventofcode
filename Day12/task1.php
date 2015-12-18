<?php
$input = file_get_contents("input");

preg_match_all("/(?!\")(-?\d+)/", $input, $matches);
echo array_sum($matches[0]) ."\n";
 ?>
