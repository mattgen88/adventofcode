<?php
$input = file("input.txt");
$good = [];
$bad = [];
var_dump($input);
foreach ($input as $passphrase) {
  $passphrase = trim($passphrase);
  $chunks = explode(" ", $passphrase);
  $map = [];
  $valid = true;
  foreach ($chunks as $chunk) {
    if (isset($map[$chunk])) {
      $valid = false;
      $bad[] = $passphrase;
      break;
    }
    $map[$chunk] = true;
  }
  if ($valid) {
    $good[] = $passphrase;
  }
}

var_dump($good);
