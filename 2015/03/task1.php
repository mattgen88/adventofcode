<?php
$input = file_get_contents("input.txt");
$grid = [];
$x = 0;
$y = 0;
$grid['0|0'] = 1;
$houses = 1;
for ($i=0; $i < strlen($input); $i++) {
	$direction = $input[$i];
	switch ($direction) {
		case "^":
			// north
			$y++;
		break;
		case "v":
			// south
			$y--;
		break;
		case ">":
			// east
			$x++;
		break;
		case "<":
			//west
			$x--;
		break;
	}
	echo "Delivering to {$y} , {$x}\n";
	if (!isset($grid[$y . '|' . $x]))
	{
		$houses++;
	}
	@$grid[$y . '|' . $x] += 1;
}
//var_dump($grid);
echo $houses;