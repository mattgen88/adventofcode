<?php
$input = file_get_contents("input.txt");

$instructions = explode("\n", $input);
$grid = array_fill(0,6,array_fill(0, 50, FALSE));

$count = 0;
foreach ($instructions as $instruction) {
  update_display($instruction, $grid);
  $count = print_display($grid);
}
var_dump($count);

function print_display($pixels) {
  $on = 0;
  foreach ($pixels as $row) {
    foreach ($row as $cell) {
      echo $cell ? "#" : ".";
      if ($cell) $on++;
    }
    echo "\n";
  }
  echo "\n";
  return $on;
}

function update_display($instruction, &$grid) {
  if (preg_match('/rect (\d+)x(\d+)/', $instruction, $matches) === 1) {
    echo "drawing " . $matches[1] . " " . $matches[2] . "\n";
    // x by y
    // starting at 0,0
    for ($x = 0; $x < $matches[1]; $x++) {
      for ($y = 0; $y < $matches[2]; $y++) {
        $grid[$y][$x] = true;
      }
    }
    return;
  }

  preg_match('/rotate (row|column) (x|y)=(\d+) by (\d+)/', $instruction, $matches);
  if ($matches[1] === 'row') {
    echo "shifting row " . $matches[3] . " " . $matches[4] . " times\n";

    $oldrow = [];
    foreach ($grid[$matches[3]] as $cell) {
      $oldrow[] = $cell;
    }

    $newrow = $oldrow;
    do {
      // merge 1 to the end with first item of $col and save it (rotate by 1)
      $newrow = array_merge(array_slice($newrow, count($newrow)-1, 1, FALSE), array_slice($newrow, 0, count($newrow) - 1, FALSE));
    } while(--$matches[4] > 0);

    foreach ($grid[$matches[3]] as $key => $cell) {
      $grid[$matches[3]][$key] = $newrow[$key];
    }
    return;
  }

  echo "shifting col " . $matches[3] . " " . $matches[4] . " times\n";

  $oldcol = [];
  foreach ($grid as $row) {
    $oldcol[] = $row[$matches[3]];
  }

  $newcol = $oldcol;

  do {
    // merge 1 to the end with first item of $col and save it (rotate by 1)
    $newcol = array_merge(array_slice($newcol, count($newcol)-1, 1, FALSE), array_slice($newcol, 0, count($newcol) - 1, FALSE));
  } while(--$matches[4] > 0);


  foreach ($grid as $key => $row) {
    $grid[$key][$matches[3]] = $newcol[$key];
  }
}
