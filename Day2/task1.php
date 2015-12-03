<?php

$input = file_get_contents("input");

$input = explode("\n", $input);

$total = 0;
foreach ($input as $dims) {
	$sides = explode("x", $dims);
	rsort($sides, SORT_NUMERIC);
	list ($w, $h, $l) = $sides;
	$surfaceArea = 2*$l*$w + 2*$w*$h + 2*$h*$l;
	$smallestSide = $sides[1] * $sides[2];
	$total += $surfaceArea + $smallestSide;
}
echo $total;