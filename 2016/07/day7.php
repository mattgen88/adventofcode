<?php

$input = file_get_contents("input.txt");

$input = explode("\n", $input);
$regex = "/([a-z]+)(\[([a-z]+)\])([a-z]+)(\[([a-z]+)\])?([a-z]+)?(\[([a-z]+)\])?([a-z]+)?/";
$processed = [];
$count = 0;
foreach ($input as $address) {
  // read chars until [
  $aggregate = "";
  $hypernets = [];
  $nonhypernets = [];
  for ($i = 0; $i < strlen($address); $i++) {
    if ($address[$i] === '[') {
      // switch to hypernet
      $hypernet = TRUE;
      $nonhypernets[] = $aggregate;
      $aggregate = '';
      continue;
    } else if ($address[$i] === ']') {
      $hypernet = FALSE;
      $hypernets[] = $aggregate;
      $aggregate = '';
      continue;
    }
    $aggregate .= $address[$i];
    if (strlen($address) === $i + 1) {
      $nonhypernets[] = $aggregate;
    }
  }

  $processed[] = ['hypernets' => $hypernets, 'nonhypernets' => $nonhypernets];


  $hypernetABBA = false;
  $nonhypernetABBA = false;
  foreach($hypernets as $hypernet) {
    $hypernetABBA = $hypernetABBA || ABBA($hypernet);
  }
  foreach($nonhypernets as $nonhypernet) {
    $nonhypernetABBA = $nonhypernetABBA || ABBA($nonhypernet);

  }
  if (!$hypernetABBA && $nonhypernetABBA) {
    $count++;
  }
}
var_dump($count);
$ssl = 0;
foreach($processed as $item) {
  foreach ($item['hypernets'] as $hypernet) {
    // iterate over string finding all things that are form aba
    $strings = [];
    for ($i=0; $i < strlen($hypernet)-2; $i++) {
      if ($hypernet[$i] == $hypernet[$i+2]) {
        $strings[] = implode('', [$hypernet[$i+1], $hypernet[$i], $hypernet[$i+1]]);
      }
    }
    foreach ($strings as $string) {
      foreach ($item['nonhypernets'] as $nonhypernet) {
        if (substr_count($nonhypernet, $string) > 0) {
          $ssl++;
          break 3;
        }
      }
    }
  }
}
var_dump($ssl);


function ABBA($string) {
  if (preg_match("#\w*(\w)(\w)\\2\\1\w*#", $string, $matches) === 1) {
    if ($matches[1] !== $matches[2]) {
      return true;
    }
  }
  return false;
}

?>
