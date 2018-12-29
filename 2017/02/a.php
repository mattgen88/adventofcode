<?php
$input = file("input.txt");
$checksum = 0;
foreach($input as $line) {
	$line = explode("\t", trim($line));
	$min = min($line);
	$max = max($line);
	$checksum += $max - $min;
}
var_dump($checksum);


