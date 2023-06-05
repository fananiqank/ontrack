<?php	 
date_default_timezone_set('Asia/Jakarta');
require( './funlibs.php' );
require "PHPMailerAutoload.php";
session_start();
$con=new Database();
		//departemen
		foreach($con->select("(select a.*,b.name as namepeople, c.name as nameclient
from tickets a join people b on a.userid=b.id left join clients c on a.clientid=c.id
where a.id = '".$data['id']."') a","*","") as $dpt){}
		
		foreach($con->select("people","*","id = '".$data['mentionid']."'") as $head){}
		if ($head['email'] != ''){
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
					//$mail->Host = "ssl://smtp.eratex.co.id";
					$mail->Host = "192.168.31.7";
					//Set the SMTP port number - likely to be 25, 465 or 587
					//$mail->Port = 465;
					//Whether to use SMTP authentication
					$mail->SMTPAuth = true;
					//$mail->SMTPSecure = 'tls'; 
					//Username to use for SMTP authentication
					$mail->Username = "sby-helpdesk@eratex.co.id";
					//Password to use for SMTP authentication
					$mail->Password = "Veritas.ertx12#";
					//Set who the message is to be sent from
					$mail->setFrom('sby-helpdesk@eratex.co.id', 'IT Ticketing Eratex Djaja ,Tbk');
					//Set an alternative reply-to address
					$mail->addReplyTo('sby-helpdesk@eratex.co.id', 'IT Ticketing Eratex Djaja ,Tbk');
					//Set who the message is to be sent to
					$mail->addAddress($head['email'] ,$head['name']);
					//Set the subject line
					$mail->Subject = 'Invitation Discuss Ticket : '.$dpt[ticket];
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
				
					$text = "<p>Hello ".$head['name'].",<br><br>Need your help for this ticket :<br>
					        No Ticket : ".$dpt['ticket']."<br>
					        Name : ".$dpt['namepeople']."<br>
				            Dept : ".$dpt['nameclient']."<br>
				            Please check your Discuss Ticket in <a href='http://192.168.31.25/ontrack'>Erassets</a><br><br>
				            <br>.<br><br>Best regards,<br>IT Ticketing Admin</p>";
				
					$mail->msgHTML("$text");
					//Replace the plain text body with one created manually
					$mail->AltBody = 'This is a plain-text message body';
					//send the message, check for errors
					
					if (!$mail->send()) {
						$row_set[] = array("status"=>$mail->ErrorInfo);
					} else {
						$row_set[] = array("status"=>"Email Terkirim");
					}
		}
		else if ($head['email'] == ''){
			$row_set[] = array("status"=>"Email Tidak Ditemukan");
		}