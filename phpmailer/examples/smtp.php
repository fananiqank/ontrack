<?php
/**
 * This example shows making an SMTP connection with authentication.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Asia/Jakarta');

require 'phpmailer/PHPMailerAutoload.php';
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
$mail->setFrom('support@bprjatim.com', 'e-Paperless Login');
//Set an alternative reply-to address
$mail->addReplyTo('support@bprjatim.com', 'e-Paperless Login');
//Set who the message is to be sent to
$mail->addAddress($_POST['email'], $_POST['nama']);
//Set the subject line
$mail->Subject = 'Userlogin e-Paperless';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$text="Yth. <br>
	   Bpk/Ibu $_POST[nama]<br>
	   
	   Anda Telah terdaftar e-Paperless BPR Jatim, dengan informasi login sebagai berikut :<br>
	   <br>
	   username : $_POST[userlogin]<br>
	   password : $password<br>
	   <br>
	   Segera lakukan penggantian password setelah anda dapat melakukan login pada aplikasi.<br>
	   
	   Terima Kasih,<br>
	   <br>
	   <br>
	   e-Paperless BPR JATIM<br><br><br>
	   
	   Email ini dikirim secara otomatis oleh sistem. Anda tidak perlu membalas atau mengirim email ke alamat ini.
	   
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
