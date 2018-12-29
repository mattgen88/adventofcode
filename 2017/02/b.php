<?php
$input = file("input.txt");
$checksum = 0;
foreach($input as $line) {
	$line = explode("\t", trim($line));
	foreach ($line as $el) {
		foreach ($line as $el2) {
			if($el !== $el2 && trim($el) % trim($el2) === 0) {
				var_dump([$el, $el2]);
				$checksum += $el / $el2;
				break;
			}
		}
	}
}
var_dump($checksum);


