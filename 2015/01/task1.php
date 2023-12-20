<?php

$input = file_get_contents("input.txt");

$floor = 0;
for($i = 0; $i < strlen($input); $i++) {
	switch ($input[$i]) {
		case '(':
			$floor++;
			break;
		case ')':
			$floor--;
			break;
	}
}
echo $floor;