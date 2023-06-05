<?php
session_start();
/**
 * This example shows making an SMTP connection with authentication.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Asia/Jakarta');

require '../../plugin/phpmailer/PHPMailerAutoload.php';
include "../../webclass.php";
$db=new kelas();

foreach($db->select("pengajuan_kredit a JOIN tm_jangs b on a.jangsuran=b.id_ang","*","id_ajuan='$_POST[id]'") as $val){
	
	}
foreach($db->select("tm_pegawai","*","id_cabang='$_SESSION[CABANG]' AND ID_JABATAN='1'") as $vpeg){
	}	
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
$mail->Username = "manman848484@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "sayasukamanual1";
//Set who the message is to be sent from
$mail->setFrom('support@bprjatim.com', 'e-Paperless Approve Notification');
//Set an alternative reply-to address
$mail->addReplyTo('support@bprjatim.com', 'e-Paperless Approve Notification');
//Set who the message is to be sent to
$mail->addAddress($vpeg['EMAIL'], $vpeg['NAMA_LENGKAP']);
//Set the subject line
$mail->Subject = 'e-Paperless Approve Notification';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$text="Yth. <br>
	   Bpk/Ibu $vpeg[NAMA_LENGKAP]<br>
	   
	   Berdasarkan pengajuan kredit oleh $_SESSION[NAMA_PEG], dengan rincian pengajuan sebagai berikut :<br>
	   <br>
	   Nomor Pengajuan		: $val[no_ajuan] <br>
	   Nama Calon Nasabah	: $val[nama_lengkap]<br>
	   Alamat				: $val[alamat]<br>
	   Tujuan Pinjaman		: $val[tujuan]<br>
	   Nilai Pinjaman		: $val[pinjaman]<br>
	   Jangka Waktu			: $val[jangkawaktu] Bulan<br>
	   Bunga				: $val[bunga] %<br>
	   Jenis Angsuran		: $val[nama_angs]<br>
	   <br>
	   Bahwasanya Pengajuan Tersebut, memerlukan disposisi untuk dilakukan BI Cheking sebelum di-disposikan ke tahap selanjutnya.<br>
	   
	   Terima Kasih,<br>
	   <br>
	   <br>
	   e-Paperless BPR JATIM<br><br><br>
	   
	   Email ini dikirim secara otomatis oleh sistem. Anda tidak perlu membalas atau mengirim email ke alamat ini.<br>
	   This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity 			to whom they are addressed. If you have received this email in error, please notify the system manager. This message contains confidential information and is intended only for the individual named. If you are not the named addressee, you should not disseminate, distribute or copy this email. Please notify the sender immediately by email if you have received this email by mistake and delete this email from your system. If you are not the intended recipient, you are notified that disclosing, copying, distributing or taking any action in reliance on the contents of this information is strictly prohibited	   
	   ";
$mail->msgHTML("$text");
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
