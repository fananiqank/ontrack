<?php	 
date_default_timezone_set('Asia/Jakarta');
require( './funlibs.php' );
require "PHPMailerAutoload.php";
session_start();

 		//$jmldetail=$con->select("pj_penjualan_dtl a join m_barang b on a.id_barang=b.id_barang join m_satuan c on b.id_satuan=c.id_satuan","a.id_dtl_jual,a.id_barang,b.kode_barang,b.nama_barang,c.nama_satuan,a.harga_jual,a.dtl_total,a.qty_jual","a.id_pj='$_GET[id]'");				
$con=new Database();
		//departemen
		foreach($con->select("(select a.name as namepeople, c.name as namedept, b.title,b.mobile,b.statusid,b.id as iddtl,b.typereq,a.email,b.alasanbutuh,b.tgl_butuh1,b.tgl_butuh2,b.akunakses,b.websiteakses,b.hasilpengecekanaset,b.assetid,d.headpeopleid,headstatus,DATE(headappdate) as headappdate,d.itpeopleid,itstatus,DATE(itappdate) as itappdate,b.locationid,DATE(b.created_date) as created_date_dtl,e.name as locationname,a.id as idpep from people a join people_dtl_request b on a.id=b.peopleid join clients c on b.clientid=c.id join people_dtl_approve d on b.id=d.peopledtlid join locations e on b.locationid=e.id
where b.id = '$data[id]' ORDER BY b.id DESC) a","*","") as $dpt){}

		foreach($con->select("people","*","id = '$dpt[headpeopleid]'") as $head){}
		foreach($con->select("people","*","id = '$dpt[itpeopleid]'") as $ithead){}


		if($dpt['typereq'] == 1) {$ctn = "Permintaan Akun"; $preq = "newrequestacc";}
		else if($dpt['typereq'] == 2) {$ctn = "Izin Akses Data"; $preq = "newrequestaci";}
		else if($dpt['typereq'] == 4) {$ctn = "Penutupan Akun"; $preq = "newrequestacp";}

		if($dpt[headstatus] == 'Approve'){$headstatus = "Approved";} else {$headstatus= $dpt[headstatus];}
		if($dpt[itstatus] == 'Approve'){$itstatus = "Approved";} else {$itstatus= $dpt[itstatus];}
		
	//email ke PIC IT blast email
		foreach($con->select("tickets","ticket,adminid","people_dtl_request_id = '$data[id]'") as $notiket){
			$expem = explode(';',$notiket[adminid]);
            foreach($expem as $key => $value){
                foreach($database->query("select email,name from people where id = '$value'") as $pemail){}
            	
	            if ($pemail['email'] != ''){
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
					$mail->addAddress($pemail['email'] ,$pemail['name']);
					//Set the subject line
					$mail->Subject = 'New Ticket Request '.$ctn.' created : '.$dpt['namepeople'].'';
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
				
					$text = "<p>Hello IT Team,<br><br>A new ticket has been created for your task.<br>Ticket ID: <b>".$notiket['ticket']."</b><br><br> 
				            Name : ".$dpt['namepeople']."<br>
				            Dept : ".$dpt['namedept']."-".$dpt['locationname']."<br>
				            Jabatan : ".$dpt['title']."<br>
				            Please check your ticket task in <a href='http://192.168.31.25/ontrack'>Erassets</a><br><br>
				            View Form Request Follow this <a href='http://192.168.31.25/eraticket/cetak.php?page=$ctk&id=$data[id]'>Link</a>
				            <br><br>.<br><br>Best regards,<br>IT Ticketing Admin</p>";
				
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
            }
		}

	
	//email ke user	   
		$asign=$con->query("select email,name from people where id = '$dpt[idpep]'");   
        foreach($asign as $is){
        		if ($is['email'] != ''){
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
					$mail->addAddress($is['email'] ,$is['name']);
					//Set the subject line
					$mail->Subject = 'IT APPROVAL Request '.$ctn.' : '.$dpt['namepeople'].'';
					//Read an HTML message body from an external file, convert referenced images to embedded,
					
						$text="Form Request ".$ctn.", here's the details : <br>
	                    <br>
	                        <table width='50%' cellpadding='0' cellspacing='0' border='0'>                   
	                          <tr>
	                            <td align='left' width='20%'><strong>Name</strong></td>
	                            <td align='left' width='2%'>:</td>
	                            <td align='left'><strong>$dpt[namepeople]</strong></td>
	                          </tr>
	                          <tr>
	                            <td align='left'><strong>Dept</strong></td>
	                            <td align='left' width='2%'>:</td>
	                            <td align='left'>$dpt[namedept] - $dpt[locationname]</td>
	                          </tr>
	                          <tr>
	                            <td align='left'><strong>Jabatan</strong></td>
	                            <td align='left' width='2%'>:</td>
	                            <td align='left'>$dpt[title]</td>
	                          </tr>
	                          <tr>
	                            <td align='left'><strong>Created Date</strong></td>
	                            <td align='left' width='2%'>:</td>
	                            <td align='left'>$dpt[created_date_dtl]</td>
	                          </tr>
	                          <tr>
	                            <td align='left'>&nbsp;</td>
	                            <td align='left' width='2%'>&nbsp;</td>
	                            <td align='left'>&nbsp;</td>
	                          </tr>
	                          <tr>
	                            <td align='left'><strong>Head Approve</strong></td>
	                            <td align='left' width='2%'>:</td>
	                            <td align='left'>$head[name]</td>
	                          </tr>
	                          <tr>
	                            <td align='left'><strong>Status</strong></td>
	                            <td align='left' width='2%'>:</td>
	                            <td align='left'>$headstatus</td>
	                          </tr>
	                          <tr>
	                            <td align='left'><strong>Approve Date</strong></td>
	                            <td align='left' width='2%'>:</td>
	                            <td align='left'>$dpt[headappdate]</td>
	                          </tr>
	                          <tr>
	                            <td align='left'>&nbsp;</td>
	                            <td align='left' width='2%'>&nbsp;</td>
	                            <td align='left'>&nbsp;</td>
	                          </tr>
	                          <tr>
	                            <td align='left'><strong>IT Approve</strong></td>
	                            <td align='left' width='2%'>:</td>
	                            <td align='left'>$ithead[name]</td>
	                          </tr>
	                          <tr>
	                            <td align='left'><strong>Status</strong></td>
	                            <td align='left' width='2%'>:</td>
	                            <td align='left'>$itstatus</td>
	                          </tr>
	                          <tr>
	                            <td align='left'><strong>IT Approve Date</strong></td>
	                            <td align='left' width='2%'>:</td>
	                            <td align='left'>$dpt[itappdate]</td>
	                          </tr>
	                        </table>

	                     <br>
	                    <br>
	                    View Progress Request Follow this <a href='http://192.168.31.25/eraticket/index.php?x=viewnewreq&d=$data[id]'>Link</a>
	                        <br>
	                        <br>
	                        regards, <br>
	                        IT Ticketing Admin <br>";

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
