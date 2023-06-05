<?php	 
date_default_timezone_set('Asia/Jakarta');
require( './funlibs.php' );
require "PHPMailerAutoload.php";
session_start();
$datenow = date('Y-m-d H:i:s');
$con=new Database();
		
		//email ke TS (SPV)	   
				if ($email['email'] != ''){
					//Create a new PHPMailer instance
					$mail = new PHPMailer;
					//Tell PHPMailer to use SMTP
					$mail->isSMTP();
					$mail->SMTPDebug = 0;
					//Ask for HTML-friendly debug output
					$mail->Debugoutput = 'html';
					//Set the hostname of the mail server
					//$mail->Host = "ssl://smtp.eratex.co.id";
					$mail->Host = "192.168.31.7";
					//Set the SMTP port number - likely to be 25, 465 or 587
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
					$mail->addAddress($email['email'],$email['name']);
					//Set the subject line
					$mail->Subject = 'Reminder Approval Request '.$tclose['typereqshow'].' : Request';
					//Read an HTML message body from an external file, convert referenced images to embedded,
					
						$text="<p>Hello ".$email[name].",<br><br>
								There's outstanding approval request $tclose[namepeople] ($tclose[norequest]), which need your help. <br> 
								Please Approve this request in <a href='http://192.168.31.25/eraticket/login.php'>Eraticket</a>.<br><br>
								if the request not approve until 3 days, system will cancel automatically.
								and you can create new request again. 
								
								<br><br>regards, <br>
								IT Ticketing Admin <br>
						   ";

	            	$mail->msgHTML("$text");
					//Replace the plain text body with one created manually
					$mail->AltBody = 'This is a plain-text message body';
					//Attach an image file
					
					if (!$mail->send()) {
						$row_set[] = array("status"=>$mail->ErrorInfo);
						//echo json_encode($row_set);
						//echo $_GET['callback']."(".json_encode($row_set).")";
					
					} else {
						//echo "b";
						$row_set[] = array("status"=>"Email Terkirim");
						//echo json_encode($row_set);
						//echo $_GET['callback']."(".json_encode($row_set).")";
					
					}

				}
				else if ($_POST['email'] == ''){
					$row_set[] = array("status"=>"Email Tidak Ditemukan");
					//echo json_encode($row_set);
					//echo $_GET['callback']."(".json_encode($row_set).")";

				}
		

	
