<?php

$input = file_get_contents("input.txt");


$input = explode("\n", $input);

$strings = [];

for ($i = 0; $i < count($input); $i++) {
  for ($j = 0; $j < strlen($input[$i]) - 1; $j++) {
    $strings[$j][] = $input[$i][$j];
  }
}

$flipped = [];
foreach ($strings as $string) {
  $implode = implode($string, "");
  if (empty($implode)) {
    continue;
  }
  $flipped[] = implode($string, "");
}


foreach($flipped as $string) {
  echo mostCommonChar($string);
}
echo "\n";

function mostCommonChar($string) {
  $letters = [];
  for ($i = 0; $i < strlen($string); $i++) {
    if ($string[$i] === "-") {
      continue;
    }
    if (isset($letters[$string[$i]])) {
      $letters[$string[$i]]['count']++;
    } else {
      $letters[$string[$i]]['value'] = $string[$i];
      $letters[$string[$i]]['count'] = 1;
    }
  }
  usort($letters, "valuealphasort");
  $result = array_slice($letters, count($letters) - 1, 1);
  return $result[0]["value"];
}
function valuealphasort($a, $b) {
  return $a["count"] < $b["count"] ? 1 : -1;
}

 ?>
