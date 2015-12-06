<?php

$input = file('input');
$grid = [[]];


for ($i = 0; $i <= 999; $i++) {
	for ($j = 0; $j <= 999; $j++) {
		$grid[$i][$j] = 0;
	}
}

foreach ($input as $instruction) {
	preg_match("/(\d+),(\d+) through (\d+),(\d+)/", $instruction, $matches);
	list(, $start_x, $start_y, $end_x, $end_y) = $matches;

	if (strstr($instruction, "turn on")) {
		// Turn on
		configLights($start_x, $end_x, $start_y, $end_y, 1, $grid);
	} else if (strstr($instruction, "turn off")) {
		// Turn off
		configLights($start_x, $end_x, $start_y, $end_y, 0, $grid);
	} else if (strstr($instruction, "toggle")) {
		// Toggle
		configLights($start_x, $end_x, $start_y, $end_y, 'toggle', $grid);
	}
}

countLights($grid);

function countLights($grid) {
	$on = 0;

	for ($i = 0; $i < count($grid); $i++) {
		for ($j = 0; $j < count($grid[$i]); $j++) {
			$on += $grid[$i][$j];
		}
	}
	echo "$on\n";
}

function configLights($start_x, $end_x, $start_y, $end_y, $config, &$grid) {
	//echo "start_x: $start_x, end_x: $end_x, start_y: $start_y, end_y: $end_y $config \n";
	for ($i = $start_x; $i <= $end_x; $i++) {
		for ($j = $start_y; $j<= $end_y; $j++) {
			//echo "$i, $j = " . ($config == 'toggle'? ($grid[$i][$j] + 1) % 2 : $config) . "\n";
			$grid[$i][$j] = ($config == 'toggle') ? ($grid[$i][$j] + 1) % 1 : $config;
		}
	}
}
?>
