<?php
$input = "1
3
5
11
13
17
19
23
29
31
41
43
47
53
59
61
67
71
73
79
83
89
97
101
103
107
109
113";

$input = explode("\n", $input);
$sum = 0;
foreach ($input as $num) {
  $sum += $num;
}

echo $sum/3;
?>
