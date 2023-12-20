<?php
$input = file_get_contents("input.txt");

$input = explode("\n", $input);

class bot {
  public $started;
  public $num;
  public $high;
  public $low;
  public $gives_high_to;
  public $gives_low_to;
  public function addValue($val) {
    if (!$this->low) {
      $this->low = $val;
    } else {
      if ($this->low > $val) {
        $this->high = $this->low;
        $this->low = $val;
      } else {
        $this->high = $val;
      }
    }
  }
  public function __construct($num, &$bots, &$output) {
    $this->bots = &$bots;
    $this->output = &$output;
    $this->num = $num;
  }

  public function execute() {
    if (!$this->high || !$this->low) {
      return false;
    }

    if ($this->high === "61" && $this->low === "17") {
      echo "My name is bot " . $this->num . "\n";
      sleep(10);
      var_dump($this);
    }

    list($thing, $num) = explode(' ', $this->gives_low_to);
    if ($thing==='output') {
      $this->output[$num] = $this->low;
      $this->low = null;
    } else {
      $this->bots[$num]->addValue($this->low);
      $this->low = null;
    }
    list($thing, $num) = explode(' ', $this->gives_high_to);
    if ($thing==='output') {
      $this->output[$num] = $this->high;
      $this->high = null;
    } else {
      $this->bots[$num]->addValue($this->high);
      $this->high = null;
    }
    return true;
  }
}

$bots = [];
$outputs = [];

foreach ($input as $line) {
  if (preg_match('/value (\d+) goes to bot (\d+)/', $line, $matches) === 1) {
    $bot_num = $matches[2];
    if (!isset($bots[$bot_num])) {
      $bot = new bot($bot_num, $bots, $outputs);
      $bots[$bot_num] = $bot;
    }
    $bots[$bot_num]->addValue($matches[1]);
  } else if (preg_match('/bot (\d+) gives low to (bot|output) (\d+)(?: and high to (bot|output) (\d+))?/', $line, $matches) === 1) {
    $bot_num = $matches[1];
    if (!isset($bots[$bot_num])) {
      $bots[$bot_num] = new bot($bot_num, $bots, $outputs);
    }
    $bot_or_output = $matches[2];
    $to_num = $matches[3];

    $bots[$bot_num]->gives_low_to = "{$bot_or_output} {$to_num}";

    if (count($matches) > 3) {
      $bot_or_output = $matches[4];
      $to_num = $matches[5];
      $bots[$bot_num]->gives_high_to = "{$bot_or_output} {$to_num}";
    }
  }
}
while(count($bots) > 0) {
array_walk($bots, function(&$value, $key) use (&$bots) {
  if ($value->execute()) {
    unset($bots[$key]);
  }
});
}
var_dump($outputs);
// var_dump($bots);
 ?>
