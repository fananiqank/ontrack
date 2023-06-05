<?php

##################################
###     GET DATA FOR PAGES     ###
##################################

// PDF
if ($route == "pdf") {
	$html = '<style>@page { margin: 10px; } p { margin: 0px; } h4 { margin: 4px; } </style>';

	if(!isset($_GET['type'])) die("'type' parameter is missing"); else $type = $_GET['type'];
	//if(!isset($_GET['id'])) die("'id' parameter is missing"); else $id = $_GET['id'];



	if($type == "assetlabel") {

		$label_width = getConfigValue("label_width");
		$label_height = getConfigValue("label_height");
		$label_qrsize = getConfigValue("label_qrsize");

		$asset = getRowById("assets",$_GET['id']);
        foreach($database->query("select a.name as assetname, DATE_SUB(a.purchase_date, INTERVAL -(warranty_months) MONTH) endwarranty from assets a where a.id = $_GET[id]") as $asl){}
		if($asset['assettype'] == 1){$typeaset = "Owned"; $typepurch = "Purc Date: ".$asset['purchase_date'];
			$endwarranty = "</td><td valign='top' style='font-size:8px;' width='65%'><b>End Warranty : ".$asl['endwarranty']."</b></td></tr>";
		}
		else{$typeaset = "Rent";$typepurch = "End date: ".$asset['endcontract'];
			$endwarranty="";
		}
		if(getConfigValue("manual_qrvalue") == 'true') {
			$qrtext = $asset['qrvalue'];
			$qrvalue = $asset['qrvalue'];

		} else {
			$qrtext = $asset['tag'];
			//$qrvalue = getConfigValue("app_url") . "?route=inventory/assets/manage&id=" . $asset['id'];
			$qrvalue = "http://192.168.31.25/showasset/index.php?id=" .$asset['id'];
            // $qrvalue = $asset['nodenumber']." | ".$asl['assetname']." | ".$asl['licensename']." | ".$asl['peoplename']." | ";
		}
        $html .= '<style>@page { margin: 3px; } p { margin: 0px; } h4 { margin: 2px; } </style><div style="text-align: left">
		<table width="100%">
			<tr>
				<td rowspan="4">
				<barcode code="'.$qrvalue.'" type="QR" class="barcode" size="0.7" error="M" disableborder="1" />
				</td>
				<td align="left" style="font-size:15px;"><b>'.$asset['nodenumber'].'</b></td>
			</tr>
			<tr><td valign="top" style="font-size:11px;"><b>'.$asset['machine_id'].'</b></td></tr>
			<tr><td valign="top" style="font-size:9px;"><b>'.getSingleValue("assetcategories","name",$asset['categoryid']).'</b></td></tr>
			<tr><td valign="top" style="font-size:8px;"><b>'.$typepurch.'</b></td></tr>
			<tr><td valign="top" style="font-size:9px;" align="center" width="35%"><b>'.$typeaset.'</b>
			'.$endwarranty.'
			<tr><td valign="top" style="font-size:8px;" colspan="2" align="center"><b>For more information, Please Access the Web Portal</b></td></tr>
			
		</table>
			</div>';

		// $html .= '<div style="text-align: left">
		// <table>
		// 	<tr>
		// 		<td rowspan="4">
		// 		'.$qrimage.'
		// 		</td>
		// 		<td align="left" style="font-size:18px;"><b>'.$asset['nodenumber'].'</b></td>
		// 	</tr>
		// 	<tr><td valign="top" style="font-size:13px;"><b>'.$asset['machine_id'].'</b></td></tr>
		// 	<tr><td valign="top" style="font-size:10px;"><b>'.getSingleValue("assetcategories","name",$asset['categoryid']).'</b></td></tr>
		// 	<tr><td valign="top" style="font-size:9px;"><b>'.$typepurch.'</b></td></tr>
			
			
		// </table>
		// 	</div>';
		// $html .= '
		// <table width="100%">
		// 	<tr>
		// 		<td align="center" style="font-size:13px;" colspan="2"><b>'.$asset['nodenumber'].'</b></td>
		// 	</tr>
		// 	<tr>
		// 		<td align="left" style="font-size:10px;"><b>'.$asset['machine_id'].'</b></td>
		// 	</tr>
		// 	<tr><td valign="top" style="font-size:10px;"><b>'.getSingleValue("assetcategories","name",$asset['categoryid']).'</b></td>
		// 		<td align="right" style="font-size:6px;"><b></b></td>
		// 	</tr>
		// 	<tr>
		// 		<td style="font-size:6px;">'.$typepurch.'</td>
		// 	</tr>
			
		// 	<tr>
		// 			<td style="font-size:6px;">'.$typeaset.'</td>
		// 		</tr>
		// </table>
		// 	';

		$mpdf = new \Mpdf\Mpdf([ 'mode' => 'utf-8', 'format' => ['30', '60'] ,
								'orientation' => 'L']);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}

	if($type == "assetlabelall") {
		if($_GET[client] > 0){$wcl = "and clientid = $_GET[client]";}
		if($_GET[loc] > 0){$wloc = "and locationid = $_GET[loc]";}

		$label_width = getConfigValue("label_width");
		$label_height = getConfigValue("label_height");
		$label_qrsize = getConfigValue("label_qrsize");

		$assets = $database->query("select a.*,a.name as assetname, DATE_SUB(a.purchase_date, INTERVAL -(warranty_months) MONTH) endwarranty from assets a where a.statusid > 0 $wcl $wloc");
		
		foreach($assets as $asset){
		if($asset['assettype'] == 1){$typeaset = "Owned"; $typepurch = "Purc Date: ".$asset['purchase_date'];
			$endwarranty = "</td><td valign='top' style='font-size:8px;' width='65%'><b>End Warranty : ".$asl['endwarranty']."</b></td></tr>";
		}
		else{$typeaset = "Rent";$typepurch = "End date: ".$asset['endcontract']; 
			$endwarranty = "";
		}
		
			if(getConfigValue("manual_qrvalue") == 'true') {
				$qrtext = $asset['qrvalue'];
				$qrvalue = $asset['qrvalue'];
			} else {
				$qrtext = $asset['tag'];
				//$qrvalue = getConfigValue("app_url") . "?route=inventory/assets/manage&id=" . $asset['id'];
				//$qrvalue = "http://192.168.31.35/eraticket_dev/index.php?ids=" .$asset['id'];
				// $qrvalue = $asset['nodenumber']." | ".$asset['assetname']." | ".$asset['licensename']." | ".$asset['peoplename']." | ";
				$qrvalue = "http://192.168.31.25/showasset/index.php?id=" .$asset['id'];
			}

			// $html .= '
			// <table width="100%">
			// 	<tr>
			// 		<td align="center" style="font-size:13px;" colspan="2"><b>'.$asset['nodenumber'].'</b></td>
			// 	</tr>
			// 	<tr>
			// 		<td align="left" style="font-size:10px;"><b>'.$asset['machine_id'].'</b></td>
			// 	</tr>
			// 	<tr><td valign="top" style="font-size:10px;"><b>'.getSingleValue("assetcategories","name",$asset['categoryid']).'</b></td>
			// 		<td align="right" style="font-size:6px;"><b></b></td>
			// 	</tr>
			// 	<tr>
			// 		<td style="font-size:6px;">'.$typepurch.'</td>
			// 	</tr>
				
			// 	<tr>
			// 		<td style="font-size:6px;">'.$typeaset.'</td>
			// 	</tr>
			// </table>';
			$html .= '<style>@page { margin: 3px; } p { margin: 0px; } h4 { margin: 2px; } </style><div style="text-align: left">
		<table width="100%">
			<tr>
				<td rowspan="4">
				<barcode code="'.$qrvalue.'" type="QR" class="barcode" size="0.7" error="M" disableborder="1" />
				</td>
				<td align="left" style="font-size:15px;"><b>'.$asset['nodenumber'].'</b></td>
			</tr>
			<tr><td valign="top" style="font-size:11px;"><b>'.$asset['machine_id'].'</b></td></tr>
			<tr><td valign="top" style="font-size:9px;"><b>'.getSingleValue("assetcategories","name",$asset['categoryid']).'</b></td></tr>
			<tr><td valign="top" style="font-size:8px;"><b>'.$typepurch.'</b></td></tr>
			<tr><td valign="top" style="font-size:9px;" align="center" width="35%"><b>'.$typeaset.'</b>
			'.$endwarranty.'
			<tr><td valign="top" style="font-size:8px;" colspan="2" align="center"><b>For more information, Please Access the Web Portal</b></td></tr>
			
		</table>
			</div>';
			
		}
		$mpdf = new \Mpdf\Mpdf([ 'mode' => 'utf-8', 'format' => ['30', '60'] ,
									'orientation' => 'L']);
			$mpdf->WriteHTML($html);
			$mpdf->Output();
		
	}

	if($type == "assetasign") {
		
		$asset = getRowById("assets",$_GET['id']);
		
		$maxreplies = $database->query("select max(id) id from tickets_replies where ticketid = $_GET[ticketid] and typereplies = 2");
		foreach($maxreplies as $mx){}

		foreach($database->query("select message from tickets_replies where id = $mx[id]") as $msg){}

		if($_GET['ticketid'] != ''){$msgnotes = $msg[message];}else{$msgnotes = $asset['tsassignnotes'];}

		if($asset['assettype'] == 1){$typeaset = "Owned"; $typepurch = "Purc Date: ".$asset['purchase_date'];}
		else{$typeaset = "Rent";$typepurch = "End Contract: ".$asset['endcontract'];}
		if($asset['assettype']==1){ 
			$tglaset = "<tr>
							<td><b>Purchase Date</b></td>
							<td>".$asset['purchase_date']."</td>
						
							<td><b>Warranty (months)</b></td>
							<td>".$asset['warranty_months']."</td>
						</tr>";
		} else {
			$tglaset = "<tr>
							<td><b>Start Contract Date</b></td>
							<td>".$asset['startcontract']."</td>
						
							<td><b>End Contract Date</b></td>
							<td>".$asset['endcontract']."</td>
						</tr>";
		}

		//$html .= '<div style="text-align: center"><barcode code="'.$qrvalue.'" type="QR" class="barcode" size="'.$label_qrsize.'" error="M" disableborder="1" /><h4>'.$qrtext.'</h4></div>';
		$html .= '
		<div style="text-align: left;">
			<div align="center"><b>Assign Form Assets</b></div>
			<hr>
			<b style="font-size:12px">Detail Asset</b>
			<br>
				<table width=100% style="font-size:10px;">
						<tr>
							<td><b>Name</b></td>
							<td colspan="3">'.$asset['name'].'</td>
						</tr>
						<tr>
							<td><b>Type</b></td>
							<td>'.$typeaset.'</td>
							<td><b>Tag</b></td>
							<td>'.$asset['tag'].'</td>
						</tr>
						<tr>
							<td><b>Category</b></td>
							<td>'.getSingleValue("assetcategories","name",$asset['categoryid']).'</td>
							<td><b>Status</b></td>
							<td>'.getSingleValue("labels","name",$asset['statusid']).'</td>
						</tr>
						<tr>
							<td><b>Department</b></td>
							<td>'.getSingleValue("clients","name",$asset['clientid']).'</td>
							<td><b>Location</b></td>
							<td>'.getSingleValue("locations","name",$asset['locationid']).'</td>
						</tr>
						
						<tr>
							<td><b>Manufacturer</b></td>
							<td>'.getSingleValue("manufactures","name",$asset['manufacturerid']).'</td>
							<td><b>Model</b></td>
							<td>'.getSingleValue("models","name",$asset['modelid']).'</td>
						</tr>
						<tr>
							<td><b>Supplier</b></td>
							<td>'.getSingleValue("suppliers","name",$asset['supplierid']).'</td>
							<td><b>Serial Number</b></td>
							<td>'.$asset['serial'].'</td>
						</tr>
						<tr>
							<td><b>Machine ID</b></td>
							<td>'.$asset['machine_id'].'</td>
							<td><b>System ID</b></td>
							<td>'.$asset['nodenumber'].'</td>
						</tr>
						<tr>
							<td><b>IP Address</b></td>
							<td>'.$asset['ipaddress'].'</td>
							<td><b>Asset User</b></td>
							<td>'.getSingleValue("people","name",$asset['userid']).'</td>
						</tr>'
						.$tglaset.
						'
				</table>
			<hr>
			<b style="font-size:12px">License</b>
			<br>
				<table width=100% style="font-size:10px;">
						<tr>
							<td><b><i>Category</i></b></td>
							<td><b><i>Name</i></b></td>
							<td><b><i>Notes</i></b></td>
						</tr>
						<tr><td colspan="3"><hr></td></tr>';
						$assignedlicenses = getTableFiltered("licenses_assets","assetid",$asset['id']);
						foreach ($assignedlicenses as $assignedlicense) {
							$license = getRowById("licenses",$assignedlicense['licenseid']);
							$html .= '<tr>
								<td>'.getSingleValue("licensecategories","name",$license['categoryid']).'</td>
								<td>'.$license['name'].'</td>
								<td>'.$asset['notes'].'</td>
							</tr>';
						} 
		$html .='</table>
			<hr>
			<b style="font-size:12px">Credential</b>
			<br>
			<table width=100% style="font-size:10px;">
						<tr>
							<td><b><i>Type</i></b></td>
							<td><b><i>Username</i></b></td>
							<td><b><i>Password</i></b></td>
						</tr>
						<tr><td colspan="3"><hr></td></tr>';
						$creds = getTableFiltered("credentials","assetid",$_GET['id']);
						foreach ($creds as $cred) {
							$html .= '<tr>
								<td>'.$cred['type'].'</td>
								<td>'.$cred['username'].'</td>
								<td>'.encrypt_decrypt('decrypt', $cred['password']).'</td>
							</tr>';
						}
		$html .='</table>
			<hr>
			<b style="font-size:12px">Note Assets</b>
				<table width=100% style="font-size:10px">
					<tr>
						<td>'.$asset['notes'].'</td>
					</tr>
				</table>
			<hr>
			<b style="font-size:12px">Note Assign For TS</b>
				<table width=100% style="font-size:10px">
					<tr>
						<td>'.$msgnotes.'</td>
					</tr>
				</table>
			<hr>
			<b style="font-size:8px">Created Date : '.$asset['dateinput'].'</b><br>
			<b style="font-size:8px">Print Date : '.date('Y-m-d H:i:s').'</b>
		</div>';
		
		$mpdf = new \Mpdf\Mpdf([ 'mode' => 'utf-8', 'format' => 'A4' ,
								'orientation' => 'P']);
		//$mpdf->debug=true;
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}

	if($type == "licenselabel") {
		$label_width = getConfigValue("label_width");
		$label_height = getConfigValue("label_height");
		$label_qrsize = getConfigValue("label_qrsize");

		$license = getRowById("licenses",$_GET['id']);


		if(getConfigValue("manual_qrvalue") == 'true') {
			$qrtext = $license['qrvalue'];
			$qrvalue = $license['qrvalue'];

		} else {
			$qrtext = $license['tag'];
			$qrvalue = getConfigValue("app_url") . "?route=inventory/licenses/manage&id=" . $asset['id'];
		}


		//$html .= '<div style="text-align: center"><barcode code="'.$qrvalue.'" type="QR" class="barcode" size="'.$label_qrsize.'" error="M" disableborder="1" /><h4>'.$qrtext.'</h4></div>';
		$html .= '<div style="text-align: left">
		<table>
			<tr>
				<td rowspan="3">
				<barcode code="'.$qrvalue.'" type="QR" class="barcode" size="0.5" error="M" disableborder="1" />
				</td>
				<td align="left" style="font-size:11px;"><b>'.$license['name'].'</b></td>
				
			</tr>
			<tr><td valign="top" style="font-size:9px;"><b>Tag : '.$qrtext.' Seats : '.$license['seats'].'</b></td></tr>
			<tr><td valign="top" style="font-size:9px;"><b>'.$license['serial'].'</b></td></tr>
			
		</table>
			</div>';

		//$mpdf = new \Mpdf\Mpdf([ 'mode' => 'utf-8', 'format' => [$label_width, $label_height] ]);
		$mpdf = new \Mpdf\Mpdf([ 'mode' => 'utf-8', 'format' => ['20', '60'] ,
								'orientation' => 'L']);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}

	if($type == "virtuallabel") {
		$label_width = getConfigValue("label_width");
		$label_height = getConfigValue("label_height");
		$label_qrsize = getConfigValue("label_qrsize");

		$virtual = getRowById("virtuals",$_GET['id']);


		if(getConfigValue("manual_qrvalue") == 'true') {
			$qrtext = $virtual['qrvalue'];
			$qrvalue = $virtual['qrvalue'];

		} else {
			$qrtext = $virtual['tag'];
			$qrvalue = getConfigValue("app_url") . "?route=inventory/virtuals/manage&id=" . $asset['id'];
		}


		$html .= '<div style="text-align: center"><barcode code="'.$qrvalue.'" type="QR" class="barcode" size="'.$label_qrsize.'" error="M" disableborder="1" /><h4>'.$qrtext.'</h4></div>';

		$mpdf = new \Mpdf\Mpdf([ 'mode' => 'utf-8', 'format' => [$label_width, $label_height] ]);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}


	if($type == "qrgenerator") {
		$label_width = getConfigValue("label_width");
		$label_height = getConfigValue("label_height");
		$label_qrsize = getConfigValue("label_qrsize");

		$qrcode = getRowById("qrcodes",$_GET['id']);
		$qrvalue = $qrcode['value'];


		$html .= '<div style="text-align: center"><barcode code="'.$qrvalue.'" type="QR" class="barcode" size="'.$label_qrsize.'" error="M" disableborder="1" /></div>';

		$mpdf = new \Mpdf\Mpdf([ 'mode' => 'utf-8', 'format' => [$label_width, $label_height] ]);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}


	if($type == "qrbatch-12") {

		$label_qrsize = getConfigValue("label_qrsize");

		$html = '';
		//$html .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" media="all" />' ;
		$html .= '<style> @page { sheet-size: A4; } </style><div style="width:100%;height:100%">';


		$batch = getRowById("qrcodes_batches",$_GET['id']);
		$ids = unserialize($batch['ids']);

		$i=1;
		foreach ($ids as $id) {
			$qrcode = getRowById("qrcodes",$id);
			$qrvalue = $qrcode['value'];
			$html .= '<div style="width:33%;height:25%;float:left;text-align:center"><barcode code="'.$qrvalue.'" type="QR" class="barcode" size="'.$label_qrsize.'" error="M" disableborder="1" /></div>';


			$i++;

			if($i == 13) {
				$html .= '<pagebreak sheet-size="A4"/>';
			}
		}

		$html .= '</div>';

		$mpdf = new \Mpdf\Mpdf([ 'mode' => 'utf-8' ]);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}

	if($type == "qrbatch-16") {

		$label_qrsize = getConfigValue("label_qrsize");

		$html = '';
		//$html .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" media="all" />' ;
		$html .= '<style> @page { sheet-size: A4; } </style><div style="width:100%;height:100%">';


		$batch = getRowById("qrcodes_batches",$_GET['id']);
		$ids = unserialize($batch['ids']);

		$i=1;
		foreach ($ids as $id) {
			$qrcode = getRowById("qrcodes",$id);
			$qrvalue = $qrcode['value'];
			$html .= '<div style="width:25%;height:25%;float:left;text-align:center"><barcode code="'.$qrvalue.'" type="QR" class="barcode" size="'.$label_qrsize.'" error="M" disableborder="1" /></div>';


			$i++;

			if($i == 17) {
				$html .= '<pagebreak sheet-size="A4"/>';
			}
		}

		$html .= '</div>';

		$mpdf = new \Mpdf\Mpdf([ 'mode' => 'utf-8' ]);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}





}

