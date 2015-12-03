<?php

$input = file_get_contents("input");

$input = explode("\n", $input);

$total = 0;
foreach ($input as $dims) {
	$sides = explode("x", $dims);
	rsort($sides, SORT_NUMERIC);
	list ($w, $h, $l) = $sides;

	$ribbon = ($sides[1]*2) + ($sides[2]*2) + ($w*$h*$l);
	$total += $ribbon;
}
echo $total;