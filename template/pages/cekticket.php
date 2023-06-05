<?php 
include "../../funlibs.php";
session_start();
$con=new Database();

foreach($con->select("tickets","COUNT(id) as jml","adminid like '%$_GET[id]%' and status in ('Assigned','In Progress')") as $cek){
	echo $cek['jml'];
}

?>