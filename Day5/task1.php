<?php

$input = file('input');

$count = 0;
foreach ($input as $line) {
	if (preg_match('/(ab|cd|pq|xy)/', $line))
	{
		echo "Skipping $line";
		continue;
	}

	if (preg_match('/[aeiou].*[aeiou].*[aeiou]/', $line))
	{
		if (preg_match('/([a-z])\1/', $line)) {
			echo "Passed $line\n";
			$count++;
		}
	}
}

echo $count;

