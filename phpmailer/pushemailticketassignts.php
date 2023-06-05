<?php	 
date_default_timezone_set('Asia/Jakarta');
require( './funlibs.php' );
require "PHPMailerAutoload.php";
session_start();
$datenow = date('Y-m-d H:i:s');
$con=new Database();
		//departemen
		foreach($con->select("(select a.*,b.nodenumber,b.machine_id,c.name as namepeople, d.name as namedept,
			e.name as locationname from tickets a left join assets b on a.assetid=b.id left join people c on a.userid=c.id 
left join clients d on c.clientid=d.id left join locations e on c.locationid=e.id where a.id = '$data[id]') a","*","") as $dpt){}

		$expa = explode(";",$data[adminid]);
		$juma = count($expa);
		for($i=0;$i<$juma;$i++){
			foreach($con->select("people","*","id = $expa[$i]") as $staff){}
		//email ke TS (staff)	   
				if ($staff['email'] != ''){
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
					$mail->addAddress($staff['email'],$staff['name']);
					//Set the subject line
					$mail->Subject = 'Ticket No.'.$dpt[ticket].' : Assigned';
					//Read an HTML message body from an external file, convert referenced images to embedded,
					
						$text="Hello $staff[name], <br><br>
							   Ticket $dpt[ticket] has assigned to you,<br> 
							   here's the details : <br>
							<br>
								<table width='50%' cellpadding='0' cellspacing='0' border='0'>                   
								  <tr style='padding:5%;'>
								    <td align='left' width='20%'><strong>Ticket No</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'><strong>$dpt[ticket]</strong></td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Created Date</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$dpt[timestamp]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Commissioned by</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$liu[name]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Name</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$dpt[namepeople]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Dept</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$dpt[namedept] - $dpt[locationname]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Machine ID</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$dpt[machine_id] / $dpt[nodenumber]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Problem</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$dpt[subject]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Status</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'><strong>Assigned</strong></td>
								  </tr>
								</table>

							 <br>
							<br>
							Please check your ticket task in <a href='http://192.168.31.25/ontrack'>Erassets</a><br><br>
							<br>
							regards, <br>
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
		}
			
		

	//email ke User
	            if ($dpt['email'] != ''){
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
					$mail->addAddress($dpt['email'] ,$dpt['name']);
					//Set the subject line
					$mail->Subject = 'Ticket No. '.$dpt['ticket'].' New Reply : Assigned';
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
				
					$text="<p>Hello ".$dpt['namepeople'].",<br><br>
								A new reply has been added to your ticket.<br>
								Ticket ID: ".$dpt[ticket]."<br>
								Assigned to : ";
					for($j=0;$j<$juma;$j++){
						foreach($con->select("people","*","id = $expa[$j]") as $stuff){}
						echo $stuff['name'].", ";
					}

							if($dpt['people_dtl_request_id'] != ''){
								$text .="You can track progress of this request regularly through <a href='http://192.168.31.25/eraticket/index.php?x=viewnewreq&d=$dpt[people_dtl_request_id]'>Link</a>. <br>
										Please do not remove the ticket number from the subject line.<br><br>Best regards,<br>IT Ticketing Admin<br></p>";
							} else {
								$text .="You can track progress of this request regularly through <a href='http://192.168.31.25/eraticket/index.php?x=view&d=$data[id]'>Link</a>. <br>
										Please do not remove the ticket number from the subject line.<br><br>Best regards,<br>IT Ticketing Admin<br></p>";
							}
							
				
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
				else if ($_POST['email'] == ''){
					$row_set[] = array("status"=>"Email Tidak Ditemukan");
					
				}
          
		