<?php
$input = file('input');

$reindeer = [];
$stats = [];

foreach($input as $r) {
  preg_match("/([A-Za-z]+) can fly (\d+) km\/s for (\d+) seconds, but then must rest for (\d+) seconds./", $r, $matches);
  list(,$name, $v, $time, $rest) = $matches;
  $reindeer[$name] = ["velocity" => (int)$v, "time" => (int)$time, "rest" => (int)$rest];
}

$time = 2503;

$distances=[];

foreach ($reindeer as $name => $r) {
  $velocity = $r['velocity'];
  $fly_time = $r['time'];
  $rest_time = $r['rest'];
  $num_splits = floor($time / ($fly_time + $rest_time));
  $distance_flown = $num_splits * $fly_time * $velocity;
  $time_left = $time % ($fly_time + $rest_time);

  $last_split_distance = ($time_left >= $fly_time ? $fly_time * $velocity : $fly_time * $time_left);

  $distance = $distance_flown + $last_split_distance;
  $distances[$name] = $distance;
}

var_dump($distances);
 ?>
