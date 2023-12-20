<?php
$input = file_get_contents("input.txt");


$input = explode("\n", $input);

$sum = 0;
$valid = [];
foreach ($input as $item) {
  preg_match("/([a-z\-]+)-([0-9]+)\[([a-z]+)\]/", $item, $matches);
  list(,$data, $id, $hash) = $matches;
  if (valid($data, $hash)) {
    $sum += (int) $id;
    $valid[] = ['data' => $data, 'rot' => $id, 'deciphered' => decipher($data, $id)];
  }
}
var_dump($sum);
var_dump($valid);
function valid($data, $hash) {
  return $hash === checksum($data);
}

function checksum($data) {
  $letters = [];
  for ($i = 0; $i < strlen($data); $i++) {
    if ($data[$i] === "-") {
      continue;
    }
    if (isset($letters[$data[$i]])) {
      $letters[$data[$i]]['count']++;
    } else {
      $letters[$data[$i]]['value'] = $data[$i];
      $letters[$data[$i]]['count'] = 1;
    }
  }
  usort($letters, "valuealphasort");
  $top = array_slice($letters, 0, 5);
  $result = '';
  foreach ($top as $it) {
    $result .= $it['value'];
  }
  return $result;
}

function valuealphasort($a, $b) {
  if ($a["count"] === $b["count"]) {
    // sort alphabetically
    return ord($a["value"]) > ord($b["value"]) ? 1 : -1;
  }
  return $a["count"] < $b["count"] ? 1 : -1;
}

function decipher($string, $times) {
  $result = "";
  for ($i = 0; $i < strlen($string); $i++) {
    if ($string[$i] === "-") {
      $result .= " ";
    } else {
      $result .= chr((((ord($string[$i]) - 97) + $times) % 26) + 97);
    }
  }
  var_dump($result);
  return $result;
}
?>
