<?php
include "connection.php";
if ($_POST["getting_data"] == "field_data") {
	$array_sql = mysql_fetch_array(mysql_query("SELECT * FROM fields WHERE id = '".$_POST["id"]."'"));
	foreach($array_sql as $key => $i) {
		if(!is_numeric($key))
			print($i . ";");
	}
}
else if ($_POST["getting_data"] == "field_history") {
	$array_sql = mysql_query("SELECT * FROM fields_history WHERE field_id = '".$_POST["id"]."'");
	while ($array= mysql_fetch_array($array_sql)) {
		print($array["seazon"] . "," . $array["plant"] . "," . $array["volume"] . ";");
	}
}
else if ($_POST["getting_data"] == "field_coordinates") {
	$array_sql = mysql_query("SELECT * FROM field_points WHERE field_id = '".$_POST["id"]."'");
	while ($array= mysql_fetch_array($array_sql)) {
		print($array["coordinate_d"] . "," . $array["coordinate_s"] . ";");
	}
}
else
	header("location: ../");
?>