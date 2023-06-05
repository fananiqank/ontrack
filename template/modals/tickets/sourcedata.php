<?php 
require_once "../../../funlibs.php";
session_start();
$con=new Database();

$dat=$con->select("assets a left join people b on a.userid=b.id left join clients c on a.clientid=c.id","a.clientid as iditassets,b.name as nama_user,b.email,a.userid,c.name as namadep","a.id = $_GET[id]");
     // echo "select id,serial,tag,clientid,b.name as nama_user from assets a join people b on a.userid=b.id where serial like '%$term%'";
    foreach($dat as $row)
    {
       echo $row['iditassets']."_".$row['userid']."_".$row['email']."_".$row['namadep']."_".$row['nama_user'];

    }
?>