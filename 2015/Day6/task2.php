<?php

$input = file('input');
//$input = ['turn on 0,0 through 4,9', 'turn off 0,0 through 0,0', 'turn off 1,1 through 1,1', 'turn off 0,0 through 9,0'];
$grid = [];

define('GRID_SIZE', 999);
for ($i = 0; $i <= GRID_SIZE; $i++) {
	for ($j = 0; $j <= GRID_SIZE; $j++) {
		$grid[$i . ',' . $j] = 0;
	}
}

foreach ($input as $instruction) {
	preg_match("/(\d+),(\d+) through (\d+),(\d+)/", $instruction, $matches);
	list(, $start_x, $start_y, $end_x, $end_y) = $matches;

	if (strstr($instruction, "turn on") !== FALSE) {
		// Turn on
		configLights($start_x, $end_x, $start_y, $end_y, 1, $grid);
	} else if (strstr($instruction, "turn off") !== FALSE) {
		// Turn off
		configLights($start_x, $end_x, $start_y, $end_y, 0, $grid);
	} else if (strstr($instruction, "toggle") !== FALSE) {
		// Toggle
		configLights($start_x, $end_x, $start_y, $end_y, 'toggle', $grid);
	}
}

countLights($grid);

function countLights($grid) {
	$on = 0;

	for ($i = 0; $i <= GRID_SIZE; $i++) {
		for ($j = 0; $j <= GRID_SIZE; $j++) {
			$on += $grid[$i . ',' . $j];
		}
	}
	echo "$on\n";
}

function configLights($start_x, $end_x, $start_y, $end_y, $config, &$grid) {
	//echo "start_x: $start_x, end_x: $end_x, start_y: $start_y, end_y: $end_y $config \n";
	for ($i = $start_x; $i <= $end_x; $i++) {
		for ($j = $start_y; $j<= $end_y; $j++) {
			//echo "$i, $j = " . ($config == 'toggle'? ($grid[$i][$j] + 1) % 2 : $config) . "\n";
			$current = $grid[$i . ',' . $j];
			if ($config === 0 && $current === 0) {
				continue;
			}
			$grid[$i . ',' . $j] += ($config === 'toggle') ? 2 : ($config === 0 ? -1 : 1);
		}
	}
}
?>
