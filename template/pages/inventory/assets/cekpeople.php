<?php 
include "../../../../funlibs.php";
session_start();
$con=new Database();

foreach($con->select("people a join assets b on a.id=b.userid","a.*","a.id = $_GET[id]") as $cek){
	echo $cek['statusid'];
}

?>