// SEARCH
if ($route == "search") {

	$assets = $database->select("assets", "*", [ "OR" => [
		"purchase_date[~]" => $_GET['q'],
		"tag[~]" => $_GET['q'],
		"name[~]" => $_GET['q'],
		"serial[~]" => $_GET['q'],
		"notes[~]" => $_GET['q'],
		"qrvalue[~]" => $_GET['q']
	]]);

	$licenses = $database->select("licenses", "*", [ "OR" => [
		"tag[~]" => $_GET['q'],
		"name[~]" => $_GET['q'],
		"serial[~]" => $_GET['q'],
		"notes[~]" => $_GET['q'],
		"qrvalue[~]" => $_GET['q']
	]]);

	$clients = $database->select("clients", "*", [ "OR" => [
		"name[~]" => $_GET['q']
	]]);

	$tickets = $database->select("tickets", "*", [ "OR" => [
		"ticket[~]" => $_GET['q'],
		"subject[~]" => $_GET['q']
	]]);

	$issues = $database->select("issues", "*", [ "OR" => [
		"name[~]" => $_GET['q'],
		"description[~]" => $_GET['q']
	]]);

	$projects = $database->select("projects", "*", [ "OR" => [
		"tag[~]" => $_GET['q'],
		"name[~]" => $_GET['q'],
		"notes[~]" => $_GET['q'],
		"description[~]" => $_GET['q']
	]]);




	if($isAdmin) {
		$articles = $database->select("kb_articles", "*", [ "OR" => [
			"name[~]" => $_GET['q']
		]]);
	}
	else {
		$articles = $database->select("kb_articles", "*", [ "OR" => [
			"name[~]" => $_GET['q']
		]]);

		foreach($articles as $key => $article) {
			if($article['clients'] == "") unset($articles[$key]);
			else {
				$clients = unserialize($article['clients']);
				if(in_array(0,$clients)) continue;
				else if(!in_array($liu['clientid'],$clients)) unset($articles[$key]);
			}
		}

	}



}


