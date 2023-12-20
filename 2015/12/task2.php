<?php
$input = file_get_contents('input.txt');
// $input = '[1,"red",5]';
$document = json_decode($input);

$sum = traverse($document);
var_dump($sum);
function traverse($item) {
  $sum = 0;
  if (is_int($item)) {
    $sum += $item;
  } else if(is_array($item)) {
    foreach($item as $val) {
      if (is_int($val)) {
        $sum += $val;
      } else {
        $sum += traverse($val);
      }
    }
  } else if(is_object($item)) {
    $tmp_sum = 0;
    $skip = false;
    foreach ($item as $prop) {
      if ($prop === "red") {
        $skip = true;
        break;
      } else {
        $tmp_sum += traverse($prop);
      }
    }
    if (!$skip) {
        $sum += $tmp_sum;
    }
  }
  return $sum;
}

 ?>
