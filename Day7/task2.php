  <?php
  $input = file('input');
  $wires = [];
  foreach ($input as $in) {
    preg_match('/([a-z\d]+ )?([A-Z]+ )?([a-z\d]+) -> ([a-z\d]+)/', $in, $matches);
    list(, $in1, $op, $in2, $out) = $matches;
    switch(trim($op)) {
      case 'NOT':
        $wires[$out] = ['op' => 'NOT', 'inputs' => [trim($in2)]];
      break;
      case 'OR':
        $wires[$out] = ['op' => 'OR', 'inputs' => [trim($in1), trim($in2)]];
      break;
      case 'AND':
        $wires[$out] = ['op' => 'AND', 'inputs' => [trim($in1), trim($in2)]];
      break;
      case 'RSHIFT':
        $wires[$out] = ['op' => 'RSHIFT', 'inputs' => [trim($in1), trim($in2)]];
      break;
      case 'LSHIFT':
        $wires[$out] = ['op' => 'LSHIFT', 'inputs' => [trim($in1), trim($in2)]];
      break;
      default:
        $wires[$out] = ['op' => 'NONE', 'inputs' => [trim($in2)]];
    }
  }
  $wires['b'] = ['op' => 'NONE', 'inputs' => ['16076']];
  ksort($wires, SORT_NATURAL);
  $cache = [];
  print_r(dereference('a'));
  function dereference($wire) {
      global $wires, $cache;
      if (isset($cache[$wire])) {
        print("Cache hit for $wire\n");
        return $cache[$wire];
      } else {
        print("Processing $wire for first time\n");
      }
      // fetch the wire's diagram
      $diagram = $wires[$wire];
      switch($diagram['op']) {
        case 'NONE':
          list($input) = $diagram['inputs'];
          if (preg_match('/\d+/', $input) === 0) {
            $val = dereference($input);
          } else {
            $val = $input;
          }
          $cache[$wire] = $val;
          print("NONE returned $val\n");
          return (int)$val;
        break;
        case 'NOT':
          list($input) = $diagram['inputs'];
          if (preg_match('/\d+/', $input) === 0) {
            echo "Dereferencing $input\n";
            $input = dereference($input);
          }
          $val = ~(int)$input;
          $cache[$wire] = $val;
          print("NOT $input returned $val\n");
          return $val;
        break;
        case 'AND':
          list($in1, $in2) = $diagram['inputs'];
          if (preg_match('/\d+/', $in1) === 0) {
            $in1 = dereference($in1);
          }
          if (preg_match('/\d+/', $in2) === 0) {
            $in2 = dereference($in2);
          }
          $val = (int)$in1 & (int)$in2;
          $cache[$wire] = $val;
          print("AND $in1 $in2 returned $val\n");
          return $val;
        break;
        case 'OR':
          list($in1, $in2) = $diagram['inputs'];
          if (preg_match('/\d+/', $in1) === 0) {
            $in1 = dereference($in1);
          }
          if (preg_match('/\d+/', $in2) === 0) {
            $in2 = dereference($in2);
          }
          $val = (int)$in1 | (int)$in2;
          $cache[$wire] = $val;
          print("OR $in1 $in2 returned $val\n");
          return $val;
        break;
        case 'RSHIFT':
          list($in1, $in2) = $diagram['inputs'];
          if (preg_match('/\d+/', $in1) === 0) {
            $in1 = dereference($in1);
          }
          if (preg_match('/\d+/', $in2) === 0) {
            $in2 = dereference($in2);
          }
          $val = (int)$in1 >> (int)$in2;
          $cache[$wire] = $val;
          print("RSHIFT $in1 $in2 returned $val\n");
          return $val;
        break;
        case 'LSHIFT':
          list($in1, $in2) = $diagram['inputs'];
          if (preg_match('/\d+/', $in1) === 0) {
            $in1 = dereference($in1);
          }
          if (preg_match('/\d+/', $in2) === 0) {
            $in2 = dereference($in2);
          }
          $val = (int)$in1 << (int)$in2;
          $cache[$wire] = $val;
          print("LSHIFT $in1 $in2 returned $val\n");
          return $val;
        break;
        default:
          exit("This shouldn't happen");
      }
  }
   ?>
