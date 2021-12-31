<?php
//ini_set("xdebug.max_nesting_level", -1);
//function infinitePaper($row, $col)
//{
//    if ($row == 1 && $col == 1)
//    {
//        // done!
//        return 201611125;
//    }
//    echo "($row, $col)\n";
//    if ($col == 1)
//        return (infinitePaper(1, $row-1) * 252533) % 33554393;
//    return (infinitePaper($row +1, $col - 1) * 252533) % 33554393;
//}
//
//echo infinitePaper(2978, 3083);
$targetRow = 2978;
$targetCol = 3083;
//$targetRow = 1;
//$targetCol = 6;
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