<?php
date_default_timezone_set('Asia/Jakarta');
include "../../webclass.php";
require "PHPMailerAutoload.php";

session_start();
$db=new kelas();

	$tabel = "anggota";
	$fild  = "*"; 
	$where = "EMAIL ='$_GET[email]'";
	$data=$db->select($tabel,$fild,$where);
	
	//echo "select $fild from $tabel where $where";
	foreach($data as $val){}

//echo "val = " . $val['EMAIL'];
if ( $val['EMAIL'] != ''){

//echo"disini tg<br>";
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "ssl://smtp.gmail.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 465;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//$mail->SMTPSecure = 'tls'; 
//Username to use for SMTP authentication
$mail->Username = "kopkardanamaon@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "34M34ng1984";
//Set who the message is to be sent from
$mail->setFrom('kopkardanamaon@gmail.com', 'Reset Password Anggota Koperasi Danamon Surabaya');
//Set an alternative reply-to address
$mail->addReplyTo('kopkardanamaon@gmail.com', 'Reset Password Anggota Koperasi Danamon Surabaya');
//Set who the message is to be sent to
$mail->addAddress($val['EMAIL'], $val['NAMA_ANGGOTA']);
//Set the subject line
$mail->Subject = 'Reset Password Anggota Koperasi Danamon Surabaya';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//--------- update pasword
$password = 'admin2019';
$data = array( 
	'password' => md5($password) 
);		
$exec= $db->update("anggota", $data,"EMAIL = '$_GET[email]'");	
//---------- end update passwor
$text="Dengan Hormat, <br>

	   Bapak / Ibu $val[nama_anggota]<br>
		Password Baru anda adalah $password	 <br>  
	  	Segera ubah password saat login pertama<br>
	   
	   Hormat Kami <br>
	   Kopkar Danamon Surabaya
	   
	   ";
$mail->msgHTML("$text");
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
	$row_set[] = array("status"=>$mail->ErrorInfo);
	//echo json_encode($row_set);
	echo $_GET['callback']."(".json_encode($row_set).")";

} else {
	$row_set[] = array("status"=>"Email Terkirim");
	//echo json_encode($row_set);
	echo $_GET['callback']."(".json_encode($row_set).")";

}

}else{
	$row_set[] = array("status"=>"Email Tidak Ditemukan");
	//echo json_encode($row_set);
	echo $_GET['callback']."(".json_encode($row_set).")";

}
