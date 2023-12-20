<?php
$input = file_get_contents("input.txt");

function decompress($start, $end, $input) {
  // If you hit the end, return 0
  if ($start === $end) {
    return 0;
  }
  // at start of mark
  if ($input[$start] === '(') {
    // find end brace
    $mark = "";
    for ($i=$start; $i < $end; $i++) {
      $mark .= $input[$i];
      // found end of mark
      if ($input[$i] === ')') {
        break;
      }
    }
    // $mark now contains the entire mark
    // pull out the char count and repeat
    list($chars, $repeat) = explode("x", trim($mark, "()"));

    // return repeat of decompressed string following it + decompressed after the following
    return $repeat * decompress($i+1, $i+1+$chars, $input) + decompress($i + 1 + $chars, $end, $input);
  }
  // did not have a start of mark, accumulate number of chars read
  for ($i=$start;$i < $end; $i++) {
    if ($input[$i] === '(') {
      // leading chars + start of a mark
      return $i - $start + decompress($i, $end, $input);
    }
  }
  // final case of whatever characters are left
  return $end-$start;
}

echo decompress(0, strlen($input), $input);
