<?php	 
date_default_timezone_set('Asia/Jakarta');
require( './funlibs.php' );
require "PHPMailerAutoload.php";
session_start();
$datenow = date('Y-m-d H:i:s');
$con=new Database();
		
		//email ke TS (SPV)	   
				if ($tclose['email'] != ''){
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
					$mail->addAddress($tclose['email'],$tclose['namepeople']);
					//Set the subject line
					$mail->Subject = 'Reminder Confirm Ticket No. '.$tclose['ticket'].' : '.$tclose['status'];
					//Read an HTML message body from an external file, convert referenced images to embedded,
					
						$text="<p>Hello $tclose[namepeople],<br><br>
								There's ticket ".$tclose['ticket']." need your help for confirmation. <br>";
							if($dpt['people_dtl_request_id'] != ''){
								$text .="Please confirm the ticket via the <a href='http://192.168.31.25/eraticket/index.php?x=viewnewreq&d=$tclose[people_dtl_request_id]'>Link</a>. <br>
									Ticket will automatically close within 24 hours of finished status. 
										Please do not remove the ticket number from the subject line.<br><br>Best regards,<br>IT Ticketing Admin<br></p>";
							} else {
								$text .="Please confirm the ticket via the <a href='http://192.168.31.25/eraticket/index.php?x=view&d=$tclose[id]'>Link</a>. <br>
									Ticket will automatically close within 24 hours of finished status.
										Please do not remove the ticket number from the subject line.<br><br>Best regards,<br>IT Ticketing Admin<br></p>";
							} 

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
		

	
