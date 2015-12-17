<?php

$input = file('input');

foreach ($input as $line) {
  preg_match('/Sue (\d+): (\w+): (\d+), (\w+): (\d+), (\w+): (\d+)/', $line, $matches);
  list(,$num, $i1, $i1_num, $i2, $i2_num, $i3, $i3_num) = $matches;

  $aunts[(int)$num] = [$i1=>(int)$i1_num, $i2=>(int)$i2_num, $i3=>(int)$i3_num];
}

$criteria = [
  'children' => 3,
  'cats' => 7,        //greater than
  'samoyeds' => 2,
  'pomeranians' => 3, //fewer than
  'akitas'=> 0,
  'vizslas'=> 0,
  'goldfish'=> 5,      //fewer than
  'trees' => 3,       //greater than
  'cars' => 2,
  'perfumes' => 1,
];

$best_match = NULL;

foreach($aunts as $num => $sue) {
  echo "testing aunt $num\n";
  $correct = TRUE;
  foreach ($sue as $thing => $score) {
    switch($thing) {
      case 'cats':
        $cats = $score > $criteria['cats'];
        $correct = $correct && $cats;
      break;
      case 'pmeranians':
        $pomeranians = $score < $criteria['pomeranians'];
        $correct = $correct && $pomeranians;
      break;
      case 'goldfish':
        $goldfish = $score < $criteria['goldfish'];
        $correct = $correct && $goldfish;
      break;
      case 'trees':
        $trees = $score > $criteria['trees'];
        $correct = $correct && $trees;
      break;
      default:
        $other = $score === $criteria[$thing];
        $correct = $correct && $other;
      break;
    }
  } // End switch
  if ($correct) {
    echo "The real sue is $num\n";
    exit();
  } else {
    echo "Sue $num failed\n";
  }
} // End foreach

 ?>
