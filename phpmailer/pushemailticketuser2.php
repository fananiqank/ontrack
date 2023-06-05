<?php	 
date_default_timezone_set('Asia/Jakarta');
if($jumepp == 0){
	require( './funlibs.php' );
	require "PHPMailerAutoload.php";
}
session_start();

 		//$jmldetail=$con->select("pj_penjualan_dtl a join m_barang b on a.id_barang=b.id_barang join m_satuan c on b.id_satuan=c.id_satuan","a.id_dtl_jual,a.id_barang,b.kode_barang,b.nama_barang,c.nama_satuan,a.harga_jual,a.dtl_total,a.qty_jual","a.id_pj='$_GET[id]'");				
$con=new Database();
		//departemen
		foreach($con->select("tickets a join people b on a.userid=b.id","a.*,b.name as namepeople,DATE(a.modifydate) as datenow","a.id = '$id'") as $dpt){}
		
        		if ($dpt['email'] != ''){
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
					$mail->addAddress($dpt['email'] ,$dpt['namepeople']);
					//Set the subject line
					$mail->Subject = 'Ticket No. '.$dpt['ticket'].' New Reply : '.$status;
					//Read an HTML message body from an external file, convert referenced images to embedded,
					
					$text="<p>Hello ".$dpt['namepeople'].",<br><br>
								A new reply has been added to your ticket.<br>
								Ticket ID: ".$dpt['ticket']."<br>
								Subject  : ".$dpt['subject']."<br>
								Email : ".$dpt['email']."<br>
								".$messageReply."<br><br>";
					if($status == 'Finished'){
						if($dpt['people_dtl_request_id'] != ''){
							$text .="Your Ticket ".$dpt['ticket']." Has Finished, please check your problem and close follow <a href='http://192.168.31.25/eraticket/index.php?x=viewnewreq&d=$dpt[people_dtl_request_id]'>Link</a> or ticket will automatically close in 24 hour / on ".$dpt['datenow']." <br>
								if you still have problem after ticket closed please create new ticket to contact us.<br>
								<br>Best regards,<br>IT Ticketing Admin<br></p>";
						} else {
							$text .="Your Ticket ".$dpt['ticket']." Has Finished, please check your problem and close follow <a href='http://192.168.31.25/eraticket/index.php?x=view&d=$data[ticketid]'>Link</a> or ticket will automatically close in 24 hour / on ".$dpt['datenow'].".<br> if you still have problem after ticket closed please create new ticket to contact us.<br>
								<br>Best regards,<br>IT Ticketing Admin<br></p>";
						}

					} else {
						if($dpt['people_dtl_request_id'] != ''){
							$text .="You can track progress of this request regularly through <a href='http://192.168.31.25/eraticket/index.php?x=viewnewreq&d=$dpt[people_dtl_request_id]'>Link</a>. <br>
									Please do not remove the ticket number from the subject line.<br><br>Best regards,<br>IT Ticketing Admin<br></p>";
						} else {
							$text .="You can track progress of this request regularly through <a href='http://192.168.31.25/eraticket/index.php?x=view&d=$id'>Link</a>. <br>
									Please do not remove the ticket number from the subject line.<br><br>Best regards,<br>IT Ticketing Admin<br></p>";
						}
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
				else if ($dpt['email'] == ''){
					$row_set[] = array("status"=>"Email Tidak Ditemukan");
					//echo json_encode($row_set);
					//echo $_GET['callback']."(".json_encode($row_set).")";

				}
		
