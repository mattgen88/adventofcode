<?php

$keypad = [
  [1,2,3],
  [4,5,6],
  [7,8,9],
];
$keypad = [
  [false, false, 1, false, false],
  [false, 2, 3, 4, false],
  [5, 6, 7, 8, 9],
  [false, 'A', 'B', 'C', FALSE],
  [false, false, 'D', false, false],
];

$inputString = file_get_contents("input.txt");
$input = explode("\n", $inputString);

$point = ["x" => 1, "y" => 1]; // 5 in $keypad
$point = ["x" => 0, "y" => 2]; // 5 in $keypad
echo "Starting at " . $keypad[$point["y"]][$point["x"]] . "\n";
foreach ($input as $keyCommands) {
  for( $i = 0; $i < strlen($keyCommands); $i++ ) {
    tryMove($keyCommands[$i], $point, $keypad);
  }
  echo $keypad[$point["y"]][$point["x"]];
}
echo "\n";

function tryMove($dir, &$point, $grid) {
  switch ($dir) {
    case "R":
      if (isset($grid[$point["y"]][$point["x"] + 1]) && $point["x"] + 1 < count($grid[0]) && $grid[$point["y"]][$point["x"] + 1] !== FALSE) {
        $point["x"]++;
      } else {
      }
      break;
    case "L":
      if (isset($grid[$point["y"]][$point["x"] - 1]) && $point["x"] - 1 >= 0 && $grid[$point["y"]][$point["x"] - 1] !== FALSE) {
        $point["x"]--;
      } else {
      }
      break;
    case "U":
      if (isset($grid[$point["y"] - 1][$point["x"]]) && $point["y"] - 1 >= 0 && $grid[$point["y"] - 1][$point["x"]] !== FALSE) {
        $point["y"]--;
      } else {
      }
      break;
    case "D":
      if (isset($grid[$point["y"] + 1][$point["x"]]) && $point["y"] + 1 < count($grid) && $grid[$point["y"] + 1][$point["x"]] !== FALSE) {
        $point["y"]++;
      } else {
      }
      break;
  }
}

?>
