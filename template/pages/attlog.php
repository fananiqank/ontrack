<?php
session_start();
// error_reporting(0);
include "../../funlibs.php";
$db=new Database();


$final = date("Y-m-d", strtotime("-1 month", strtotime($_GET[end])));

$awal=date("Y-m-1",strtotime($final));
$akhir=date("Y-m-t",strtotime($final))
;
$whtgl="where date(tglkerja) between '$awal' and '$akhir'";
$whid="where id_pegawai='$_SESSION[ID_PEG]'";
$whid2="AND id_pegawai='$_SESSION[ID_PEG]'";

$fld="(select tgl_mtc, case when type_input = 1 then 'Planning' else 'Realisasi' end typeinput,case when type_input = 1 then count(id)  else count(id) end oke,type_input
from maintenance GROUP BY type_input,tgl_mtc) a";


$dtnya=$db->select("$fld","*");

foreach($dtnya as $vdtnya){
	$cl="";
	if($vdtnya[type_input]=='2'){
		$cl="Red";
	} else {
		$cl="#557C55";
	}
		$dtx[]=array("title"=> $vdtnya[typeinput] .": "." ". $vdtnya[oke],
				"start"=> $vdtnya[tgl_mtc],
				"starttime"=> $vdtnya[tgl_mtc],
				"endtime"=> $vdtnya[tgl_mtc],
				"color"=> $cl,
				"masuk"=>$vdtnya[typeinput],
				"pulang"=>$vdtnya[typeinput],
				"typedt"=>"x"
			  );
}


// --------------------
// Kartu Kredit
// 163 766 191 061
// $dtadd=array("title"=>'C.out',"start"=>"2021-11-04 12:12:00");
// array_push($dtnya, $dtadd);
// $dtadd=array("title"=>'C.out',"start"=>"2021-11-04 12:12:00");
// array_push($dtnya, $dtadd);
// $dtadd=array("title"=>'C.out',"start"=>"2021-11-04 12:12:00");
// array_push($dtnya, $dtadd);
// $dtadd=array("title"=>'C.out',"start"=>"2021-11-04 12:12:00");
// array_push($dtnya, $dtadd);

echo json_encode($dtx ? $dtx : array());

// echo '[{"title":"All Day Event","start":"2021-11-01"},{"title":"Long Event","start":"2021-11-07","end":"2021-11-10"},{"groupId":"999","title":"Repeating Event","start":"2021-11-09T16:00:00+00:00"},{"groupId":"999","title":"Repeating Event","start":"2021-11-16T16:00:00+00:00"},
// 	{"title":"Conference","start":"2021-11-21","end":"2021-11-23"},
// 	{"title":"Meeting","start":"2021-11-22T10:30:00+00:00","end":"2021-11-22T12:30:00+00:00"},
// 	{"title":"Lunch","start":"2021-11-22T12:00:00+00:00"},{"title":"Birthday Party","start":"2021-11-23T07:00:00+00:00"},{"url":"http:\/\/google.com\/","title":"Click for Google","start":"2021-11-28"},{"title":"Meeting","start":"2021-11-22T14:30:00+00:00"},{"title":"Happy Hour","start":"2021-11-22T17:30:00+00:00"},{"title":"Dinner","start":"2021-11-22T20:00:00+00:00"}]';


 //var_dump($dtnya);