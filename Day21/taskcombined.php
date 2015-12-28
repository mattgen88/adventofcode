<?php
$boss = [
  "name"    => "boss",
  "hp"      => 109,
  "damage"  => 8,
  "armor"   => 2,
];

$me = [
  "name"    => "spyder",
  "hp"      => 100,
  "damage"  => 0,
  "armor"   => 0,
];

$weapons = [
  ['cost' => 8,  'armor' => 0, 'damage' => 4, 'name' => 'Dagger'],
  ['cost' => 10, 'armor' => 0, 'damage' => 5, 'name' => 'Shortsword'],
  ['cost' => 25, 'armor' => 0, 'damage' => 6, 'name' => 'Warhammer'],
  ['cost' => 40, 'armor' => 0, 'damage' => 7, 'name' => 'Longsword'],
  ['cost' => 74, 'armor' => 0, 'damage' => 8, 'name' => 'Greataxe'],
];
$armors = [
  ['cost' => 0,    'armor' => 0, 'damage' => 0, 'name' => 'none'],
  ['cost' => 13,   'armor' => 1, 'damage' => 0, 'name' => 'Leather'],
  ['cost' => 31,   'armor' => 2, 'damage' => 0, 'name' => 'Chainmail'],
  ['cost' => 53,   'armor' => 3, 'damage' => 0, 'name' => 'Splintmail'],
  ['cost' => 75,   'armor' => 4, 'damage' => 0, 'name' => 'Bandedmail'],
  ['cost' => 102,  'armor' => 5, 'damage' => 0, 'name' => 'Platemail'],
];
$rings = [
  ['cost' => 0,    'armor' => 0, 'damage' => 0, 'name' => 'none_left'],
  ['cost' => 0,    'armor' => 0, 'damage' => 0, 'name' => 'none_right'],
  ['cost' => 25,   'armor' => 0, 'damage' => 1, 'name' => 'Damage+1'],
  ['cost' => 50,   'armor' => 0, 'damage' => 2, 'name' => 'Damage+2'],
  ['cost' => 100,  'armor' => 0, 'damage' => 3, 'name' => 'Damage+3'],
  ['cost' => 20,   'armor' => 1, 'damage' => 0, 'name' => 'Defense+1'],
  ['cost' => 40,   'armor' => 2, 'damage' => 0, 'name' => 'Defense+2'],
  ['cost' => 80,   'armor' => 3, 'damage' => 0, 'name' => 'Defense+3'],
];

$min = 999999;
$max = 0;
foreach ($armors as $armor) {
  foreach($weapons as $weapon) {
    foreach($rings as $ring1) {
      foreach($rings as $ring2) {
        if ($ring1 === $ring2) {
          continue;
        }
        echo sprintf("Trying %s %s %s %s\n", $weapon['name'], $armor['name'], $ring1['name'], $ring2['name']);
        $me['armor'] = $weapon['armor'] + $armor['armor'] + $ring1['armor'] + $ring2['armor'];
        $me['damage'] = $weapon['damage'] + $armor['damage'] + $ring1['damage'] + $ring2['damage'];
        $cost = $weapon['cost'] + $armor['cost'] + $ring1['cost'] + $ring2['cost'];
        if (fight($me, $boss)) {
          $min = min($min, $cost);
          if ($cost === $min) {
            $winning_equipment = [$armor['name'], $weapon['name'], $ring1['name'], $ring2['name']];
          }
        }else {
          $max = max($max, $cost);
        }
      }
    }
  }
}

echo "Lowest cost $min, Highest cost $max\n";
var_dump($winning_equipment);
function fight($me, $boss) {
    return ceil($me['hp'] / max(1, $boss['damage'] - $me['armor'])) >= $boss['hp'] / max(1,$me['damage'] - $boss['armor']);
}
