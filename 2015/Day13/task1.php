<?php
$input = file('input');
class table {
  public $seats=[];
  public $persons=[];
  public function shuffle() {
    // CHANGE PLACES!
    shuffle($this->seats);
  }
  public function addSeat($name) {
    if (!in_array($name, $this->seats)) {
      $this->seats[] = $name;
    }
  }
  public function addPerson($name, $neighbor, $net) {
    $this->persons[$name][$neighbor] = $net;
  }
  public function netHappiness() {
    $first = 0;
    $last = count($this->seats) - 1;
    $left = $last;
    $right = 1;
    $happiness = 0;
    foreach ($this->seats as $location => $person) {
      // echo $person . "\n";
      // echo "Seat left " . $left . "\n";
      // echo "Seat current " . $location . "\n";
      // echo "Seat right " . $right . "\n";
      // echo "Left: " . $this->seats[$left] . " Current: " . $this->seats[$location] . " Right: " . $this->seats[$right]."\n";

      $happiness += $this->persons[$person][$this->seats[$left]];
      $happiness += $this->persons[$person][$this->seats[$right]];

      if ($left === $last) {
        $left = $first;
      } else {
        $left++;
      }

      if ($right === $last) {
        $right = $first;
      } else {
        $right++;
      }
    }
    return $happiness;
  }
}
$table = new table();
foreach ($input as $line) {
  $success = preg_match('/([A-Za-z]+) would (gain|lose) (\d+) happiness units by sitting next to ([A-Za-z]+)/', $line, $matches);
  if (!$success) {
    exit("FAIL");
  }
  list(,$subject, $net, $amount, $neighbor) = $matches;

  $table->addPerson($subject, $neighbor, (int)($net=='lose'?-$amount : $amount));
  $table->addSeat($subject);
}

$max = 0;
while (true) {
  $happiness = $table->netHappiness();
  if ($happiness > $max) {
    echo "New max happiness of " . $happiness  . "\n";
    $max = $happiness;
  }
  $table->shuffle();
}

echo "Happiness is " . $table->netHappiness() . "\n";
 ?>
