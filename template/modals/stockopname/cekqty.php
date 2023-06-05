<?php 
include "../../../funlibs.php";
session_start();
$con=new Database();
 // echo "select sparepartid,sum(masukmutasi) as masuk, sum(keluarmutasi) as keluar, (sum(masukmutasi)-sum(keluarmutasi)) as total from tx_mutasi
 // where sparepartid = $_GET[id] and id_gudang = $_GET[gdg]";
foreach($con->select("(select sparepartid,sum(masukmutasi) as masuk, sum(keluarmutasi) as keluar, (sum(masukmutasi)-sum(keluarmutasi)) as total from tx_mutasi
where sparepartid = $_GET[id] and id_gudang = $_GET[gdg] and jenisbrg = $_GET[jns]) a","*") as $cek){
	echo $cek['total'];
}

?>