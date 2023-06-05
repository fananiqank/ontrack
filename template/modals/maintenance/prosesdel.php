<?php 
include "../../../funlibs.php";
session_start();
$con=new Database();

$exec=$con->delete("maintenance_dtl",array("id"=> $_GET[id]));
echo $exec;
?>
