<?php 
include "../../../funlibs.php";
session_start();
$con=new Database();
 // echo "select sparepartid,sum(masukmutasi) as masuk, sum(keluarmutasi) as keluar, (sum(masukmutasi)-sum(keluarmutasi)) as total from tx_mutasi
 // where sparepartid = $_GET[id] and id_gudang = $_GET[gdg]";
foreach($con->select("(select sparepartid,sum(masukmutasi) as masuk, sum(keluarmutasi) as keluar, (sum(masukmutasi)-sum(keluarmutasi)) as total from tx_mutasi
where sparepartid = $_GET[id] and id_gudang = $_GET[gdg] and jenisbrg = $_GET[jns]) a","*") as $cek){

}

foreach($con->select("(select sum(qty) qty from maintenance_dtl where sparepartid = $_GET[id] and statusid=0 and jenisbrg = $_GET[jns] group by sparepartid) a","*") as $mtcs){}
foreach($con->select("(select sum(qty) qty from tx_barangkeluar_dtl where sparepartid = $_GET[id] and statusid=0 and jenisbrg = $_GET[jns] group by sparepartid) a","*") as $bklr){}

$allval = (int)$cek['total']-(int)$mtcs['qty']-(int)$bklr['qty'];
echo $allval;
?>