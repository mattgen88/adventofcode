<?php
$input = file_get_contents("input.txt");
$data = explode("\n\n", $input);
$productions = explode("\n", $data[0]);
$molecule = $data[1];
$molecules=[];
$replacements = 0;
foreach ($productions as $production) {
  preg_match("/(\w+) => (\w+)/", $production, $matches);
  list(,$from, $to) = $matches;
  $count = substr_count($molecule, $from);
  echo "Found $count instances of $from in $molecule\n";
  $loc = 0;
  while ($count > 0) {
    // find the $count instance of $from and replace with $to
    $loc = strpos($molecule, $from, $loc);
    echo "Found $from at location $loc\n";
    $new_molecule = substr_replace($molecule, $to, $loc, strlen($from));
    $molecules[$new_molecule] = TRUE;
    $loc++;
    $count--;
    $replacements++;
  }
}
echo "Found " . count($molecules) . " Distinct Molecules over $replacements iterations\n";
//var_dump($molecules);
 ?>
