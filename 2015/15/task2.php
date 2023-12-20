<?php
$input = file('input.txt');
$ingredients = [];
foreach ($input as $item) {
  preg_match('/([A-Za-z]+): [a-z]+ ([\d-]+), [a-z]+ ([\d-]+), [a-z]+ ([\d-]+), [a-z]+ ([\d-]+), [a-z]+ ([\d-]+)/', $item, $matches);
  list(, $name, $capacity, $durability, $flavor, $texture, $calories) = $matches;
  $ingredients[$name] = [
    'capacity' => (int)$capacity,
    'durability' => (int)$durability,
    'flavor' => (int)$flavor,
    'texture' => (int)$texture,
    'calories' => (int)$calories,
  ];
}

$max = 0;
for ($i = 0; $i <= 100; $i++) {
  for ($j = 0; $j <= 100 - $i; $j++) {
    for ($k = 0; $k <= 100 - $i - $j; $k++) {
      $l = 100 - $i - $j - $k;
      if ($i + $j + $k + $l !== 100) {
        continue;
      }
      $capacity = $durability = $flavor = $texture = 0;

      $capacity = ($ingredients["Frosting"]['capacity'] * $i)
        + ($ingredients["Candy"]['capacity'] * $j)
        + ($ingredients["Butterscotch"]['capacity'] * $k)
        + ($ingredients["Sugar"]['capacity'] * $l);

      $durability = ($ingredients["Frosting"]['durability'] * $i)
        + ($ingredients["Candy"]['durability'] * $j)
        + ($ingredients["Butterscotch"]['durability'] * $k)
        + ($ingredients["Sugar"]['durability'] * $l);

      $flavor = ($ingredients["Frosting"]['flavor'] * $i)
        + ($ingredients["Candy"]['flavor'] * $j)
        + ($ingredients["Butterscotch"]['flavor'] * $k)
        + ($ingredients["Sugar"]['flavor'] * $l);

      $texture = ($ingredients["Frosting"]['texture'] * $i)
        + ($ingredients["Candy"]['texture'] * $j)
        + ($ingredients["Butterscotch"]['texture'] * $k)
        + ($ingredients["Sugar"]['texture'] * $l);

      $calories = ($ingredients["Frosting"]['calories'] * $i)
        + ($ingredients["Candy"]['calories'] * $j)
        + ($ingredients["Butterscotch"]['calories'] * $k)
        + ($ingredients["Sugar"]['calories'] * $l);

      $capacity = max(0, $capacity);
      $durability = max(0, $durability);
      $flavor = max(0, $flavor);
      $texture = max(0, $texture);

      if ($calories !== 500) {
        continue;
      }

      $score = $capacity * $durability * $flavor * $texture;
      $max = max($max, $score);
    }
  }
}

var_dump($max);
