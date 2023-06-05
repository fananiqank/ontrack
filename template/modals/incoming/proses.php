<?php 
include "../../../funlibs.php";
session_start();
$con=new Database();

$data = array(
    'sparepartid' => $_POST[sparepart],
    'qty' => $_POST[qty],
    'userid' => $_POST[userid],
    'statusid' => 0,
    'jenisbrg' => $_POST[jenisbrg]
);

$exec=$con->insertID("tx_barangmasuk_dtl",$data);
echo $exec;
?>
