<?php
$presents = file_get_contents("input.txt");
$houses = [];
for($i = 1; $i < $presents; $i++) {
    $count = 0;
    for($j = $i; $j < $presents; $j+=$i) {
        @$houses[$j] += $i*11;
        $count++;
        if($count == 50) {
          break;
        }
    }
}
for($i = 1; $i < $presents;$i++) {
    if(@$houses[$i] >= $presents) {
        echo $i . "\n";
        exit();
    }
}

 ?>
