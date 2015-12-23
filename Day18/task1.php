<?php
$input = file('input', FILE_IGNORE_NEW_LINES);
$grid=[];

foreach($input as $line) {
  $lights = [];
  for ($i = 0; $i < strlen($line); $i++) {
    $lights[] = $line[$i];
  }
  $grid[] = $lights;
}

$gridprime = [];
for ($i = 0; $i < 100; $i++) {
  print_grid($grid);
  // Update grid
  $gridprime = update_grid($grid);
  // Copy new grid back
  $grid = $gridprime;
  usleep(50000);
}

count_lights($grid);

function print_grid($grid) {
  echo "\n\n\n\n\n\n";
  ob_start();
  for ($row = 0; $row < count($grid); $row++) {
    for ($col = 0; $col < count($grid[$row]); $col++) {
      echo $grid[$row][$col];
    }
    echo "\n";
  }
  ob_end_flush();
}

function update_grid($grid) {
  $new_grid = [];
  for ($row = 0; $row < count($grid); $row++) {
    for ($col = 0; $col < count($grid[$row]); $col++) {
      $current_state = $grid[$row][$col];
      @$on_count = substr_count(
        $grid[$row - 1][$col - 1] .
        $grid[$row - 1][$col] .
        $grid[$row - 1][$col + 1] .
        $grid[$row][$col - 1] .
        $grid[$row][$col + 1] .
        $grid[$row + 1][$col - 1] .
        $grid[$row + 1][$col] .
        $grid[$row + 1][$col + 1]
        , "#");
        if ($current_state === ".") {
          // Off
          $new_grid[$row][$col] = $on_count === 3 ? "#" : ".";
        } else if($current_state === "#") {
          // On
          $new_grid[$row][$col] = $on_count === 2 || $on_count === 3 ? "#" : ".";
        } else {
          throw new Exception("Assertion failed, invalid state");
        }
    }
  }
  return $new_grid;
}

function count_lights($grid) {
  $lights = 0;
  for ($row = 0; $row < count($grid); $row++) {
    for ($col = 0; $col < count($grid[$row]); $col++) {
      $lights += $grid[$row][$col] === "#" ? 1 : 0;
    }
  }
  echo $lights . "\n";
}

 ?>
