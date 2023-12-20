<?php

$presents = file_get_contents("input.txt");
for ($elf=1;$elf <= $presents/10;$elf++) {
  for ($house = $elf;$house <= $presents/10; $house += $elf) {
    @$houses[$house]['number'] = $house;
    @$houses[$house]['presents'] += $elf * 10;
  }
}
$good = array_filter($houses, function($house) {
  $result = $house['presents'] >= 36000000;
  if ($result) {
    echo "House " . $house['number'] . " wins\n";
    exit();
  }
  return $result;
});
ksort($good);


 ?>
