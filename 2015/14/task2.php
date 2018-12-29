<?php
$input = file('input');
$reindeer = [];
$stats = [];

foreach($input as $r) {
  preg_match("/([A-Za-z]+) can fly (\d+) km\/s for (\d+) seconds, but then must rest for (\d+) seconds./", $r, $matches);
  list(,$name, $v, $time, $rest) = $matches;
  $reindeer[$name] = ["velocity" => (int)$v, "time" => (int)$time, "rest" => (int)$rest];
  $stats[$name]['state'] = 'rest';
  $stats[$name]['duration'] = 0;
  $stats[$name]['points'] = 0;
  $stats[$name]['distance'] = 0;
}

$time = 2503;


for ($i = 0; $i < $time; $i++) {
  foreach ($reindeer as $name => $r) {
    $velocity = $r['velocity'];
    $fly_time = $r['time'];
    $rest_time = $r['rest'];
    if ($stats[$name]['duration'] === 0) {
      // Toggle state of deer
      $stats[$name]['state'] = $stats[$name]['state'] === 'rest' ? 'fly' : 'rest';
      $stats[$name]['duration'] = $stats[$name]['state'] === 'rest' ? $r['rest'] : $r['time'];
    }
    if ($stats[$name]['state'] === 'fly') {
      $stats[$name]['distance'] += $r['velocity'];
      echo "$name traveled " . $r['velocity'] . " for a total of " . $stats[$name]['distance'] . "\n";
    } else {
      echo "$name is resting for " . $stats[$name]['duration'] . " more seconds\n";
    }
    $stats[$name]['duration']--;
  }
  // find deer with max duration and give points
  $current_winner = '';
  $current_winning_score = 0;
  $scores = [];
  foreach ($reindeer as $name => $r) {
    echo "$name is at " . $stats[$name]['distance'] . "\n";
    $scores[$name] = $stats[$name]['distance'];
  }
  $top_score = max($scores);
  foreach ($reindeer as $name => $r) {
    if ($stats[$name]['distance'] === $top_score) {
      $stats[$name]['points']++;
    }
  }
}

var_dump($stats);

 ?>
