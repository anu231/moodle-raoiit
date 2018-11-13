<?php
if(file_exists("demosaved.csv")){
	$file = fopen('demosaved.csv', 'a');
}
else{
	$file = fopen('demosaved.csv', 'w');
}
//$file = fopen('demosaved.csv', 'w');
$j=1;
$arraytest = array();

	for($j=100;$j<170;$j++)
	{
		$arraytest[]=array($j,$j+5,$j*10);
		//$arraytest[]=$i+5;
		//$arraytest[]=$i*10;
	}

	foreach ($arraytest as $row)
	{
	    fputcsv($file, $row);
	}
	 
// Close the file
fclose($file);
echo "<pre/>";
print_r($arraytest);
?>
