<?php
$input = explode(", ", "R3, L2, L2, R4, L1, R2, R3, R4, L2, R4, L2, L5, L1, R5, R2, R2, L1, R4, R1, L5, L3, R4, R3, R1, L1, L5, L4, L2, R5, L3, L4, R3, R1, L3, R1, L3, R3, L4, R2, R5, L190, R2, L3, R47, R4, L3, R78, L1, R3, R190, R4, L3, R4, R2, R5, R3, R4, R3, L1, L4, R3, L4, R1, L4, L5, R3, L3, L4, R1, R2, L4, L3, R3, R3, L2, L5, R1, L4, L1, R5, L5, R1, R5, L4, R2, L2, R1, L5, L4, R4, R4, R3, R2, R3, L1, R4, R5, L2, L5, L4, L1, R4, L4, R4, L4, R1, R5, L1, R1, L5, R5, R1, R1, L3, L1, R4, L1, L4, L4, L3, R1, R4, R1, R1, R2, L5, L2, R4, L1, R3, L5, L2, R5, L4, R5, L5, R3, R4, L3, L3, L2, R2, L5, L5, R3, R4, R3, R4, R3, R1");
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
