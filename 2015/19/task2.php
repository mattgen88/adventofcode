<?php
$input = file_get_contents("input.txt");
$data = explode("\n\n", $input);
$productions = explode("\n", $data[0]);
$molecule = $data[1];
$molecules=[];
$replacements = 0;
while ($molecule !== 'e') {
  foreach ($productions as $production) {
    preg_match("/(\w+) => (\w+)/", $production, $matches);
    list(,$to, $from) = $matches;
    $count = substr_count($molecule, $from);
    if ($count === 0) {
      continue;
    }
    echo "Found $count instances of $from in $molecule replaced with $to\n";
    $molecule = preg_replace("/($from)/", $to, $molecule);
    $replacements += $count;
  }
}
echo "Did $replacements replacements\n";
//var_dump($molecules);
 ?>
