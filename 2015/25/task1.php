<?php
$input = file_get_contents("input.txt");
preg_match("^To continue, please consult the code grid in the manual.  Enter the code at row (\d+), column (\d+).$", $input, $matches);

$targetRow = $matches[1][0];
$targetCol = $matches[2][0];

$row = 1;
$col = 1;
$value = 20151125;
echo "Starting\n";
while (true)
{
    $nextRow = 0;
    $nextCol = 0;

    echo ("($row, $col) = $value\n");

    if ($row == $targetRow && $col == $targetCol)
        return;

    if ($row == 1 && $col == 1)
    {
        $nextRow = 2;
        $nextCol = 1;
    } else if ($row == 1 && $col != 1) {
        // $row = 1
        // $col = 2
        $nextRow = $col+1;
        $nextCol = 1;
    } else {
        $nextRow = $row - 1;
        $nextCol = $col + 1;
    }

    $value = ($value * 252533) % 33554393;
    $row = $nextRow;
    $col = $nextCol;
}