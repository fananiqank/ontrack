<?php 
include "../../../funlibs.php";
session_start();
$con=new Database();

$exec=$con->delete("tx_barangmasuk_dtl",array("id"=> $_GET[id]));
echo $exec;
?>
