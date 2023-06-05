<?php 
include "../../../funlibs.php";
session_start();
$con=new Database();

$data = array(
    'sparepartid' => $_POST[sparepart],
    'qty' => $_POST[qty],
    'userid' => $_POST[userid],
    'statusid' => 0,
    'jenisbrg' => $_POST[jnsbrg],
    'jenisganti' => $_POST[jnsmtc],
    'gudangid' => $_POST[gudangid]

);

$exec=$con->insertID("maintenance_dtl",$data);
echo $exec;
?>
