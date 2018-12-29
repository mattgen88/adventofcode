<?php

$input = file_get_contents('input.txt');

$input = trim($input);

$sum = 0;
for($i = 0; $i< strlen($input) - 1; $i++) {
	echo "Comparing ".$input[$i]." to " . $input[$i+1] . "\n";
	if ($input[$i] === $input[$i+1]) {
		echo "Summing\n";
		$sum += $input[$i];
	}
}
echo "Comparing ".$input[0]." to " . $input[strlen($input)-1] . "\n";
if ($input[0] === $input[strlen($input)-1]) {
	echo "Summing\n";
	$sum += $input[strlen($input)-1];
}

echo $sum;
