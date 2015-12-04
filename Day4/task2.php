<?php

$input = 'ckczppom';

function mine($input, $num)
{
	$md5 = md5($input . $num);
	$head = substr($md5, 0, 6);
	if ($head === '000000')
	{
		return TRUE;
	}
	return FALSE;
}

$num = 1;
while (!mine($input, $num))
{
	$num++;
}
//echo $num;
echo "MD5 of " . $input . $num . ": " . md5($input . $num);

?>