// GENERAL
if($isAdmin) {
	if($liu[roleid] == 4 || $liu[roleid] == 5){
		foreach($database->query("select count(a.id) as jumlah from tickets a where a.status in ('Open') and a.typeticket not in (3,4)") as $arTicketsCount){}
		foreach($database->query("select count(a.id) as jumlah from tickets a where a.typeticket not in (3,4) and a.status not in ('Closed','Reject','Finished')") as $activeTicketsCount){}
		foreach($database->query("select count(a.id) as jumlah from tickets a where a.typeticket not in (3,4)") as $allTicketsCount){}
		foreach($database->query("select count(id) jmlid from tickets where mentionid like '%".$liu[id]."%' and a.status != 'Closed' and a.status != 'Finished' and a.status != 'Reject'") as $convTicket){}
		
	} else if($liu[roleid] == 3 || $liu[roleid] == 6){
		foreach($database->query("select count(a.id) as jumlah from tickets a where a.status in ('Open') and a.typeticket in (1,2,3)") as $arTicketsCount){}
		foreach($database->query("select count(a.id) as jumlah from tickets a where a.typeticket in (1,2,3) and a.status not in ('Closed','Reject','Finished')") as $activeTicketsCount){}
		foreach($database->query("select count(a.id) as jumlah from tickets a where a.typeticket in (1,2,3)") as $allTicketsCount){}
		foreach($database->query("select count(id) jmlid from tickets where mentionid like '%".$liu[id]."%' and a.status != 'Closed' and a.status != 'Finished' and a.status != 'Reject'") as $convTicket){}
		
	} else if($liu[roleid] == 7){
		foreach($database->query("select count(a.id) as jumlah from tickets a where a.status in ('Open') and a.typeticket in (3,4)") as $arTicketsCount){}
		foreach($database->query("select count(a.id) as jumlah from tickets a left join people d on a.adminid=d.id where d.roleid = '7' and a.status not in ('Closed','Reject','Finished')") as $activeTicketsCount){}
		foreach($database->query("select count(a.id) as jumlah from tickets a left join people d on a.adminid=d.id where d.roleid = '7'") as $allTicketsCount){}
		foreach($database->query("select count(id) jmlid from tickets where mentionid like '%".$liu[id]."%' and a.status != 'Closed' and a.status != 'Finished' and a.status != 'Reject'") as $convTicket){}
		
	} else if($liu[roleid] == 1){
		foreach($database->query("select count(a.id) as jumlah from tickets a where a.status in ('Open')") as $arTicketsCount){}
		foreach($database->query("select count(a.id) as jumlah from tickets a where  a.status not in ('Closed','Reject','Finished')") as $activeTicketsCount){}
		foreach($database->query("select count(a.id) as jumlah from tickets a") as $allTicketsCount){}
		foreach($database->query("select count(id) jmlid from tickets where mentionid like '%".$liu[id]."%' and a.status != 'Closed' and a.status != 'Finished' and a.status != 'Reject'") as $convTicket){}
		
	}
	

	$overdueIssuesCount = $database->count("issues", [ "AND" => ["status[!]" => "Done", "duedate[<]" => date("Y-m-d") , "duedate[!]" => ""] ]);
	$activeIssuesCount = $database->count("issues", [ "status[!]" => "Done" ] );
	$allIssuesCount = $database->count("issues");
}
else {
	$arTicketsCount = $database->count("tickets", [ "AND" => ["status" => ["Open"], "clientid" => $liu['clientid']]] );
	$activeTicketsCount = $database->count("tickets", [ "AND" => ["status[!]" => "Closed", "clientid" => $liu['clientid']]] );
	$allTicketsCount = countTableFiltered("tickets","clientid",$liu['clientid']);

	$overdueIssuesCount = $database->count("issues", [ "AND" => ["status[!]" => "Done", "duedate[<]" => date("Y-m-d") , "duedate[!]" => "", "clientid" => $liu['clientid']] ]);
	$activeIssuesCount = $database->count("issues", [ "AND" => ["status[!]" => "Done", "clientid" => $liu['clientid']] ] );
	$allIssuesCount = countTableFiltered("issues","clientid",$liu['clientid']);
}

// DASHBOARD
if ($route == "dashboard") {
	global $database;

	if($isAdmin) {
		// $sumAssets = countTable("assets");
		// $sumLicenses = countTable("licenses");
		$sumAssets =  countTableFiltered("assets","statusid[!]","0","assettype","1");
		$sumAssetsRent =  countTableFiltered("assets","statusid[!]","0","assettype","2");
		$sumLicenses =  countTableFiltered("licenses","statusid[!]","0");
		$sumVirtuals =  countTableFiltered("virtuals","statusid[!]","0");
		$sumProjects = countTable("projects");
		$sumClients = countTable("clients");
		$sumUsers = countTableFiltered("people","type","user");
		$categories = getTable("assetcategories");
		$myIssues = $database->select("issues", "*", [ "AND" => ["status[!]" => "Done", "adminid" => $liu['id']] ]);
		$activeIssues = $database->select("issues", "*", [ "status[!]" => "Done" ]);

		if($liu[roleid] == 4 || $liu[roleid] == 5) {
			$openTickets = $database->select("tickets", "*", ["AND" => ["status[!]" => "Closed","typeticket[!]" => [3,4] 
				], "ORDER" => ["id" => "DESC"]]);
		} else if($liu[roleid] == 7) {
			$openTickets = $database->select("tickets", "*", ["AND" => ["status[!]" =>"Closed","typeticket" => [3,4] 
				], "ORDER" => ["id" => "DESC"]]);
		} else if($liu[roleid] == 3 || $liu[roleid] == 6) {
			$openTickets = $database->select("tickets", "*", ["AND" => ["status[!]" => "Closed","typeticket" => [3] 
				], "ORDER" => ["id" => "DESC"]]);
		} 

		//$openTickets = $database->select("tickets", "*", [ "status[!]" => "Closed", "ORDER" => ["id" => "DESC"] ]);

		$recentAssets = $database->select("assets", "*", [ "ORDER" => ["id" => "DESC"], "LIMIT" => 5]);
		$recentLicenses = $database->select("licenses", "*", [ "ORDER" => ["id" => "DESC"], "LIMIT" => 5]);
		$selaslo = $database->query("select b.name,count(a.id) jml from assets a join locations b on a.locationid=b.id where statusid > 0 GROUP BY b.name");
		$sellilo = $database->query("select locationname, count(nameaset) jml from(select b.name as nameaset, c.name as namelicense,c.seats,d.name as locationname from licenses_assets a join assets b on a.assetid=b.id join (select a.id,a.name,a.tag,a.categoryid,sum(b.seats) as seats from licenses a left join licenses_dtl b on a.id=b.licensesid and b.statusid > 0 where a.statusid > 0 GROUP BY a.id,a.name,a.tag,a.categoryid ) c on a.licenseid=c.id left join locations d on b.locationid=d.id) a GROUP BY locationname");
	}
	else {
		$sumAssets = countTableFiltered("assets","clientid",$liu['clientid']);
		$sumLicenses = countTableFiltered("licenses","clientid",$liu['clientid']);
		$sumProjects = countTableFiltered("projects","clientid",$liu['clientid']);
		$sumClients = countTable("clients");
		$sumUsers = countTableFiltered("people","type","user","clientid",$liu['clientid']);

		$categories = getTable("assetcategories");

		$activeIssues = $database->select("issues", "*", [ "AND" => ["status[!]" => "Done", "clientid" => $liu['clientid']] ]);
		$openTickets = $database->select("tickets", "*", [ "AND" => ["clientid" => $liu['clientid'], "status[!]" => "Closed"], "ORDER" => ["id" => "DESC"]] );

		$recentAssets = $database->select("assets", "*", ["clientid" => $liu['clientid'], "ORDER" => ["id" => "DESC"], "LIMIT" => 5]);
		$recentLicenses = $database->select("licenses", "*", ["clientid" => $liu['clientid'], "ORDER" => ["id" => "DESC"], "LIMIT" => 5]);
		$selaslo = $database->query("select b.name,count(a.id) jml from assets a join locations b on a.locationid=b.id where statusid > 0 GROUP BY b.name");
		$sellilo = $database->query("select locationname, count(nameaset) jml from(select b.name as nameaset, c.name as namelicense,c.seats,d.name as locationname from licenses_assets a join assets b on a.assetid=b.id join (select a.id,a.name,a.tag,a.categoryid,sum(b.seats) as seats from licenses a left join licenses_dtl b on a.id=b.licensesid and b.statusid > 0 where a.statusid > 0 GROUP BY a.id,a.name,a.tag,a.categoryid ) c on a.licenseid=c.id left join locations d on b.locationid=d.id) a GROUP BY locationname");
	}
}

// ASSETS
if ($route == "inventory/assets") {
	isAuthorized("viewAssets");
	if($isAdmin) {
		$assets = $database->query("select * from assets where statusid > 0");
		//$assets = getTableFiltered("assets","statusid[!]","0");
		//$assets = getTable("assets");
	}
	else {
		$assets = $database->query("select * from assets where statusid > 0");
	//	$assets = getTableFiltered("assets","clientid",$liu['clientid'],"statusid[!]","0");
	}
	$pageTitle = __("Assets");
}

if ($route == "inventory/assets/create") {
	isAuthorized("addAsset");

	if($isAdmin) {
		$locations = getTable("locations");
		$users = getTableFiltered("people","type","user","statusid","Active");
	}
	else {
		$locations = getTableFiltered("locations","clientid",$liu['clientid']);
		$users = getTableFiltered("people","type","user","clientid",$liu['clientid'],"statusid","Active");
	}

	$clients = getTable("clients");
	$admins = getTableFiltered("people","type","admin");
	$models = getTable("models");
	$manufacturers = getTable("manufacturers");
	$categories = getTable("assetcategories");
	$labels = getTable("labels");
	$suppliers = getTable("suppliers");
	$customfields = getTable("assets_customfields");

	$pageTitle = __("Create Asset");
	// //$eishws = getTableFiltered("assets_eis","status_sync","0");
	// $eishws = $database->select("assets_eis", "*", [ "AND" => ["status_sync" => "0", "subclassif_id" => "34"] ]);
	$eishws = $database->query("select * from assets_eis where status_sync = 0 and subclassif_id = '34' and DATE(grin_date) >= '2022-07-01'");
	//$eislcs = $database->select("assets_eis", "*", [ "AND" => ["status_sync" => "0", "subclassif_id" => "36"] ]);
	$eislcs = $database->query("select * from assets_eis where status_sync = 0 and subclassif_id = '36' and DATE(grin_date) >= '2022-07-01'");
	$logsyncdatas = $database->select("log_sync_data", "*");
}

if ($route == "inventory/assets/manage") {
	isAuthorized("manageAsset");
	$asset = getRowById("assets",$_GET['id']);

	isOwner($asset['clientid']);

	//$customdata = unserialize($asset['customfields']);


	if($isAdmin) {
		$locations = getTable("locations");
		//$users = getTableFiltered("people","type","user");
		$users = getTable("people");
	}
	else {
		$locations = getTableFiltered("locations","clientid",$liu['clientid']);
		//$users = getTableFiltered("people","type","user","clientid",$liu['clientid']);
		$users = getTableFiltered("people","clientid",$liu['clientid']);
	}

	$issues = getTableFiltered("issues","assetid",$_GET['id']);
	//$tickets = getTableFiltered("tickets","assetid",$_GET['id'],"","","*","id","DESC");
	$tickets = $database->query("select * from tickets where assetid = '$_GET[id]' and typeasset = 1 order by id DESC");
	$credentials = getTableFiltered("credentials","assetid",$_GET['id']);
	$userhistorys = getTableFiltered("assetsuserhistory","idasset",$_GET['id'],"","","*","idassetsuser","ASC");
	$lochistorys = getTableFiltered("assetlochistory","idasset",$_GET['id'],"","","*","idassetsloc","ASC");
	$sthistorys = getTableFiltered("assetstatushistory","idasset",$_GET['id'],"","","*","idassetsstatus","ASC");
	$assignedlicenses = $database->query("select * from licenses_assets where assetid = '$_GET[id]' and typeasset = 1");
	$clients = getTable("clients");
	$admins = getTableFiltered("people","type","admin");
	$models = getTable("models");
	$manufacturers = getTable("manufacturers");
	$categories = getTable("assetcategories");
	$labels = getTable("labels");
	$licenses = getTable("licenses");
	$suppliers = getTable("suppliers");
	$files = getTableFiltered("files","assetid",$_GET['id']);
	$timelog = getTableFiltered("timelog","assetid",$_GET['id']);

	$customfields = getTable("assets_customfields");

	$pageTitle = $asset['tag'];
	$nameaset = $asset[machine_id];
	$nameasetocs = base64_encode($nameaset);
	//echo "http://localhost/restfulapiocs/public/ocshw/".$nameasetocs."-".$nameaset;
	
    
    //ambil data API 31.24
       // $no=1;
	
	}

