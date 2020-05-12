<!DOCTYPE html>
<html>
<body>
<?php
//this page is used for testing code before putting into actual db file
srand(time()); 

$id_array;

for($id_array as $value)
    {
		$id_array[9] = rand(0, 9);
		 
    }
	
	echo $id_array;
?>	
</body>
</html>