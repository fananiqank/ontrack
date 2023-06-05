<?php	
//echo "cac";
date_default_timezone_set('Asia/Jakarta');
//include "../funlibs.php";
require "phpmailer/PHPMailerAutoload.php";

session_start();
 
 // echo "jem";
 //die;
 		//$jmldetail=$con->select("pj_penjualan_dtl a join m_barang b on a.id_barang=b.id_barang join m_satuan c on b.id_satuan=c.id_satuan","a.id_dtl_jual,a.id_barang,b.kode_barang,b.nama_barang,c.nama_satuan,a.harga_jual,a.dtl_total,a.qty_jual","a.id_pj='$_GET[id]'");				
					
//$con=new Dbase();

	// $tabel = "trdata";
	// $fild  = "*"; 
	// $where = "email_data ='$smail[email_data]'";
	// $data=$con->select($tabel,$fild,$where);
	// //var_dump($data);
	// //die;
	// foreach($data as $val){}
	// //echo $val['EMAIL'];
	// //die;

 
        // $jmldetail=$con->select("trdata a left join mtdept b on a.departemen_data=b.id_dept
        // 	left join trwo c on a.id_data = c.id_data 
        // 	left join mtagent d on c.id_agent = d.id_agent 
        // 	left join mtpegawai e on d.id_pegawai = e.id_pegawai",
        // 	"a.*,b.nama_dept,e.nama_pegawai",
        // 	"a.no_ticket='$idgen'");   
        // $no=1;
        // foreach($jmldetail as $d){}                     
        //                 if ($d['status_data'] == '1'){
        //                   $statuse = "OPEN";
        //                 } else if ($d['status_data'] == '2'){
        //                   $statuse = "ON PROGRESS";
        //                 } else if ($d['status_data'] == '3'){
        //                   $statuse = "HOLD";
        //                 } else if ($d['status_data'] == '4'){
        //                   $statuse = "CLOSED";
        //                 } else if ($d['status_data'] == '5'){
        //                   $statuse = "HOLD REQUEST";
        //                 } else if ($d['status_data'] == '6'){
        //                   $statuse = "ASSIGN";
        //                 } else if ($d['status_data'] == '0'){
        //                   $statuse = "REJECT";
        //                 }
     // echo "select a.*,b.nama_dept from trdata a left join mtdept b on a.departemen_data=b.id_dept where a.no_ticket='$idgen'";            
   //      $asign=$con->select("(select '' as agent,a.email_data as email,nama_data as nama FROM
			// trdata a left join mtdept b on a.departemen_data=b.id_dept
   //      	left join trwo c on a.id_data = c.id_data 
   //      	left join mtagent d on c.id_agent = d.id_agent 
   //      	left join mtpegawai e on d.id_pegawai = e.id_pegawai
   //      	where a.no_ticket='$idgen'
   //      	union
   //      	select d.id_agent as agent,e.email as email,e.nama_pegawai as nama FROM
			// mtagent d left join mtpegawai e on d.id_pegawai = e.id_pegawai
			// left join mauser_login f on e.id_pegawai = f.id_pegawai
   //   		where f.ID_ROLE = '1' and f.STATUS = '1') as b","agent,email,nama"); 
   //   		echo  "select agent,email,nama from (select '' as agent,a.email_data as email,nama_data as nama FROM
			// trdata a left join mtdept b on a.departemen_data=b.id_dept
   //      	left join trwo c on a.id_data = c.id_data 
   //      	left join mtagent d on c.id_agent = d.id_agent 
   //      	left join mtpegawai e on d.id_pegawai = e.id_pegawai
   //      	where a.id_data='$idgen'
   //      	union
   //      	select d.id_agent as agent,e.email as email,e.nama_pegawai as nama FROM
			// mtagent d left join mtpegawai e on d.id_pegawai = e.id_pegawai
			// left join mauser_login f on e.id_pegawai = f.id_pegawai
   //   		where f.ID_ROLE = '1' and f.STATUS = '1') as b";
   //   		die;
//foreach($asign as $is){
			if ($to != ''){
				$nopj = "idgen";
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
				$mail->SMTPAuth = false;
				//$mail->SMTPSecure = 'tls'; 
				//Username to use for SMTP authentication
				$mail->Username = "sby-helpdesk@eratex.co.id";
				//Password to use for SMTP authentication
				$mail->Password = "";
				//Set who the message is to be sent from
				$mail->setFrom('sby-helpdesk@eratex.co.id', 'IT Assets Eratex Djaja ,Tbk');
				//Set an alternative reply-to address
				$mail->addReplyTo('sby-helpdesk@eratex.co.id', 'IT Assets Eratex Djaja ,Tbk');
				//Set who the message is to be sent to
				$expemail = explode(';', $to);
				$jmlemail = count($expemail);
				for ($i=0; $i < $jmlemail; $i++) { 
					$mail->addAddress($expemail[$i]);
				}
				//$mail->addAddress('alfian.syahroni@eratex.co.id');
				//Set the subject line
				$mail->Subject = $subject;
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				//--------- update pasword
				// $password = 'admin2019';
				// $data = array( 
				// 	'password' => md5($password) 
				// );		
				// $exec= $con->update("anggota", $data,"EMAIL = '$smail[EMAIL]'");	
				//---------- end update passwor

				//$isian = include 'detail_email.php';
				
					$text="Ticket $nopj has been created, here's the details : <br>
							<br>
								<table width='50%' cellpadding='0' cellspacing='0' border='0'>                   
								  <tr style='padding:5%;'>
								    <td align='left' width='20%'><strong>Ticket No</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'><strong>$nopj</strong></td>
								  </tr>
								 </table>

							 <br>
							<br>
							View Ticket Click <a href='http://192.168.31.35/eraticket/content.php?p=rq2&d=$iddata&e=1'>Here</a>
							<br>
							<br>
							<br>
							regards, <br>
							IT Ticketing Admin <br>
					   
						   ";

				$mail->msgHTML("$message");
				//Replace the plain text body with one created manually
				$mail->AltBody = 'This is a plain-text message body';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');
				
				//send the message, check for errors
				
				if (!$mail->send()) {
					$row_set[] = array("status"=>$mail->ErrorInfo);
					//echo json_encode($row_set);
					//echo $_GET['callback']."(".json_encode($row_set).")";
				
				} else {
					$row_set[] = array("status"=>"Email Terkirim");
					//echo json_encode($row_set);
					//echo $_GET['callback']."(".json_encode($row_set).")";
				
				}

			}
			else if ($to == ''){
				$row_set[] = array("status"=>"Email Tidak Ditemukan");
				//echo json_encode($row_set);
				//echo $_GET['callback']."(".json_encode($row_set).")";

			}
//}
