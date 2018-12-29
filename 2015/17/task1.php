<?php
$input = file('input');

$num_containers = count($input);

$total = pow(2, $num_containers);
$fillable = [];
for ($i = 0; $i < $total; $i++) {
  $containers = [];
    for ($j = 0; $j < $num_containers; $j++) {
        if (pow(2, $j) & $i) {
          $containers[] = $j;
        }
    }
    $fillable[] = $containers;
}

$satisfies = [];
foreach($fillable as $containers) {
  // print_r($containers);
  $good = sum_containers($containers);
  if ($good) {
    $satisfies[] = implode($good, ',');
  }
}

$satisfies = array_unique($satisfies);
echo count($satisfies) . "\n";

// Will return the set that can be summed to 150
// otherwise null
function sum_containers($containers) {
  global $input;
  $sum = 0;
  $subset=[];
  foreach($containers as $container) {
    // var_dump((int)trim($input[$container]));
    $sum += (int)trim($input[$container]);
    $subset[] = $container;
    // echo $sum . "\n";
    if ($sum===150) {
      return $subset;
    }
  }
  return NULL;
}