// LICENSES
if ($route == "inventory/licenses") {
	isAuthorized("viewLicenses");
	if($isAdmin) {
		//$licenses = getTableFiltered("licenses","statusid[!]","0");
		$licenses = $database->query("select a.id,a.name,a.tag,a.categoryid,sum(b.seats) as seats from  licenses a left join licenses_dtl b on a.id=b.licensesid and b.statusid > 0 where a.statusid > 0 GROUP BY a.id,a.name,a.tag,a.categoryid");
	}
	else {
		//$licenses = getTableFiltered("licenses","clientid",$liu['clientid']);
		$licenses = $database->query("select a.id,a.name,a.tag,a.categoryid,sum(b.seats) as seats from  licenses a join licenses_dtl b on a.id=b.licensesid and b.statusid > 0 where a.statusid > 0 GROUP BY a.id,a.name,a.tag,a.categoryid");
	}
	$pageTitle = __("Licenses");
}

if ($route == "inventory/licenses/create") {
	isAuthorized("addLicense");
	$suppliers = getTable("suppliers");
	//$categories = getTable("licensecategories");
	$labels = getTable("labels");
	$clients = getTable("clients");
	$customfields = getTable("licenses_customfields");

	$eishws = $database->select("assets_eis", "*", [ "AND" => ["status_sync" => "0", "subclassif_id" => "34"] ]);
	$eislcs = $database->select("assets_eis", "*", [ "AND" => ["status_sync" => "0", "subclassif_id" => "36"] ]);
	$logsyncdatas = $database->select("log_sync_data", "*");
}

