<?php
$input = file_get_contents("input.txt");
$grid = [];
$x = 0;
$y = 0;
$grid['0|0'] = 1;
$santa = ['x' => 0, 'y' => 0];
$robosanta = ['x' => 0, 'y' => 0];
$houses = 1;
$turn = 'santa';
for ($i=0; $i < strlen($input); $i++) {
	$x = ${$turn}['x'];
	$y = ${$turn}['y'];
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
	if (!isset($grid[$y . '|' . $x]))
	{
		$houses++;
	}
	@$grid[$y . '|' . $x] += 1;

	${$turn}['x'] = $x;
	${$turn}['y'] = $y;
	$turn = $turn == 'santa' ? 'robosanta' : 'santa';
}
echo $houses;