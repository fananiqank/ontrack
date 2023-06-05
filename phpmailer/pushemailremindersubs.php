<?php	 
date_default_timezone_set('Asia/Jakarta');
require( '../funlibs.php' );
require "PHPMailerAutoload.php";
session_start();
$datenow = date('Y-m-d H:i:s');
$con=new Database();
		
		$detail ="<table class='table' width='100%' border='1'>
					<tr>
						<td>License Name</td>
						<td>Start Subscription</td>
						<td>End Subscription</td>
						<td>Remaining Days</td>
					</tr>
		";
		foreach($con->select("(select b.name as licensename,a.id as iddtl,a.startsubsdate,a.endsubsdate,
		DATEDIFF(endsubsdate,DATE(now())) selisih from licenses_dtl a join licenses b on a.licensesid=b.id
where COALESCE(startsubsdate,0) <> 0 and DATEDIFF(endsubsdate,DATE(now())) < 60 and b.statusid > 0 and a.statusid > 0) a","*") as $listlic){
					$detail.="<tr>
						  <td>".$listlic['licensename']."</td>
						  <td>".$listlic['startsubsdate']."</td>
						  <td>".$listlic['endsubsdate']."</td>
						  <td>".$listlic['selisih']."</td>
					</tr>";
		}
		$detail .="</table>";
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
					$mail->Subject = 'Reminder License Subscription';
					//Read an HTML message body from an external file, convert referenced images to embedded,
					
						$text="<p>Hello SA Team,<br><br>
								You have a subscription license that will expire <br>
								This is list : <br><br>".$detail."
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
		

	
