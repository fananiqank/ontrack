<?php	 

date_default_timezone_set('Asia/Jakarta');
require( './funlibs.php' );
require "PHPMailerAutoload.php";
session_start();
$con=new Database();

	foreach($con->select("tickets a join people b on a.userid=b.id join people_dtl_request c on a.people_dtl_request_id=c.id","a.*,b.name as namepeople,DATE(a.modifydate) as datenow,c.norequest","a.id = '$cekold[id]'") as $dpt){}

	$expp = explode("|",$eppa);
	for($i=0;$i<$jumepp;$i++){
	//explode id admin & ticketid
		$expap = explode("_",$expp[$i]);
	//select ticketid child
		foreach($con->select("tickets","*","id = '$expap[1]'") as $tck){}
	//explode id admin
		$expab = explode(";",$expap[0]);
		$jumab = count($expab);
		for($j=0;$j<$jumab;$j++){
			foreach($con->select("people","*","id = '$expab[$j]'") as $email){}
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
					$mail->addAddress($email['email'] ,$email['name']);
					//Set the subject line
					$mail->Subject = 'Ticket No.'.$dpt['ticket'].' '.$dpt['subject'].": ".$status;
					//Read an HTML message body from an external file, convert referenced images to embedded,
					
					$text="<p>Hello ".$email['name'].",<br><br>
							Ticket No.".$dpt['ticket']." has been completed by ".$liu['name'].".<br>
							here's the details,<br>
							Req ID: ".$dpt['norequest']."<br>
							Name  : ".$dpt['namepeople']."<br>
							Email : ".$dpt['email']."<br>
							Subj  : ".$messageReply."<br><br>
							Please complete your Ticket No.".$tck['ticket'].",
							check your Ticket in <a href='http://192.168.31.25/ontrack'>Erassets</a>
							<br>Best regards,<br>IT Ticketing Admin<br></p>";

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
				else if ($dpt['email'] == ''){
					$row_set[] = array("status"=>"Email Tidak Ditemukan");
					//echo json_encode($row_set);
					//echo $_GET['callback']."(".json_encode($row_set).")";

				}
		}
				
	}
	
        		
		
