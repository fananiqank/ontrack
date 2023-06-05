<?php	 
date_default_timezone_set('Asia/Jakarta');
require( './funlibs.php' );
require "PHPMailerAutoload.php";
session_start();
$datenow = date('Y-m-d H:i:s');
$con=new Database();
		//departemen
		foreach($con->select("(select a.name as namepeople, c.name as namedept, b.title,b.mobile,b.statusid,b.id as iddtl,b.typereq,a.email,b.alasanbutuh,b.tgl_butuh1,b.tgl_butuh2,b.akunakses,b.websiteakses,b.hasilpengecekanaset,b.assetid,d.headpeopleid,headstatus,DATE(headappdate) as headappdate,d.itpeopleid,itstatus,DATE(itappdate) as itappdate,b.locationid,DATE(b.created_date) as created_date_dtl,e.name as locationname,a.id as idpep from people a join people_dtl_request b on a.id=b.peopleid join clients c on b.clientid=c.id join people_dtl_approve d on b.id=d.peopledtlid join locations e on b.locationid=e.id
where b.id = '$_POST[peopledtlid]' ORDER BY b.id DESC) a","*","") as $dpt){}
		
		//email ke TS (SPV)	   
				if ($emailadmin['email'] != ''){
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
					$mail->addAddress($emailadmin['email'],$emailadmin['name']);
					//Set the subject line
					$mail->Subject = 'Ticket No.'.$noticket.' : Open';
					//Read an HTML message body from an external file, convert referenced images to embedded,
					
						$text="Hello IT Team, A new ticket $noticket has created for your task,<br> 
							   here's the details : <br>
							<br>
								<table width='50%' cellpadding='0' cellspacing='0' border='0'>                   
								  <tr style='padding:5%;'>
								    <td align='left' width='20%'><strong>Ticket No</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'><strong>$noticket</strong></td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Created Date</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$datenow</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Created By / Commissioned by</strong></td>
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
								    <td align='left'>$ascok[machine_id] / $ascok[nodenumber]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Problem</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$_POST[remark]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Status</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'><strong>Open</strong></td>
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
					$mail->Subject = 'New Ticket No. '.$noticket.' created : Open';
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
				
					$text="<p>Hello ".$dpt['namepeople'].",<br><br>
								Your new ticket has created.<br>
								Ticket ID: ".$noticket."<br>
								Subject  : ".$_POST['tsnotes']."<br><br>
							You can track progress of this ticket regularly through <a href='http://192.168.31.25/eraticket/index.php?x=viewnewreq&d=$_POST[peopledtlid]'>Link</a>. <br>
									Please do not remove the ticket number from the subject line.<br><br>Best regards,<br>IT Ticketing Admin<br></p>
						  </p>";
				
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
          
		//email ke User
	            if ($liu['email'] != ''){
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
					$mail->Subject = 'New Ticket No. '.$noticket.' created to be assigned to TS';
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
				
					$text="<p>Hello ".$liu['name'].",<br><br>
								The assignment has created a new ticket <br>
								and has assigned to the $emailadmin[name].<br><br>
								Ticket ID: ".$noticket."<br>
								Subject  : ".$_POST['tsnotes']."<br><br>
							You can track progress of this ticket regularly through <a href='http://192.168.31.25/eraticket/index.php?x=viewnewreq&d=$_POST[peopledtlid]'>Link</a> <br> 
							or check <a href='http://192.168.31.25/ontrack'>Erassets</a>.
							<br><br>Best regards,<br>IT Ticketing Admin<br></p>
						  </p>";
				
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
	
