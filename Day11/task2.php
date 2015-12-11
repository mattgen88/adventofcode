<?php

$input = "vzbxxzaa";

while (!testPassword($input)) {
  echo "$input didn't pass\n";
  // increment
  $input = increment_letter($input);
}

echo "$input\n";

function increment_letter($input) {
  if ($input[strlen($input)-1] === 'z') {
    $new_input = increment_letter(substr($input, 0, strlen($input)-1)) . 'a';
  }
  else {
    $length = strlen($input);
    $partial = substr($input, 0, $length-1);
    $new_input = $partial . chr(ord($input[strlen($input)-1])+1);
  }
  return $new_input;
}
function testPassword($password) {
  $confusion_check = (preg_match('/(i|o|l)/', $password) === 0);
  $repeat_letter_check = preg_match('/([a-z])\1.*([a-z])\2/', $password);
  $series_check = preg_match('/(abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz)/',$password);
  return $confusion_check && $repeat_letter_check && $series_check;
}
 ?>
