<?php
include "connection.php";
if ($_POST["mode"] == "new") {
	mysql_query("INSERT INTO fields SET item = '".mysql_real_escape_string($_POST["item"])."', code = '".mysql_real_escape_string($_POST["code"])."', square = '".mysql_real_escape_string($_POST["square"])."'");
	$create_field_id = mysql_fetch_array(mysql_query("SELECT * FROM fields WHERE item = '".mysql_real_escape_string($_POST["item"])."' and code = '".mysql_real_escape_string($_POST["code"])."' and square = '".mysql_real_escape_string($_POST["square"])."' ORDER BY `id` DESC"));
	$arr_ptr = explode("," , $_POST["coords"]);
	
	foreach($arr_ptr as $key => $coord) {
		$i = explode(";", $coord);
		mysql_query("INSERT INTO field_points SET coordinate_s = '".mysql_real_escape_string($i[0])."', coordinate_d = '".mysql_real_escape_string($i[1])."', field_id = '".mysql_real_escape_string($create_field_id['id'])."'");
	}
} 
else if ($_POST["mode"] == "change") {
	$arr = array();
	mysql_query("UPDATE fields SET item = '".mysql_real_escape_string($_POST["item"])."', code = '".mysql_real_escape_string($_POST["code"])."', square = '".mysql_real_escape_string($_POST["square"])."' WHERE id = '".mysql_real_escape_string($_POST["id"])."'");

	if(!empty($_POST['seazon']) && !empty($_POST['plant']) && !empty($_POST['volume']))
    {

        foreach($_POST['seazon'] as $id => $data)
        {
			mysql_query("INSERT INTO fields_history SET field_id = '".mysql_real_escape_string($_POST["id"])."', seazon = '".$data."'");
			$history = mysql_fetch_array(mysql_query("SELECT * FROM fields_history WHERE field_id = '".$_POST["id"]."' and seazon = '".$data."' ORDER BY `id` DESC"));
			$arr[] = $history['id'];
        }
		$i = 0;
		 foreach($_POST['plant'] as $id => $data)
        {
			mysql_query("UPDATE fields_history SET plant = '".$data."' WHERE id = '".$arr[$i]."'");
			$i++;
        }
		$i = 0;
		 foreach($_POST['volume'] as $id => $data)
        {
			
			mysql_query("UPDATE fields_history SET volume = '".$data."' WHERE id = '".$arr[$i]."'");
			$i++;
        }
    }
}

header("location: ../");
?>