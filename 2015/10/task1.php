<?php
$input = file_get_contents("input.txt");


$last = $input;
for ($i = 0; $i < 40; $i++) {
    //consume the characters
    $lastChar = '';
    $newString = '';
    $currentCharCount = 0;
    for ($j = 0; $j < strlen($last); $j++) {
      // get nextChar
      $nextChar = $last[$j];
      // if nextChar is not the first char and it is different from last char
      if ($lastChar != '' && $lastChar != $nextChar) {
        // append the current count of chars to the string followed by the last char
        // reset the currentCharCount
        $newString .= "{$currentCharCount}{$lastChar}";
        $currentCharCount=1;
      } else {
        $currentCharCount++;
      }
      $lastChar = $nextChar;
    }
    $newString .= "{$currentCharCount}{$lastChar}";
    echo $newString;
    $last = $newString;
    echo "\n";
}

echo "Answer: " . strlen($last);
?>
