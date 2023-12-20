<?php

$input = file_get_contents("input.txt");
// Determine how big our square has to be for the input

$factor = 1;
$count = 1;
do {
  $count = $factor * $factor;
  echo "Square size $factor x $factor has count of $count\n";
} while ($count < $input && $factor+=2 );
echo "Square size = $factor X $factor\n";

// We know that the last number filled is also $factor, $factor
// We can then calculate distance to $factor, $factor
// We know that numbers fill in a pattern, we can then use that pattern to walk
// back to get the coordinate of that entry
// Then we can calculate manhattan distance from there
$origin["x"] = floor($factor/2);
$origin["y"] = floor($factor/2);
echo "start is at ".floor($factor/2).",".floor($factor/2)."\n";
echo "$count is at ".$factor.",".$factor."\n";
echo "Difference between $input and $count is " . ($count-$input) ."\n";

$coordinate = [];
$diff = $count-$input;
echo $diff . "\n";
echo $factor . "\n";
echo $input . "\n";
if ($diff === 0) {
  $coordinate["x"] = $factor-1;
  $coordinate["y"] = $factor-1;
  echo "Coordinate is at " . $coordinate["x"] . "," . $coordinate["y"] . "\n";
} else if ($diff > ($factor-1)*3) {
  $coordinate["x"] = ($factor - 1);
  $coordinate["y"] = ($diff - (($factor-1)*3));
  echo "Coordinate is at " . $coordinate["x"] . "," . $coordinate["y"] . "\n";
} else if ($diff > ($factor-1)*2) {
  $coordinate["x"] = ($diff - (($factor - 1) * 2));
  $coordinate["y"] = 0;
  echo "Coordinate is at " . $coordinate["x"] . "," . $coordinate["y"] . "\n";
} else if ($diff > $factor-1) {
  $coordinate["x"] = 0;
  $coordinate["y"] = $factor-1 - ($diff - ($factor - 1));
  echo "Coordinate is at " . $coordinate["x"] . "," . $coordinate["y"] . "\n";
} else {
  $coordinate["x"] = (($factor - 1) - $diff);
  $coordinate["y"] = ($factor - 1);
  echo "Coordinate is at " . $coordinate["x"] . "," . $coordinate["y"] . "\n";
}

echo "Manhattan distance from " . $origin["x"] . "," . $origin["y"] ." to " . $coordinate["x"] . "," . $coordinate["y"] . "\n";


echo abs($origin["x"] - $coordinate["x"]) + abs($origin["y"] - $coordinate["y"])."\n";
exit();


$val = 1;
$x = $origin["x"];
$y = $origin["y"];
$DIR="R";
$target = $input;
$shell = 1;

var_dump(step($val, $x, $y, $factor, $DIR, $target, $shell));

function step($val, $x, $y, $factor, $DIR, $target, $shell) {
  // given x, y, what shell are we in?

  $shell_max = floor($shell/2) + floor($factor/2); // $shell by $shell grid
  $shell_min = floor($shell/2) - floor($factor/2);

  echo "$val at $x,$y, moving $DIR looking for $target\n";
  printgrid(["x" => floor($factor/2), "y" => floor($factor/2)], ["x" => $x, "y" => $y], $factor);
  // Decide where to go next
  switch($DIR) {
    case "R":
      if ($y+1 > $shell_max) {
        $DIR="U";
        return step($val, $x, $y, $factor, $DIR, $target, $shell);
      } else {
        $shell++;
        $y++;
      }
      // R -> U
    break;
    case "L":
      if ($y-1 < $shell_min) {
        $DIR="D";
        return step($val, $x, $factor, $y, $DIR, $target, $shell);
      } else {
        $y--;
      }
      // L -> D
    break;
    case "U":
      if ($x+1 >= $shell_max) {
        $DIR="L";
        return step($val, $x, $factor, $y, $DIR, $target, $shell);
      } else {
        $x++;
      }
      // U -> L
    break;
    case "D":
      if ($x-1 <= $shell_min) {
        $DIR="R";
        return step($val, $x, $factor, $y, $DIR, $target, $shell);
      } else {
        $x--;
      }
      // D -> R
    break;
  }
  $val++;
  if ($val === $target) {
    return [$x, $y];
  }
  return step($val, $x, $y, $factor, $DIR, $target, $shell);
}

function printgrid($origin, $current, $size) {
  system("clear");
  var_dump($origin, $current, $size);
  for ($x = 0; $x < $size; $x++) {
    for ($y = 0; $y < $size; $y++) {
      if (($y == $origin['x'] && $x == $origin['y']) || ($y == $current['x'] && $x == $current['y'])) {
        echo "x ";
      } else {
        echo "o ";
      }
    }
    echo "\n";
  }
  sleep(1);
}
