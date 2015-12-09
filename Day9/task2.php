<?php
$input = file('input');
$location_names = [];
$locations = [];
foreach ($input as $location) {
  preg_match('/([A-Za-z]+) to ([A-Za-z]+) = (\d+)/', $location, $matches);
  $start = $matches[1];
  $end = $matches[2];
  $distance = $matches[3];
  $locations[$start][$end] = $distance;
  $locations[$end][$start] = $distance;
  if (!in_array($start,$location_names)) {
    $location_names[] = $start;
  }
  if (!in_array($end,$location_names)) {
    $location_names[] = $end;
  }
}

function pc_next_permutation($p, $size) {
    // slide down the array looking for where we're smaller than the next guy
    for ($i = $size - 1; $p[$i] >= $p[$i+1]; --$i) { }

    // if this doesn't occur, we've finished our permutations
    // the array is reversed: (1, 2, 3, 4) => (4, 3, 2, 1)
    if ($i == -1) { return false; }

    // slide down the array looking for a bigger number than what we found before
    for ($j = $size; $p[$j] <= $p[$i]; --$j) { }

    // swap them
    $tmp = $p[$i]; $p[$i] = $p[$j]; $p[$j] = $tmp;

    // now reverse the elements in between by swapping the ends
    for (++$i, $j = $size; $i < $j; ++$i, --$j) {
         $tmp = $p[$i]; $p[$i] = $p[$j]; $p[$j] = $tmp;
    }

    return $p;
}

$set = $location_names; // like array('she', 'sells', 'seashells')
$size = count($set) - 1;
$perm = range(0, $size);
$j = 0;

do {
     foreach ($perm as $i) {
       $perms[$j][] = $set[$i];
     }
     $j++;
} while ($perm = pc_next_permutation($perm, $size));

$distances = [];
foreach ($perms as $p) {
    $distance = 0;
    $last = $p[0];
    $total_distance = 0;
    foreach ($p as $l) {
      if ($last !== $l) {
        $total_distance += $locations[$last][$l];
      }
      $last = $l;
    }
    $distances[] = $total_distance;
    echo implode($p, ' -> ');
    echo " $total_distance\n";
}
echo "Answer " . max($distances) . "\n";
 ?>
