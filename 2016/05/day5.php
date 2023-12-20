<?php

$input = file_get_contents("input.txt");
$i=0;
$password = "________";
$count = 0;
do {
  // if ($i % 1000 === 0) {
  //   echo "input: " .$i . "\n";
  // }
  $tohash = $input . $i;
  $md5 = md5($tohash);
  if (substr($md5, 0, 5) === "00000") {
    $char = $md5[6];
    $pos = $md5[5];
    echo "Looking at " . $char . " for pos " . $pos . "\n";
    if (hexdec($pos) >= 8 || $password[$pos] !== '_') {
      continue;
    }
    echo "Found char\n";
    $count++;
    $password[$pos] = $char;
    echo $password . "\n";
  }
} while($i++ >= 0 && $count < 8);

var_dump($password);
 ?>
