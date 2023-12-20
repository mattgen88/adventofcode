<?php
$input = explode(", ", file_get_contents("input.txt"));
$map = [
  'NORTH', 'EAST', 'SOUTH', 'WEST'
];
$dir = 0;
$x = 0;
$y = 0;
$visited = [];
$easterbunny = null;
foreach($input as $instruction) {
  $change = $instruction[0];
  $distance = substr($instruction, 1);
  $dir = (($dir + ($change == 'R' ? 1 : 3)) % 4);
  //echo "rotate " . $change . " to " . $map[$dir] . " and travel " . $distance . "\n";
  for($i = 0; $i < $distance; $i++) {
    if ($map[$dir] === 'NORTH' || $map[$dir] === 'SOUTH') {
      $y += ($map[$dir] === 'NORTH' ? 1 : -1);
    } else {
      $x += ($map[$dir] === 'EAST' ? 1 : -1);
    }
    //echo "current distance x: ". $x ." current distance y: ". $y ."\n";
    if (!$easterbunny && isset($visited[$x][$y])) {
      echo "visited " . $x .", " . $y . " before!\n";
	  echo abs(0 - $x) + abs(0 - $y) . " is the distance\n";
	  $easterbunny = [$x, $y];
    }
    $visited[$x][$y] = true;
  }
}
echo "Final coordinates: ($x, $y)\n";
echo abs(0 - $x) + abs(0 - $y) . " is the distance\n";
 ?>