if ($route == "inventory/licenses/manage") {
	isAuthorized("manageLicense");
	//$license = getRowById("licenses",$_GET['id']);
	$licenses = $database->query("select a.id,a.name,a.tag,a.categoryid,sum(b.seats) as seats,a.statusid,a.serial,a.supplierid from  licenses a left join licenses_dtl b on a.id=b.licensesid and b.statusid > 0 where a.statusid > 0 and a.id = '$_GET[id]' 
		GROUP BY a.id,a.name,a.tag,a.categoryid");
	foreach($licenses as $license){}

	isOwner($license['clientid']);

	$customdata = unserialize($license['customfields']);

	$categories = getTable("licensecategories");
	$labels = getTable("labels");
	$clients = getTable("clients");
	$assignedassets = getTableFiltered("licenses_assets","licenseid",$_GET['id']);
	$insassignedassets = $database->query("select * from licenses_assets where licenseid = '$_GET[id]' and inslicenseid <> '$_GET[id]' union select * from licenses_assets where licenseid != '$_GET[id]' and inslicenseid = '$_GET[id]'");
	$assignedassetshis = getTableFiltered("licenses_assetshis","licenseid",$_GET['id']);
	$assets = getTable("assets");
	$suppliers = getTable("suppliers");
	$files = getTableFiltered("files","licenseid",$_GET['id']);

	$customfields = getTable("licenses_customfields");

	$pageTitle = $license['tag'];
	}

// VIRTUAL
if ($route == "inventory/virtuals") {
	isAuthorized("viewVirtual");
	$labels = getTable("labels");
	$virtuals = $database->query("select a.*,b.name as assetname from virtuals a left join assets b on a.assetid=b.id 
		where a.statusid <> 0");
	
	
	$pageTitle = __("Virtuals");
}

if ($route == "inventory/virtuals/create") {
	isAuthorized("addVirtual");
	$asrefs = $database->query("select * from assets where statusid > 0");
	$labels = getTable("labels");
}

if ($route == "inventory/virtuals/manage") {
	isAuthorized("manageVirtual");
	$virtual = getRowById("virtuals",$_GET['id']);
	$asrefs = $database->query("select * from assets where statusid > 0");
	$labels = getTable("labels");
	isOwner($license['clientid']);

	$assignedassets = getTableFiltered("virtuals_assets","licenseid",$_GET['id']);
	$assets = $database->query("select * from assets where statusid > 0");
	
	$issues = getTableFiltered("issues","assetid",$_GET['id']);
	//$tickets = getTableFiltered("tickets","assetid",$_GET['id'],"","","*","id","DESC");
	$tickets = $database->query("select * from tickets where assetid = '$_GET[id]' and typeasset = 2 order by id DESC");
	$credentials = getTableFiltered("credentials","assetid",$_GET['id']);
	$userhistorys = getTableFiltered("assetsuserhistory","idasset",$_GET['id'],"","","*","idassetsuser","ASC");
	$lochistorys = getTableFiltered("assetlochistory","idasset",$_GET['id'],"","","*","idassetsloc","ASC");
	$sthistorys = getTableFiltered("assetstatushistory","idasset",$_GET['id'],"","","*","idassetsstatus","ASC");

	$assignedlicenses = $database->query("select * from licenses_assets where assetid = '$_GET[id]' and typeasset = 2");
	
	$clients = getTable("clients");
	$admins = getTableFiltered("people","type","admin");
	$models = getTable("models");
	$manufacturers = getTable("manufacturers");
	$categories = getTable("assetcategories");
	$labels = getTable("labels");
	$licenses = getTable("licenses");
	$suppliers = getTable("suppliers");
	$files = getTableFiltered("files","assetid",$_GET['id']);
	$timelog = getTableFiltered("timelog","assetid",$_GET['id']);

	$pageTitle = $license['tag'];
	}

// SPAREPARTS
if ($route == "inventory/spareparts") {
	isAuthorized("viewSpareparts");
	$labels = getTable("labels");
	$sparepartss = $database->query("select * from spareparts where statusid <> 0");
	
	
	$pageTitle = __("Spareparts");
}

if ($route == "inventory/spareparts/create") {
	isAuthorized("addSpareparts");
	$asrefs = $database->query("select * from assets where statusid > 0");
	$labels = getTable("labels");
}

if ($route == "inventory/spareparts/manage") {
	isAuthorized("manageSpareparts");
	$sparepart = getRowById("spareparts",$_GET['id']);
	$asrefs = $database->query("select * from assets where statusid > 0");
	$labels = getTable("labels");
	isOwner($license['clientid']);

	$assignedassets = getTableFiltered("virtuals_assets","licenseid",$_GET['id']);
	$assets = $database->query("select * from assets where statusid > 0");
	
	$issues = getTableFiltered("issues","assetid",$_GET['id']);
	//$tickets = getTableFiltered("tickets","assetid",$_GET['id'],"","","*","id","DESC");
	$tickets = $database->query("select * from tickets where assetid = '$_GET[id]' and typeasset = 2 order by id DESC");
	$credentials = getTableFiltered("credentials","assetid",$_GET['id']);
	$userhistorys = getTableFiltered("assetsuserhistory","idasset",$_GET['id'],"","","*","idassetsuser","ASC");
	$lochistorys = getTableFiltered("assetlochistory","idasset",$_GET['id'],"","","*","idassetsloc","ASC");
	$sthistorys = getTableFiltered("assetstatushistory","idasset",$_GET['id'],"","","*","idassetsstatus","ASC");

	$assignedlicenses = $database->query("select * from licenses_assets where assetid = '$_GET[id]' and typeasset = 2");
	
	$clients = getTable("clients");
	$admins = getTableFiltered("people","type","admin");
	$models = getTable("models");
	$manufacturers = getTable("manufacturers");
	$categories = getTable("assetcategories");
	$labels = getTable("labels");
	$licenses = getTable("licenses");
	$suppliers = getTable("suppliers");
	$files = getTableFiltered("files","assetid",$_GET['id']);
	$timelog = getTableFiltered("timelog","assetid",$_GET['id']);

	$pageTitle = $license['tag'];
	}

// CREDENTIALS
if ($route == "inventory/credentials") { isAuthorized("viewCredentials"); $credentials = getTable("credentials"); $pageTitle = __("Credentials Manager"); }


// PROJECTS
if ($route == "projects") {
	isAuthorized("viewProjects");
	if($isAdmin) {
		if($liu[roleid] == 7){
			$projects = $database->query("select a.*,b.adminid from projects a join projects_admins b 
										 on a.id=b.projectid where adminid= $liu[id]");	
		} else if($liu[roleid] == 3 || $liu[roleid] == 6 ){
			$projects = $database->query("select a.*,b.adminid from projects a left join projects_admins b 
										 on a.id=b.projectid where section= 2");
		} else if($liu[roleid] == 4 || $liu[roleid] == 5){
			$projects = $database->query("select a.*,b.adminid from projects a left join projects_admins b 
										 on a.id=b.projectid where section= 3");
		} else {
			$projects = getTable("projects");			
		}
 		
	}
	else {
		$projects = getTableFiltered("projects","clientid",$liu['clientid']);
	}
	$pageTitle = __("Projects");
}

if ($route == "projects/manage") {
	isAuthorized("manageProject");
	$project = getRowById("projects",$_GET['id']);

	isOwner($project['clientid']);

	$assignedadmins = getTableFiltered("projects_admins","projectid",$_GET['id']);
	$files = getTableFiltered("files","projectid",$_GET['id']);
	$comments = getTableFiltered("comments","projectid",$_GET['id'],"","","*","timestamp","ASC");
	$milestones = getTableFiltered("milestones","projectid",$_GET['id']);
	$timelog = getTableFiltered("timelog","projectid",$_GET['id']);

	$todo = $database->select("issues", "*", [ "AND" => ["status" => "To Do", "projectid" => $_GET['id']] ]);
	$inprogress = $database->select("issues", "*", [ "AND" => ["status" => "In Progress", "projectid" => $_GET['id']] ]);
	$inreview = $database->select("issues", "*", [ "AND" => ["status" => "In Review", "projectid" => $_GET['id']] ]);
	$done = $database->select("issues", "*", [ "AND" => ["status" => "Done", "projectid" => $_GET['id'], "milestoneid" => 0] ]);

	$allissues = getTableFiltered("issues","projectid",$_GET['id']);
	$tickets = getTableFiltered("tickets","projectid",$_GET['id']);

	$countTodo = count($todo);
	$countInprogress = count($inprogress);
	$countInreview = count($inreview);
	$countDone = count($done);
	$countAll = count($allissues);
	$pageTitle = $project['tag'];
}

// TICKETS
if ($route == "tickets/ar") {
	isAuthorized("viewTickets");
	foreach($database->query("select sla from tickets_sla") as $slt){}
	if($liu[roleid] == 4 || $liu[roleid] == 5) {
		$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.status in ('Open') and a.typeticket not in (3,4)  order by a.id DESC");
	} else if($liu[roleid] == 7) {
		$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.status in ('Open') and a.typeticket in (3,4) order by a.id DESC");
	} else if($liu[roleid] == 3 || $liu[roleid] == 6) {
		$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.status in ('Open') and a.typeticket in (1,2,3) order by a.id DESC");
	} 
	else {
		//$tickets = $database->select("tickets", "*", [ "status" => ["Open","Reopened"], "ORDER" => ["id" => "DESC"]]);
		$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.status in ('Open') order by a.id DESC");
	}
	$pageTitle = __("Tickets Awaiting Reply");
}
if ($route == "tickets/active") {
	isAuthorized("viewTickets");
	foreach($database->query("select sla from tickets_sla") as $slt){}
	if($isAdmin) {
		//$tickets = $database->select("tickets", "*", ["status[!]" => ["Closed","Reject"], "ORDER" => ["id" => "DESC"]]);
		if($liu[roleid] == 4 || $liu[roleid] == 5) {
			$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.status not in ('Closed','Reject','Finished') and a.typeticket not in (3,4) order by a.id DESC");			
		} else if($liu[roleid] == 7) {
			$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.status not in ('Closed','Reject','Finished') and d.roleid = '7' order by a.id DESC");
		} else if($liu[roleid] == 3 || $liu[roleid] == 6) {
			$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.status not in ('Closed','Reject','Finished') and a.typeticket in (1,2,3) order by a.id DESC");
		} else {
			$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.status not in ('Closed','Reject','Finished') order by a.id DESC");
			
		}
	}
	else {
		$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.status not in ('Closed','Reject','Finished') and a.clientid = $liu[clientid] order by a.id DESC"); 
	}
	$pageTitle = __("Active Tickets");
}
if ($route == "tickets/all") {
	isAuthorized("viewTickets");
	foreach($database->query("select sla from tickets_sla") as $slt){}
	if($isAdmin) {
		//$tickets = $database->select("tickets", "*", ["ORDER" => ["id" => "DESC"]]);
		if($liu[roleid] == 4 || $liu[roleid] == 5) {
			$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.typeticket not in (3,4) order by a.id DESC"); 
		} else if($liu[roleid] == 7) {
			$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where d.roleid = '7' order by a.id DESC"); 
			//$tickets = $database->select("tickets", "*", ["typeticket" => [3,4], "ORDER" => ["id" => "DESC"]]);
		} else if($liu[roleid] == 3 || $liu[roleid] == 6) {
			$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.typeticket in (1,2,3) order by a.id DESC"); 
			//$tickets = $database->select("tickets", "*", ["typeticket" => [3], "ORDER" => ["id" => "DESC"]]);
		} else {
			$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id order by a.id DESC"); 
			//$tickets = $database->select("tickets", "*", ["ORDER" => ["id" => "DESC"]]);
		}
	}
	else {
		if($liu[roleid] == 8) {$whe = "a.adminid like '%0000000485%'";}
		else{$whe = "a.clientid = $liu[clientid]";} 
		$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where 
			$whe order by a.id DESC"); 
		//$tickets = $database->select("tickets", "*", ["clientid" => $liu['clientid'], "ORDER" => ["id" => "DESC"]]);
	}
	$pageTitle = __("All Tickets");
}

if ($route == "tickets/conv") {
	isAuthorized("viewTickets");
	foreach($database->query("select sla from tickets_sla") as $slt){}
		$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.modifydate),':',1),0) AS SIGNED) as slamodify,CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where mentionid like '%".$liu[id]."%' and a.status in ('Assigned','In Progress','Hold') order by a.id DESC");
	
	$pageTitle = __("Discussion");
}

if ($route == "tickets/manage") {
	isAuthorized("manageTicket");
	foreach($database->query("select sla from tickets_sla ") as $slt){}
	//$ticket = getRowById("tickets",$_GET['id']);
	$tickets = $database->query("select a.*,c.name as picuser,f.name as picapprove,d.name as picadmin, e.name as picfinish, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.approvedate, a.timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.finishdate, a.approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(a.closedate, a.finishdate),':',1),0) AS SIGNED) as slaclosed,g.name as slaname,g.sla as slatime from tickets a left join clients b on a.clientid=b.id left join people c on a.userid=c.id left join people f on a.approvedby=f.id left join people d on a.adminid=d.id left join people e on a.finishby=e.id left join tickets_sla g on a.slaid=g.id where a.id ='$_GET[id]'");
	foreach($tickets as $ticket){}

	isOwner($ticket['clientid']);

	$replies = getTableFiltered("tickets_replies", "ticketid", $_GET['id'], "", "", "*", "id", "DESC");
	$comments = getTableFiltered("comments","ticketid",$_GET['id'],"","","*","timestamp","ASC");
	$rules = getTableFiltered("tickets_rules","ticketid",$_GET['id']);
	$pageTitle = __("#") . $ticket['ticket'];

    $licenses = $database->query("select a.id,a.name,a.tag,a.categoryid,sum(b.seats) as seats,c.typecategory 
    from  licenses a left join licenses_dtl b on a.id=b.licensesid and b.statusid > 0 
    join licensecategories c on a.categoryid=c.id
    where a.statusid > 0 GROUP BY a.id,a.name,a.tag,a.categoryid"); 
        $licenses2 = $database->query("select a.id,a.name,a.tag,a.categoryid,sum(b.seats) as seats,c.typecategory  
    from  licenses a left join licenses_dtl b on a.id=b.licensesid and b.statusid > 0 
    join licensecategories c on a.categoryid=c.id
    where a.statusid > 0 GROUP BY a.id,a.name,a.tag,a.categoryid"); 
    
    $assignedlicenses = $database->query("select a.*,b.ticket from licenses_assets a join tickets b on a.assetid=b. assetid where b.id = '$_GET[id]'");    
	$hpconfig = HTMLPurifier_Config::createDefault();
	$hpconfig->set('HTML.AllowedAttributes', 'src, height, width, alt');
	$hpconfig->set('URI.AllowedSchemes', array('http' => true, 'https' => true, 'mailto' => true, 'ftp' => true, 'nntp' => true, 'news' => true, 'callto' => true, 'data' => true));


	$purifier = new HTMLPurifier($hpconfig);

	require($scriptpath . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'washtml.php');

	$ascoks = $database->query("select b.id,b.machine_id,b.name as assetname,nodenumber,c.name as categoryname,a.id as peopleid 
		from people a join people_dtl_request d on a.id=d.peopleid join assets b on a.id=b.userid join assetcategories c on b.categoryid=c.id where d.id = $ticket[people_dtl_request_id] and b.categoryid in (1,2,7) and b.statusid > 0");
	foreach($ascoks as $ascok){}

}


if ($route == "submitticket") {
	$departments = getTable("tickets_departments");

}


// ISSUES
if ($route == "issues/active") {
	isAuthorized("viewIssues");
	if($isAdmin) {
		$issues = $database->select("issues", "*", ["status[!]" => "Done"]);
	}
	else {
		$issues = $database->select("issues", "*", [ "AND" => ["status[!]" => "Done", "clientid" => $liu['clientid']] ]);
	}
	$pageTitle = __("Active Issues");
}

if ($route == "issues/all") {
	isAuthorized("viewIssues");
	if($isAdmin) {
		$issues = getTable("issues");
	}
	else {
		$issues = getTableFiltered("issues","clientid",$liu['clientid']);
	}
	$pageTitle = __("All Issues");
}

// KNOWLEDGE BASE
if ($route == "kb") {
	isAuthorized("viewKB");

	if($isAdmin) {
		$categories = getTable("kb_categories");

		if(!isset($_GET['id'])) $id = 0; else $id = $_GET['id'];
		$articles = getTableFiltered("kb_articles","categoryid", $id);
	}

	else {
		// get categories
		$categories = getTable("kb_categories");
		foreach($categories as $key => $category) {

			if($category['clients'] == "") unset($categories[$key]);
			else {
				$clients = unserialize($category['clients']);
				if(in_array(0,$clients)) continue;
				else if(!in_array($liu['clientid'],$clients)) unset($categories[$key]);
			}

		}

		// get articles
		if(!isset($_GET['id'])) $id = 0; else $id = $_GET['id'];
		$articles = getTableFiltered("kb_articles", "categoryid", $id);
		foreach($articles as $key => $article) {

			if($article['clients'] == "") unset($articles[$key]);
			else {
				$clients = unserialize($article['clients']);
				if(in_array(0,$clients)) continue;
				else if(!in_array($liu['clientid'],$clients)) unset($articles[$key]);
			}

		}

	}

	$pageTitle = __("Knowledge Base");

}






// MONITORING
if ($route == "monitoring") {
	isAuthorized("viewMonitoring");
	$clients = getTable("clients");

	if($isAdmin) {
		$hostsUp = getTableFiltered("hosts","status","Up");
		$hostsDown = getTableFiltered("hosts","status","Down");
		$hostsWarning = getTableFiltered("hosts","status","Warning");

		$hosts = getTableFiltered("hosts","status","");
		$sumHosts = countTable("hosts");
		$sumHostsUp = countTableFiltered("hosts","status","Up");
		$sumHostsDown = countTableFiltered("hosts","status","Down");
		$sumHostsWarning = countTableFiltered("hosts","status","Warning");
	}
	else {
		$hostsUp = getTableFiltered("hosts","status","Up","clientid",$liu['clientid']);
		$hostsDown = getTableFiltered("hosts","status","Down","clientid",$liu['clientid']);
		$hostsWarning = getTableFiltered("hosts","status","Warning","clientid",$liu['clientid']);

		$hosts = getTableFiltered("hosts","status","","clientid",$liu['clientid']);
		$sumHosts = countTableFiltered("hosts","clientid",$liu['clientid']);
		$sumHostsUp = countTableFiltered("hosts","status","Up","clientid",$liu['clientid']);
		$sumHostsDown = countTableFiltered("hosts","status","Down","clientid",$liu['clientid']);
		$sumHostsWarning = countTableFiltered("hosts","status","Warning","clientid",$liu['clientid']);
	}

	if($sumHosts > 0) {
		$percUp = round($sumHostsUp / $sumHosts * 100, 0);
		$percWarning = round($sumHostsWarning / $sumHosts * 100, 0);
		$percDown = round($sumHostsDown / $sumHosts * 100, 0);
	}
	else {
		$percUp = 0;
		$percWarning = 0;
		$percDown = 0;
	}
	$pageTitle = __("Monitoring");
}
if ($route == "monitoring/manage") {
	isAuthorized("manageHost");
	$host = getRowById("hosts",$_GET['id']);

	isOwner($host['clientid']);

	$admins = getTableFiltered("people","type","admin");
	$checksUp = getTableFiltered("hosts_checks","hostid",$_GET['id'],"status","Up");
	$checksDown = getTableFiltered("hosts_checks","hostid",$_GET['id'],"status","Down");
	$checks = getTableFiltered("hosts_checks","hostid",$_GET['id'],"status","");
	$assignedpeople = getTableFiltered("hosts_people","hostid",$_GET['id']);
	$pageTitle = __("Monitoring");
}

// REPORTS
if ($route == "reports") {
	isAuthorized("viewReports");
	$clients = getTable("clients");
	$admins = getTableFiltered("people","type","admin");
	$users = getTableFiltered("people","type","user");
	$pageTitle = __("Reports");
	$assets = $database->query("select * from assets where statusid <> 0");
	$licenses = $database->query("select a.*,b.name as namecat,b.typecategory from licenses a join categoryid b on a.categoryid=b.id where statusid <> 0");
	$assetcategoriess = getTable("assetcategories");
	$licensecategoriess = getTable("licensecategories");
	$labels = getTable("labels");
}

if ($route == "reports/view") {
	if($_GET['report'] == "timesheet") {
		//$startdate = dateDb($_GET['startDate']) . " 00:00:00";
		//$enddate = dateDb($_GET['endDate']) . " 00:00:00";
		$startdate = dateDb($_GET['startDate']);
		$enddate = dateDb($_GET['endDate']);
		if($_GET['clientid'] == "0") {
			$issues = $database->query("select * from issues where DATE(dateadded) between '".$startdate."' and '".$enddate."'");
			$tickets = $database->query("select * from tickets where DATE(timestamp) between '".$startdate."' and '".$enddate."'");
			$timelog = $database->select("timelog", "*", ["date[<>]" => [dateDb($_GET['startDate']), dateDb($_GET['endDate'])]]
			);
		}
		else {
			$issues = $database->query("select * from issues where DATE(dateadded) between '".$startdate."' and '".$enddate."' and clientid ='".$_GET['clientid']."'");
			$tickets = $database->query("select * from tickets where DATE(timestamp) between '".$startdate."' and '".$enddate."' and clientid ='".$_GET['clientid']."'");
			$timelog = $database->select("timelog", "*", [ "AND" => [
				"date[<>]" => [dateDb($_GET['startDate']), dateDb($_GET['endDate'])],
				"clientid" => $_GET['clientid']
			]]);
		}
	}
	elseif($_GET['report'] == "ticketsReport") {
		foreach($database->query("select sla from tickets_sla") as $slt){}

		$startdate = dateDb($_GET['startDate']);
		$enddate = dateDb($_GET['endDate']);
		if($_GET['typeticket'] == "1"){$typeticket = " and typeticket in (1,2)";}else if($_GET['typeticket'] == "2"){$typeticket = " and typeticket in (3)";}else{$typeticket = "";}
		if($_GET['clientid'] <> "0"){$clientid = " and clientid = '".$_GET['clientid']."'";}else{$clientid = "";}
		if($_GET['assetid'] <> "0"){$assetid = " and assetid = '".$_GET['assetid']."'";}else{$assetid = "";}
		if($_GET['userid'] <> "0"){$userid = " and userid = '".$_GET['userid']."'";}else{$userid = "";}
		if($_GET['adminid'] <> "0"){$adminid = " and adminid like '%".$_GET['adminid']."%'";}else{$adminid = "";}
		if($_GET['statusid'] <> "0"){$statusid = " and status = '".$_GET['statusid']."'";}else{$statusid = "";}
		if($_GET['priorityid'] <> "0"){$priorityid = " and priority = '".$_GET['priorityid']."'";}else{$priorityid = "";}

		$tickets = $database->query("select *,CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), timestamp),':',1),0) AS SIGNED) as sla, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), approvedate),':',1),0) AS SIGNED) as slamod, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(approvedate, timestamp),':',1),0) AS SIGNED) as slaapprove, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(finishdate, approvedate),':',1),0) AS SIGNED) as slafinished, CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(closedate, finishdate),':',1),0) AS SIGNED) as slaclosed from tickets where DATE(timestamp) between '".$startdate."' and '".$enddate."' ".$typeticket.$clientid.$assetid.$userid.$adminid.$statusid.$priorityid."");
		
		//$departments = getTable("tickets_departments");
		$departments = getTable("clients");
	}

	elseif($_GET['report'] == "timesheetSummary") {
		$startdate = dateDb($_GET['startDate']);
		$enddate = dateDb($_GET['endDate']);
		if($_GET['clientid'] == "0") {
			$assets = $database->query("select * from assets where statusid <> 0");
			$licenses = $database->query("select * from licenses where statusid <> 0");
			$issues = $database->query("select * from issues where DATE(dateadded) between '".$startdate."' and '".$enddate."'");
			$tickets = $database->query("select * from tickets where DATE(timestamp) between '".$startdate."' and '".$enddate."'");
			$timelog = $database->select("timelog", "*", [
				"date[<>]" => [dateDb($_GET['startDate']), dateDb($_GET['endDate'])]
			]);
		}
		else {
			// $assets = getTableFiltered("assets","clientid",$_GET['clientid']);
			// $licenses = getTableFiltered("licenses","clientid",$_GET['clientid']);
			$assets = $database->query("select * from assets where statusid <> 0 and clientid = '".$_GET['clientid']."'");
			$licenses = $database->query("select * from licenses where statusid <> 0 and clientid = '".$_GET['clientid']."'");
			$issues = $database->query("select * from issues where DATE(dateadded) between '".$startdate."' and '".$enddate."' and clientid ='".$_GET['clientid']."'");
			$tickets = $database->query("select * from tickets where DATE(timestamp) between '".$startdate."' and '".$enddate."' and clientid ='".$_GET['clientid']."'");
			$timelog = $database->select("timelog", "*", [ "AND" => [
				"date[<>]" => [dateDb($_GET['startDate']), dateDb($_GET['endDate'])],
				"clientid" => $_GET['clientid']
			]]);
		}
	}
	elseif($_GET['report'] == "summaryasset") {
		if($_GET['clientid'] <> "0"){$clientid = " and clientid = '".$_GET['clientid']."'";}else{$clientid = "";}
		if($_GET['categoryid'] <> "0"){$categoryid = " and categoryid = '".$_GET['categoryid']."'";}else{$categoryid = "";}
		if($_GET['statusid'] <> "0"){$statusid = " and statusid = '".$_GET['statusid']."'";}else{$statusid = "";}
		if($_GET['typeasset'] <> "0"){$typeasset = " and assettype = '".$_GET['typeasset']."'";}else{$typeasset = "";}
		
		$assets = $database->query("select * from assets where statusid <> 0 ".$typeasset.$clientid.$categoryid.$statusid."");

		$seltotal = $database->query("select assettype,clientid,categoryid,statusid,count(id) total from assets where statusid <> 0 ".$typeasset.$clientid.$categoryid.$statusid." GROUP BY assettype,clientid,categoryid,statusid");
		//$licenses = $database->query("select * from licenses where statusid <> 0 and clientid = '".$_GET['clientid']."'");
		
	}

	elseif($_GET['report'] == "summarylicense") {
		if($_GET['categoryid'] <> "0"){$categoryid = " and a.categoryid = '".$_GET['categoryid']."'";}else{$categoryid = "";}
		if($_GET['statusid'] <> "0"){$statusid = " and a.statusid = '".$_GET['statusid']."'";}else{$statusid = "";}
		if($_GET['tcategoryid'] <> "0"){$tcategoryid = " and c.typecategory = '".$_GET['tcategoryid']."'";}else{$statusid = "";}
		//if($_GET['licensename'][] != ''){
			foreach($_GET['licensename'] as $cok){
			$cn[].= "'".$cok."'";
			}
			$coki = count($cn);
			if($coki > 0){
				$cx = implode(',', $cn);
				$namlic = "and a.name in (".$cx.")";
			} else {
			 	$namlic - "";
			}
		

		//var_dump($_GET['licensename']);
		
		//$assets = $database->query("select * from assets where statusid <> 0 ".$typeasset.$clientid.$categoryid.$statusid."");
		$licenses = $database->query("select a.id,a.name,a.tag,a.categoryid,sum(b.seats) as seats,c.name as namecat, c.typecategory from  licenses a join licensecategories c on a.categoryid=c.id left join licenses_dtl b on a.id=b.licensesid and b.statusid > 0 where a.statusid > 0 ".$tcategoryid.$categoryid.$statusid.$namlic." GROUP BY a.id,a.name,a.tag,a.categoryid");
		$asetlicense = $database->query("select * from (select a.name as licensename,d.`name` as assetname,d.tag,d.machine_id,b.notes,case when b.leaklicense = 0 then 'V' else '-' end licused,
case when b.licenseid = b.inslicenseid then 'V' else '-' end installed from licenses a join licensecategories c on a.categoryid=c.id join licenses_assets b on a.id=b.licenseid join assets d on b.assetid=d.id where a.statusid <> 0 and b.typeasset = 1 ".$tcategoryid.$categoryid.$statusid.$namlic." union select a.name as licensename,d.`name` as assetname,d.tag,'VM' as machine_id,b.notes,
case when b.licenseid = b.inslicenseid then 'V' else '-' end installed,case when b.leaklicense = 0 then 'V' else '-' end licused from licenses a join licensecategories c on a.categoryid=c.id join licenses_assets b on a.id=b.licenseid join virtuals d on b.assetid=d.id where a.statusid <> 0 and b.typeasset = 2 ".$tcategoryid.$categoryid.$statusid.$namlic.") b order by licensename");

	}

	elseif($_GET['report'] == "summaryvirtual") {
		//if($_GET['categoryid'] <> "0"){$categoryid = " and categoryid = '".$_GET['categoryid']."'";}else{$categoryid = "";}
		if($_GET['statusid'] <> "0"){$statusid = " and a.statusid = '".$_GET['statusid']."'";}else{$statusid = "";}
		
		//$assets = $database->query("select * from assets where statusid <> 0 ".$typeasset.$clientid.$categoryid.$statusid."");
		$virtuals = $database->query("select * from virtuals a where a.statusid <> 0 ".$statusid."");
		$sumvirtuals = $database->query("select assetid,a.statusid,b.name as assetname,count(a.id) as total from virtuals a join assets b on a.assetid=b.id where a.statusid <> 0 ".$statusid." GROUP BY assetid,a.statusid");
		
	}

	elseif($_GET['report'] == "userSummary") {
		if($_GET['userid'] == "0") {
			$assets = $database->query("select * from assets where statusid <> 0");
		}
		else {
			// $assets = getTableFiltered("assets","userid",$_GET['userid']);
			$assets = $database->query("select * from assets where statusid <> 0 and userid = '".$_GET['userid']."'");
		}
	}

	elseif($_GET['report'] == "summarysparepartstock") {
		$startdate = dateDb($_GET['startDate']);
		$enddate = dateDb($_GET['endDate']);

		$bulan = date('m');
		$bulan1 = date('m')-1;
		$tahun = date('Y');
		if($bulan == '12'){
		    $tahun1 = date('Y')-1;
		} else {
		    $tahun1 = date('Y');
		}

		if($startdate <> '') {
            $tglbet1 = "DATE(tgl_mutasi) < '$startdate'";
            $tglbet = "DATE(tgl_mutasi) between '$startdate' and '$enddate'";
            $judulsaldo = "Saldo Akhir sampai tanggal ".date('Y-m-d', strtotime("-1 day", strtotime($_GET[startDate])));
        } else {
            $tglbet1 = "MONTH(tgl_mutasi) = '$bulan1' and YEAR(tgl_mutasi) = '$tahun1'";
            //$tglbet = "MONTH(tgl_mutasi) = '$bulan' and YEAR(tgl_mutasi) = '$tahun'";
            $tglbet = "YEAR(tgl_mutasi) = '$tahun'";
            $judulsaldo = "Saldo Bulan Lalu ".$tahun1."-".$bulan1;
        }

		if($_GET['gudangid'] <> "0"){$gudangid = " and id_gudang = '".$_GET['gudangid']."'";}else{$clientid = "";}
		if($_GET['jenisbrg'] <> "0"){$jenisbrg = " and jenisbrg = '".$_GET['jenisbrg']."'";}else{$categoryid = "";}
		
		$seltotal = $database->query("select * from (select a.*,
concat(COALESCE(b.masukmutasi,0),'_',COALESCE(b.keluarmutasi,0),'_',COALESCE(b.harga,0)) as persediaan,ifnull(b.harga,0) as hpp_mutasi,zz.name as namagudang,COALESCE(sdawal,0) sdawal
from spareparts a 
left join (
    select max(tx_mutasi.id) AS id_mutasi,tx_mutasi.id_gudang AS id_gudang,tx_mutasi.sparepartid AS sparepartid from tx_mutasi where DATE(tgl_mutasi) < '".$enddate."'".$gudangid.$jenisbrg." group by tx_mutasi.id_gudang,tx_mutasi.sparepartid
) bb on a.id=bb.sparepartid 
left join tx_mutasi b on bb.id_mutasi=b.id
left join gudang zz on b.id_gudang=zz.id
left join (select sparepartid,(coalesce(sum(masukmutasi),0)-coalesce(sum(keluarmutasi),0)) sdawal from tx_mutasi a where ".$tglbet1.$gudangid.$jenisbrg." GROUP BY sparepartid) c on a.id=c.sparepartid) as asi");
		
		//$licenses = $database->query("select * from licenses where statusid <> 0 and clientid = '".$_GET['clientid']."'");
		
	}
   
    elseif($_GET['report'] == "summarymaintenance") {
		$startdate = dateDb($_GET['startDate']);
		$enddate = dateDb($_GET['endDate']);
		
		$assets = $database->query("select a.*,b.name as assetname,b.nodenumber,b.machine_id,b.tag,a.id as idmtc,c.name as namepeople,d.name as nameuser from maintenance a join assets b on a.assetid=b.id left join people c on b.userid=c.id left join people d on a.userid=d.id where tgl_mtc between '".$startdate."' and '".$enddate."'");
		
	} 
	elseif($_GET['report'] == "timesheetlog") {
		//$startdate = dateDb($_GET['startDate']) . " 00:00:00";
		//$enddate = dateDb($_GET['endDate']) . " 00:00:00";
		$startdate = dateDb($_GET['startDate']);
		$enddate = dateDb($_GET['endDate']);
		if($_GET['peopleid'] == "0") {
			$issues = $database->query("select * from issues where DATE(dateadded) between '".$startdate."' and '".$enddate."'");
			$tickets = $database->query("select * from tickets where DATE(timestamp) between '".$startdate."' and '".$enddate."'");
			$timelog = $database->select("timelog", "*", ["date[<>]" => [dateDb($_GET['startDate']), dateDb($_GET['endDate'])]]
			);
		}
		else {
			$issues = $database->query("select * from issues where DATE(dateadded) between '".$startdate."' and '".$enddate."' and adminid ='".$_GET['peopleid']."'");
			$tickets = $database->query("select * from tickets where DATE(timestamp) between '".$startdate."' and '".$enddate."' and adminid ='".$_GET['peopleid']."'");
			$timelog = $database->select("timelog", "*", [ "AND" => [
				"date[<>]" => [dateDb($_GET['startDate']), dateDb($_GET['endDate'])],
				"staffid" => $_GET['peopleid']
			]]);
		}
	}
    $pageTitle = __("View Reports");
}

if ($route == "reports/assets") {
	isAuthorized("viewAssets");
	$customfields = getTable("assets_customfields");
	if($isAdmin) {
		$assets = $database->query("select * from assets where statusid <> 0");
	}
	else {
		$assets = $database->query("select * from assets where statusid <> 0 and clientid = '".$liu['clientid']."'");
		//$assets = getTableFiltered("assets","clientid",$liu['clientid']);
	}
	$pageTitle = __("Assets (Detailed List)");
}

if ($route == "reports/licenses") {
	isAuthorized("viewLicenses");
	$customfields = getTable("licenses_customfields");
	if($isAdmin) {
		$licenses = $database->query("select * from licenses where statusid <> 0");
	}
	else {
		$licenses = $database->query("select * from licenses where statusid <> 0 and clientid = '".$liu['clientid']."'");
		//$licenses = getTableFiltered("licenses","clientid",$liu['clientid']);
	}
	$pageTitle = __("Licenses (Detailed List)");
}

if ($route == "reports/virtuals") {
	isAuthorized("viewVirtuals");
	//$customfields = getTable("licenses_customfields");
		$virtuals =$database->query("select a.*,b.name as assetname from virtuals a left join assets b on a.assetid=b.id 
		where a.statusid <> 0");
	
	$pageTitle = __("Virtuals (Detailed List)");
}


// CLIENTS
if ($route == "clients") {
	isAuthorized("viewClients");
	$clients = getTable("clients");
	$pageTitle = __("Departements");
}

if ($route == "clients/manage") {
	isAuthorized("manageClient");
	$client = getRowById("clients",$_GET['id']);

	isOwner($_GET['id']);

	// $assets = getTableFiltered("assets","clientid",$_GET['id']);
	// $licenses = getTableFiltered("licenses","clientid",$_GET['id']);
	$assets = $database->query("select * from assets where statusid <> 0 and clientid = '".$_GET['id']."'");
	$licenses = $database->query("select * from licenses where statusid <> 0 and clientid = '".$_GET['id']."'");
	$credentials = getTableFiltered("credentials","clientid",$_GET['id']);
	$issues = getTableFiltered("issues","clientid",$_GET['id']);
	$tickets = getTableFiltered("tickets","clientid",$_GET['id'],"","","*","id","DESC");
	$users = getTableFiltered("people","clientid",$_GET['id']);
	$admins = getTableFiltered("people","type","admin");
	$assignedadmins = getTableFiltered("clients_admins","clientid",$_GET['id']);
	$timelog = getTableFiltered("timelog","clientid",$_GET['id']);

	$sumAssets = countTableFiltered("assets","clientid",$_GET['id']);
	$sumLicenses = countTableFiltered("licenses","clientid",$_GET['id']);
	$sumCredentials = countTableFiltered("credentials","clientid",$_GET['id']);
	$sumProjects = countTableFiltered("projects","clientid",$_GET['id']);
	$sumUsers = countTableFiltered("people","clientid",$_GET['id']);

	$categories = getTable("assetcategories");
	$files = getTableFiltered("files","clientid",$_GET['id']);
	$projects = getTableFiltered("projects","clientid",$_GET['id']);
	$pageTitle = $client['name'];
	}


// STAFF
if ($route == "people/staff") { isAuthorized("viewStaff");  $admins = getTableFiltered("people","type","admin"); $pageTitle = __("Staff"); }
if ($route == "people/staff/edit") {
	isAuthorized("editStaff");
	$admin = getRowById("people",$_GET['id']);
	$languages = getTable("languages");
	//$roles = getTableFiltered("roles","type","admin");
	$roles = $database->query("select * from roles where type = 'admin'");
	$pageTitle = __("Edit Staff");
}


// USERS
if ($route == "people/users") {
	isAuthorized("viewUsers");
	if($isAdmin) $users = getTableFiltered("people","type","user");
	else $users = getTableFiltered("people","type","user","clientid",$liu['clientid']);
	$pageTitle = __("Users");
}

if ($route == "people/users/edit") {
	isAuthorized("editUser");
	//$user = getRowById("people",$_GET['id']);
	foreach($database->query("select a.id,type,roleid,nogm,name,email,ldap_user,`password`,theme,sidebar,layout,notes,signature,sessionid,resetkey,autorefresh,lang,ticketsnotification,avatar,a.created_date,case when COALESCE(b.clientid,'0')<>'0' and b.created_date > IFNULL(a.modify_date,'1970-01-01 01:01:01')  then b.clientid else a.clientid end clientid,case when COALESCE(b.title,'0')<>'0' and b.created_date > IFNULL(a.modify_date,'1970-01-01 01:01:01') then b.title else a.title end as title,case when COALESCE(b.mobile,'0')<>'0' and b.created_date > IFNULL(a.modify_date,'1970-01-01 01:01:01') then b.mobile else a.mobile end as mobile,a.approval_type,a.statusid,a.locationid from people a left join (select clientid,title,mobile,a.peopleid,a.created_date from people_dtl_request a join (select peopleid,max(id) as id from people_dtl_request GROUP BY peopleid) b on a.id=b.id) b on a.id=b.peopleid 
where a.id = '$_GET[id]'") as $user){};
	isOwner($user['clientid']);

	$clients = getTable("clients");
	$languages = getTable("languages");
	$roles = getTableFiltered("roles","type","user");
	$pageTitle = __("Edit User");
	$files = getTableFiltered("files","peopleid",$_GET['id']);
	foreach($database->query("select count(*) jmlminta from akunminta where statusakunminta = 1") as $jam){};

}


// PROFILE
if ($route == "profile") { $languages = getTable("languages"); $pageTitle = __("Profile"); }


// CONTACTS
if ($route == "people/contacts") { isAuthorized("viewContacts"); $contacts = getTable("contacts"); $pageTitle = __("Contacts"); }

// Akun Akses
if ($route == "people/akunakses") { isAuthorized("viewAkunakses"); 
	$masakunakses = $database->query("select * from akunminta where statusakunminta = 1"); 
	$pageTitle = __("
	Akun Akses"); 
}

// Appreq
if ($route == "people/appreq") { isAuthorized("viewAppreq"); $appreq = getTable("people_dtl_approve"); $pageTitle = __("Approve Request User"); }

// ROLES
if ($route == "people/roles") { isAuthorized("viewRoles"); 
	//$roles = getTable("roles"); 
	$roles = getTableFiltered("roles","role_status","1");
	$pageTitle = __("Roles"); }

if ($route == "people/roles/add") {
	isAuthorized("addRole");

	$roleperms = array();
	$pageTitle = __("Add Role");
}

if ($route == "people/roles/edit") {
	isAuthorized("editRole");	
	$role = getRowById("roles",$_GET['id']);
	$roleperms = unserialize($role['perms']);
	$pageTitle = __("Edit Role");
}


// LABELS
if ($route == "inventory/attributes/labels") { isAuthorized("manageData"); $labels = getTable("labels"); $pageTitle = __("Labels"); }


// ASSET CATEGORIES
if ($route == "inventory/attributes/assetcategories") { isAuthorized("manageData"); $categories = getTable("assetcategories"); $pageTitle = __("Asset Categories"); }


// LICENSE CATEGORIES
if ($route == "inventory/attributes/licensecategories") { isAuthorized("manageData"); $categories = getTable("licensecategories"); $pageTitle = __("License Categories"); }

// QR CODES
if ($route == "inventory/attributes/qrcodes") {
	isAuthorized("manageData");
	$qrcodes = getTable("qrcodes");
	$batches = getTable("qrcodes_batches");

	$pageTitle = __("QR Codes");
}

// MANUFACTURERS
if ($route == "inventory/attributes/manufacturers") { isAuthorized("manageData"); $manufacturers = getTable("manufacturers"); $pageTitle = __("Manufacturers"); }

// LOCATIONS
if ($route == "inventory/attributes/locations") {
	isAuthorized("manageData");
	if($isAdmin) { $locations = getTable("locations"); }
	else { $locations = getTableFiltered("locations","clientid",$liu['clientid']); }
	$pageTitle = __("Locations");
}

// SUPPLIERS
if ($route == "inventory/attributes/suppliers") { isAuthorized("manageData"); $suppliers = getTable("suppliers"); $pageTitle = __("Suppliers"); }

// GUDANG
if ($route == "inventory/attributes/gudang") { isAuthorized("manageData"); $gudangs = getTable("gudang"); $pageTitle = __("Gudang"); }


// MODELS
if ($route == "inventory/attributes/models") { isAuthorized("manageData"); $models = getTable("models"); $pageTitle = __("Models"); }

// TRANSACTION INCOMING
if ($route == "inventory/transaction/incoming") {
	isAuthorized("manageData");
	if($isAdmin) { $incomings = getTable("tx_barangmasuk"); }
	else { $incomings = getTable("tx_barangmasuk"); }
	$pageTitle = __("Incoming");
}

// TRANSACTION OUTGOING
if ($route == "inventory/transaction/outgoing") {
	isAuthorized("manageData");
	if($isAdmin) { $outgoings = getTable("tx_barangkeluar"); }
	else { $outgoings = getTable("tx_barangkeluar"); }
	$pageTitle = __("Outgoing");
}

// TRANSACTION OUTGOING
if ($route == "inventory/transaction/stockopname") {
	isAuthorized("manageData");
	if($isAdmin) { $stokopname = getTable("tx_stockopname"); }
	else { $stokopname = getTable("tx_stockopname"); }
	$pageTitle = __("Stock Opname");
}

// LOGS
if ($route == "system/logs") {
	isAuthorized("viewLogs");
	$systemLog = getTable("systemlog");
	$emailLog = getTable("emaillog");
	$SMSLog = getTable("smslog");
	$pageTitle = __("Logs");
}


// SYSTEM SETTINGS

# predefined replies
if ($route == "system/preplies") {
	isAuthorized("viewPReplies");
	$preplies = getTable("tickets_pr");
	$pageTitle = __("Predefined Replies");
}

# API Keys
if ($route == "system/apikeys") {
	isAuthorized("manageApiKeys");
	$apikeys = getTable("api_keys");
	$pageTitle = __("API Keys");
}

# Custom Fields
if ($route == "system/customfields") {
	isAuthorized("viewCustomFields");
	$assets_customfields = getTable("assets_customfields");
	$licenses_customfields = getTable("licenses_customfields");
	$pageTitle = __("Custom Fields");
}

# Import
if ($route == "system/import") {
	isAuthorized("manageSettings");
	$pageTitle = __("Import");
}

if ($route == "system/import/assetsSample") {
	isAuthorized("manageSettings");

	Import::assetsSample();

}

if ($route == "system/import/licensesSample") {
	isAuthorized("manageSettings");

	Import::licensesSample();

}


if ($route == "system/settings") {
	isAuthorized("manageSettings");
	$emailtemplates = getTable("emailTemplates");
	$languages = getTable("languages");
	$sdepartments = getTable("tickets_departments");
	$rules = getTableFiltered("tickets_rules","ticketid","0");
	$pageTitle = __("Settings");

	$tzlist = array (
	    '(UTC-11:00) Midway Island' => 'Pacific/Midway',
	    '(UTC-11:00) Samoa' => 'Pacific/Samoa',
	    '(UTC-10:00) Hawaii' => 'Pacific/Honolulu',
	    '(UTC-09:00) Alaska' => 'US/Alaska',
	    '(UTC-08:00) Pacific Time (US &amp; Canada)' => 'America/Los_Angeles',
	    '(UTC-08:00) Tijuana' => 'America/Tijuana',
	    '(UTC-07:00) Arizona' => 'US/Arizona',
	    '(UTC-07:00) Chihuahua' => 'America/Chihuahua',
	    '(UTC-07:00) La Paz' => 'America/Chihuahua',
	    '(UTC-07:00) Mazatlan' => 'America/Mazatlan',
	    '(UTC-07:00) Mountain Time (US &amp; Canada)' => 'US/Mountain',
	    '(UTC-06:00) Central America' => 'America/Managua',
	    '(UTC-06:00) Central Time (US &amp; Canada)' => 'US/Central',
	    '(UTC-06:00) Guadalajara' => 'America/Mexico_City',
	    '(UTC-06:00) Mexico City' => 'America/Mexico_City',
	    '(UTC-06:00) Monterrey' => 'America/Monterrey',
	    '(UTC-06:00) Saskatchewan' => 'Canada/Saskatchewan',
	    '(UTC-05:00) Bogota' => 'America/Bogota',
	    '(UTC-05:00) Eastern Time (US &amp; Canada)' => 'US/Eastern',
	    '(UTC-05:00) Indiana (East)' => 'US/East-Indiana',
	    '(UTC-05:00) Lima' => 'America/Lima',
	    '(UTC-05:00) Quito' => 'America/Bogota',
	    '(UTC-04:00) Atlantic Time (Canada)' => 'Canada/Atlantic',
	    '(UTC-04:30) Caracas' => 'America/Caracas',
	    '(UTC-04:00) La Paz' => 'America/La_Paz',
	    '(UTC-04:00) Santiago' => 'America/Santiago',
	    '(UTC-03:30) Newfoundland' => 'Canada/Newfoundland',
	    '(UTC-03:00) Brasilia' => 'America/Sao_Paulo',
	    '(UTC-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
	    '(UTC-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
	    '(UTC-03:00) Greenland' => 'America/Godthab',
	    '(UTC-02:00) Mid-Atlantic' => 'America/Noronha',
	    '(UTC-01:00) Azores' => 'Atlantic/Azores',
	    '(UTC-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
	    '(UTC+00:00) Casablanca' => 'Africa/Casablanca',
	    '(UTC+00:00) Edinburgh' => 'Europe/London',
	    '(UTC+00:00) Greenwich Mean Time : Dublin' => 'Etc/Greenwich',
	    '(UTC+00:00) Lisbon' => 'Europe/Lisbon',
	    '(UTC+00:00) London' => 'Europe/London',
	    '(UTC+00:00) Monrovia' => 'Africa/Monrovia',
	    '(UTC+00:00) UTC' => 'UTC',
	    '(UTC+01:00) Amsterdam' => 'Europe/Amsterdam',
	    '(UTC+01:00) Belgrade' => 'Europe/Belgrade',
	    '(UTC+01:00) Berlin' => 'Europe/Berlin',
	    '(UTC+01:00) Bern' => 'Europe/Berlin',
	    '(UTC+01:00) Bratislava' => 'Europe/Bratislava',
	    '(UTC+01:00) Brussels' => 'Europe/Brussels',
	    '(UTC+01:00) Budapest' => 'Europe/Budapest',
	    '(UTC+01:00) Copenhagen' => 'Europe/Copenhagen',
	    '(UTC+01:00) Ljubljana' => 'Europe/Ljubljana',
	    '(UTC+01:00) Madrid' => 'Europe/Madrid',
	    '(UTC+01:00) Paris' => 'Europe/Paris',
	    '(UTC+01:00) Prague' => 'Europe/Prague',
	    '(UTC+01:00) Rome' => 'Europe/Rome',
	    '(UTC+01:00) Sarajevo' => 'Europe/Sarajevo',
	    '(UTC+01:00) Skopje' => 'Europe/Skopje',
	    '(UTC+01:00) Stockholm' => 'Europe/Stockholm',
	    '(UTC+01:00) Vienna' => 'Europe/Vienna',
	    '(UTC+01:00) Warsaw' => 'Europe/Warsaw',
	    '(UTC+01:00) West Central Africa' => 'Africa/Lagos',
	    '(UTC+01:00) Zagreb' => 'Europe/Zagreb',
	    '(UTC+02:00) Athens' => 'Europe/Athens',
	    '(UTC+02:00) Bucharest' => 'Europe/Bucharest',
	    '(UTC+02:00) Cairo' => 'Africa/Cairo',
	    '(UTC+02:00) Harare' => 'Africa/Harare',
	    '(UTC+02:00) Helsinki' => 'Europe/Helsinki',
	    '(UTC+02:00) Istanbul' => 'Europe/Istanbul',
	    '(UTC+02:00) Jerusalem' => 'Asia/Jerusalem',
	    '(UTC+02:00) Kyiv' => 'Europe/Helsinki',
	    '(UTC+02:00) Pretoria' => 'Africa/Johannesburg',
	    '(UTC+02:00) Riga' => 'Europe/Riga',
	    '(UTC+02:00) Sofia' => 'Europe/Sofia',
	    '(UTC+02:00) Tallinn' => 'Europe/Tallinn',
	    '(UTC+02:00) Vilnius' => 'Europe/Vilnius',
	    '(UTC+03:00) Baghdad' => 'Asia/Baghdad',
	    '(UTC+03:00) Kuwait' => 'Asia/Kuwait',
	    '(UTC+03:00) Minsk' => 'Europe/Minsk',
	    '(UTC+03:00) Nairobi' => 'Africa/Nairobi',
	    '(UTC+03:00) Riyadh' => 'Asia/Riyadh',
	    '(UTC+03:00) Volgograd' => 'Europe/Volgograd',
	    '(UTC+03:30) Tehran' => 'Asia/Tehran',
	    '(UTC+04:00) Abu Dhabi' => 'Asia/Muscat',
	    '(UTC+04:00) Baku' => 'Asia/Baku',
	    '(UTC+04:00) Moscow' => 'Europe/Moscow',
	    '(UTC+04:00) Muscat' => 'Asia/Muscat',
	    '(UTC+04:00) St. Petersburg' => 'Europe/Moscow',
	    '(UTC+04:00) Tbilisi' => 'Asia/Tbilisi',
	    '(UTC+04:00) Yerevan' => 'Asia/Yerevan',
	    '(UTC+04:30) Kabul' => 'Asia/Kabul',
	    '(UTC+05:00) Islamabad' => 'Asia/Karachi',
	    '(UTC+05:00) Karachi' => 'Asia/Karachi',
	    '(UTC+05:00) Tashkent' => 'Asia/Tashkent',
	    '(UTC+05:30) Chennai' => 'Asia/Calcutta',
	    '(UTC+05:30) Kolkata' => 'Asia/Kolkata',
	    '(UTC+05:30) Mumbai' => 'Asia/Calcutta',
	    '(UTC+05:30) New Delhi' => 'Asia/Calcutta',
	    '(UTC+05:30) Sri Jayawardenepura' => 'Asia/Calcutta',
	    '(UTC+05:45) Kathmandu' => 'Asia/Katmandu',
	    '(UTC+06:00) Almaty' => 'Asia/Almaty',
	    '(UTC+06:00) Astana' => 'Asia/Dhaka',
	    '(UTC+06:00) Dhaka' => 'Asia/Dhaka',
	    '(UTC+06:00) Ekaterinburg' => 'Asia/Yekaterinburg',
	    '(UTC+06:30) Rangoon' => 'Asia/Rangoon',
	    '(UTC+07:00) Bangkok' => 'Asia/Bangkok',
	    '(UTC+07:00) Hanoi' => 'Asia/Bangkok',
	    '(UTC+07:00) Jakarta' => 'Asia/Jakarta',
	    '(UTC+07:00) Novosibirsk' => 'Asia/Novosibirsk',
	    '(UTC+08:00) Beijing' => 'Asia/Hong_Kong',
	    '(UTC+08:00) Chongqing' => 'Asia/Chongqing',
	    '(UTC+08:00) Hong Kong' => 'Asia/Hong_Kong',
	    '(UTC+08:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
	    '(UTC+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
	    '(UTC+08:00) Perth' => 'Australia/Perth',
	    '(UTC+08:00) Singapore' => 'Asia/Singapore',
	    '(UTC+08:00) Taipei' => 'Asia/Taipei',
	    '(UTC+08:00) Ulaan Bataar' => 'Asia/Ulan_Bator',
	    '(UTC+08:00) Urumqi' => 'Asia/Urumqi',
	    '(UTC+09:00) Irkutsk' => 'Asia/Irkutsk',
	    '(UTC+09:00) Osaka' => 'Asia/Tokyo',
	    '(UTC+09:00) Sapporo' => 'Asia/Tokyo',
	    '(UTC+09:00) Seoul' => 'Asia/Seoul',
	    '(UTC+09:00) Tokyo' => 'Asia/Tokyo',
	    '(UTC+09:30) Adelaide' => 'Australia/Adelaide',
	    '(UTC+09:30) Darwin' => 'Australia/Darwin',
	    '(UTC+10:00) Brisbane' => 'Australia/Brisbane',
	    '(UTC+10:00) Canberra' => 'Australia/Canberra',
	    '(UTC+10:00) Guam' => 'Pacific/Guam',
	    '(UTC+10:00) Hobart' => 'Australia/Hobart',
	    '(UTC+10:00) Melbourne' => 'Australia/Melbourne',
	    '(UTC+10:00) Port Moresby' => 'Pacific/Port_Moresby',
	    '(UTC+10:00) Sydney' => 'Australia/Sydney',
	    '(UTC+10:00) Yakutsk' => 'Asia/Yakutsk',
	    '(UTC+11:00) Vladivostok' => 'Asia/Vladivostok',
	    '(UTC+12:00) Auckland' => 'Pacific/Auckland',
	    '(UTC+12:00) Fiji' => 'Pacific/Fiji',
	    '(UTC+12:00) International Date Line West' => 'Pacific/Kwajalein',
	    '(UTC+12:00) Kamchatka' => 'Asia/Kamchatka',
	    '(UTC+12:00) Magadan' => 'Asia/Magadan',
	    '(UTC+12:00) Marshall Is.' => 'Pacific/Fiji',
	    '(UTC+12:00) New Caledonia' => 'Asia/Magadan',
	    '(UTC+12:00) Solomon Is.' => 'Asia/Magadan',
	    '(UTC+12:00) Wellington' => 'Pacific/Auckland',
	    '(UTC+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
	);

}

if ($route == "inventory/assets/data") {
    echo "d3sa";
	//include "data.php";

}


?>
