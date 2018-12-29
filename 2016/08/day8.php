<?php
$input = "rect 1x1
rotate row y=0 by 2
rect 1x1
rotate row y=0 by 5
rect 1x1
rotate row y=0 by 3
rect 1x1
rotate row y=0 by 3
rect 2x1
rotate row y=0 by 5
rect 1x1
rotate row y=0 by 5
rect 4x1
rotate row y=0 by 2
rect 1x1
rotate row y=0 by 2
rect 1x1
rotate row y=0 by 5
rect 4x1
rotate row y=0 by 3
rect 2x1
rotate row y=0 by 5
rect 4x1
rotate row y=0 by 2
rect 1x2
rotate row y=1 by 6
rotate row y=0 by 2
rect 1x2
rotate column x=32 by 1
rotate column x=23 by 1
rotate column x=13 by 1
rotate row y=0 by 6
rotate column x=0 by 1
rect 5x1
rotate row y=0 by 2
rotate column x=30 by 1
rotate row y=1 by 20
rotate row y=0 by 18
rotate column x=13 by 1
rotate column x=10 by 1
rotate column x=7 by 1
rotate column x=2 by 1
rotate column x=0 by 1
rect 17x1
rotate column x=16 by 3
rotate row y=3 by 7
rotate row y=0 by 5
rotate column x=2 by 1
rotate column x=0 by 1
rect 4x1
rotate column x=28 by 1
rotate row y=1 by 24
rotate row y=0 by 21
rotate column x=19 by 1
rotate column x=17 by 1
rotate column x=16 by 1
rotate column x=14 by 1
rotate column x=12 by 2
rotate column x=11 by 1
rotate column x=9 by 1
rotate column x=8 by 1
rotate column x=7 by 1
rotate column x=6 by 1
rotate column x=4 by 1
rotate column x=2 by 1
rotate column x=0 by 1
rect 20x1
rotate column x=47 by 1
rotate column x=40 by 2
rotate column x=35 by 2
rotate column x=30 by 2
rotate column x=10 by 3
rotate column x=5 by 3
rotate row y=4 by 20
rotate row y=3 by 10
rotate row y=2 by 20
rotate row y=1 by 16
rotate row y=0 by 9
rotate column x=7 by 2
rotate column x=5 by 2
rotate column x=3 by 2
rotate column x=0 by 2
rect 9x2
rotate column x=22 by 2
rotate row y=3 by 40
rotate row y=1 by 20
rotate row y=0 by 20
rotate column x=18 by 1
rotate column x=17 by 2
rotate column x=16 by 1
rotate column x=15 by 2
rotate column x=13 by 1
rotate column x=12 by 1
rotate column x=11 by 1
rotate column x=10 by 1
rotate column x=8 by 3
rotate column x=7 by 1
rotate column x=6 by 1
rotate column x=5 by 1
rotate column x=3 by 1
rotate column x=2 by 1
rotate column x=1 by 1
rotate column x=0 by 1
rect 19x1
rotate column x=44 by 2
rotate column x=40 by 3
rotate column x=29 by 1
rotate column x=27 by 2
rotate column x=25 by 5
rotate column x=24 by 2
rotate column x=22 by 2
rotate column x=20 by 5
rotate column x=14 by 3
rotate column x=12 by 2
rotate column x=10 by 4
rotate column x=9 by 3
rotate column x=7 by 3
rotate column x=3 by 5
rotate column x=2 by 2
rotate row y=5 by 10
rotate row y=4 by 8
rotate row y=3 by 8
rotate row y=2 by 48
rotate row y=1 by 47
rotate row y=0 by 40
rotate column x=47 by 5
rotate column x=46 by 5
rotate column x=45 by 4
rotate column x=43 by 2
rotate column x=42 by 3
rotate column x=41 by 2
rotate column x=38 by 5
rotate column x=37 by 5
rotate column x=36 by 5
rotate column x=33 by 1
rotate column x=28 by 1
rotate column x=27 by 5
rotate column x=26 by 5
rotate column x=25 by 1
rotate column x=23 by 5
rotate column x=22 by 1
rotate column x=21 by 2
rotate column x=18 by 1
rotate column x=17 by 3
rotate column x=12 by 2
rotate column x=11 by 2
rotate column x=7 by 5
rotate column x=6 by 5
rotate column x=5 by 4
rotate column x=3 by 5
rotate column x=2 by 5
rotate column x=1 by 3
rotate column x=0 by 4";

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
