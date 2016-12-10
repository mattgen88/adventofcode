<?php

$input = file('input');

$count = 0;
foreach ($input as $line) {
	if (preg_match('/(..).*\1/', $line))
	{
		if (preg_match('/(.).\1/', $line)) {
			echo "Passed $line\n";
			$count++;
		}
	}
}

echo $count;

