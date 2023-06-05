<?php

class Asset extends App {


    public static function add($data) {
    	global $database;
        global $liu;

        $categoryid = 0;
        $manufacturerid = 0;
        $modelid = 0;
        $supplierid = 0;
        $locationid = 0;
        
        // var_dump($data);
        // die();
        
        if(isset($data['category'])) {
            $categoryid = $database->get("assetcategories", "id", [ "name" => $data['category'] ]);
            if($categoryid == "") $categoryid = $database->insert("assetcategories", [ "name" => $data['category'], "color" => rand_color() ]);
        }

        if(isset($data['manufacturer'])) {
            $manufacturerid = $database->get("manufacturers", "id", [ "name" => $data['manufacturer'] ]);
            if($manufacturerid == "") $manufacturerid = $database->insert("manufacturers", [ "name" => $data['manufacturer'] ]);
        }

        if(isset($data['model'])) {
            $modelid = $database->get("models", "id", [ "name" => $data['model'] ]);
            if($modelid == "") $modelid = $database->insert("models", [ "name" => $data['model'] ]);
        }

        if(isset($data['supplier'])) {
            $supplierid = $database->get("suppliers", "id", [ "name" => $data['supplier'] ]);
            if($supplierid == "") $supplierid = $database->insert("suppliers", [ "name" => $data['supplier'] ]);
        }

        if(isset($data['location'])) {
            $locationid = $database->get("locations", "id", [ "AND" => [ "name" => $data['location'], "clientid" => $data['clientid'] ] ]);
            if($locationid == "") $locationid = $database->insert("locations", [ "name" => $data['location'], "clientid" => $data['clientid'] ]);
        }

        $prefixCat = getTableFiltered("assetcategories","name",$data['category']);
        foreach($prefixCat as $prfx){}
            
        $customfields = getTable("assets_customfields");
        $customfieldsdata = array();

        foreach ($customfields as $customfield) {
            $customfieldsdata[$customfield['id']] = $data[$customfield['id']];
        }

        if($data['assettype']==1){
            // echo "disini";
            // echo Asset::nextAssetTag();
            // echo $data['purchase_date'];
            $tag=Asset::newassetstag(dateDb($data['purchase_date']),"E");
            $serialnode=Asset::newassetsnode(1,$prfx['prefixcategory'],$categoryid);
            $machid=Asset::existassetsnode($prfx['prefixcategory'],$categoryid);
            // echo "disini";
        } else {
            $tag=Asset::newassetstag(dateDb($data['startcontract']),"R");
            $serialnode=Asset::newassetsnode(2,"R".$prfx['prefixcategory'],$categoryid);
            $machid=Asset::existassetsnode("R".$prfx['prefixcategory'],$categoryid);
        }
        
        if($categoryid == 1 || $categoryid == 2 || $categoryid == 7) {
            $wherecat = "and categoryid in (1,2,7)";
        } else {
            $wherecat = "and categoryid=$categoryid";
        }
        
        foreach($database->query("select max(sequencenode) as maxn from assets where assettype = $data[assettype] $wherecat") as $seqnode){}
        $sequencenode = $seqnode['maxn']+1;

        $nameeishws = getTableFiltered("assets_eis","id",$data['name2']);
        foreach($nameeishws as $nameeishw) {}
       
        if($data['assettype'] == '2'){
            $nameaset = $data['name1'];
            $asethweisid = 0;
        }else{
            $nameaset = $nameeishw['name'];
            $asethweisid = $data['name2'];
            
        }

        $receiveqty = $nameeishw['receive_qty']+1;
        // $data5 = array(
        //     "categoryid" => $categoryid,
        //     "adminid" => $data['adminid'],
        //     "clientid" => $data['clientid'],
        //     "userid" => $data['userid'],
        //     "manufacturerid" => $manufacturerid,
        //     "modelid" => $modelid,
        //     "supplierid" => $supplierid,
        //     "statusid" => $data['statusid'],
        //     "purchase_date" => dateDb($data['purchase_date']),
        //     "warranty_months" => $data['warranty_months'],
        //     "tag" => $tag,
        //     "name" => $data['hostname'],
        //     "serial" => $data['serial'],
        //     "notes" => $data['notes'],
        //     "locationid" => $locationid,
        //     "customfields" => serialize($customfieldsdata),
        //     "qrvalue" => $data['qrvalue'],
        //     "assettype" => $data['assettype'],
        //     "startcontract" => $data['startcontract'],
        //     "endcontract" => $data['endcontract'],
        //     "ipaddress" => $data['ipaddress'],
        //     "userinput" => $liu['id'],
        //     "assetshardwareeisid" => $asethweisid,
        //     "nodenumber" => $serialnode,
        //     "machine_id" => $serialnode,
        //     "name_eis" => $nameaset,
        //     "sequencenode" => $sequencenode,
        // );
    	$lastid = $database->insert("assets", [
    		"categoryid" => $categoryid,
    		"adminid" => $data['adminid'],
    		"clientid" => $data['clientid'],
    		"userid" => $data['userid'],
            "manufacturerid" => $manufacturerid,
    		"modelid" => $modelid,
    		"supplierid" => $supplierid,
    		"statusid" => $data['statusid'],
    		"purchase_date" => dateDb($data['purchase_date']),
    		"warranty_months" => $data['warranty_months'],
    		"tag" => $tag,
    		"name" => $data['hostname'],
    		"serial" => $data['serial'],
    		"notes" => $data['notes'],
            "locationid" => $locationid,
            "customfields" => serialize($customfieldsdata),
            "qrvalue" => $data['qrvalue'],
            "assettype" => $data['assettype'],
            "startcontract" => $data['startcontract'],
            "endcontract" => $data['endcontract'],
            "ipaddress" => $data['ipaddress'],
            "userinput" => $liu['id'],
            "assetshardwareeisid" => $asethweisid,
            "nodenumber" => $serialnode,
            "machine_id" => $serialnode,
            "name_eis" => $nameaset,
            "sequencenode" => $sequencenode,
            "assetroom" => $data['assetroom']
    	]);
         //var_dump($data5);
         //die();
        // if($data['assettype'] == '1'){
        //     foreach($database->query("select id from licenses where categoryid = 11") as $lcd) {
        //         $lastlcd = $database->insert("licenses_assets", [
        //             "licenseid" => $lcd['id'],
        //             "assetid" => $lastid,
        //             "typeasset" => 1,
        //             "notes" => 'Standart Apps',
        //             "inslicenseid" => $lcd['id'],
        //         ]);
        //     }
        // } 
        //die();
    	if ($lastid == "0") { return "11"; } 
        else { logSystem("Asset Added - ID: " . $lastid); 
            if($receiveqty == $nameeishw['actual_qty_s']){ $ssy=1;} else { $ssy=0;}
            $database->update("assets_eis", [
                "status_sync" => $ssy,
                "receive_qty" => $receiveqty,

            ], [ "id" => $data['name2'] ]);
            return "10"; 
        }
    }
    public static function addApi($data) {
        global $database;


        $customfields = getTable("assets_customfields");
        $customfieldsdata = array();

        foreach ($customfields as $customfield) {
            $customfieldsdata[$customfield['id']] = $data[$customfield['id']];
        }
        
        $lastid = $database->insert("assets", [
            "categoryid" => $data['categoryid'],
            "adminid" => $data['adminid'],
            "clientid" => $data['clientid'],
            "userid" => $data['userid'],
            "manufacturerid" => $data['manufacturerid'],
            "modelid" => $data['modelid'],
            "supplierid" => $data['supplierid'],
            "statusid" => $data['statusid'],
            "purchase_date" => $data['purchase_date'],
            "warranty_months" => $data['warranty_months'],
            "tag" => $data['tag'],
            "name" => $data['name'],
            "serial" => $data['serial'],
            "notes" => $data['notes'],
            "locationid" => $data['locationid'],
            "customfields" => serialize($customfieldsdata),
            "qrvalue" => $data['qrvalue'],

        ]);
        if ($lastid == "0") { return "11"; } else { logSystem("Asset Added - ID: " . $lastid); return "10"; }
    }

    public static function edit($data) {
    	global $database;
        global $liu;
        
        $categoryid = 0;
        $manufacturerid = 0;
        $modelid = 0;
        $supplierid = 0;
        $locationid = 0;

        if(isset($data['category'])) {
            $categoryid = $database->get("assetcategories", "id", [ "name" => $data['category'] ]);
            if($categoryid == "") $categoryid = $database->insert("assetcategories", [ "name" => $data['category'], "color" => rand_color() ]);
        }

        if(isset($data['manufacturer'])) {
            $manufacturerid = $database->get("manufacturers", "id", [ "name" => $data['manufacturer'] ]);
            if($manufacturerid == "") $manufacturerid = $database->insert("manufacturers", [ "name" => $data['manufacturer'] ]);
        }

        if(isset($data['model'])) {
            $modelid = $database->get("models", "id", [ "name" => $data['model'] ]);
            if($modelid == "") $modelid = $database->insert("models", [ "name" => $data['model'] ]);
        }

        if(isset($data['supplier'])) {
            $supplierid = $database->get("suppliers", "id", [ "name" => $data['supplier'] ]);
            if($supplierid == "") $supplierid = $database->insert("suppliers", [ "name" => $data['supplier'] ]);
        }

        if(isset($data['location'])) {
            $locationid = $database->get("locations", "id", [ "AND" => [ "name" => $data['location'], "clientid" => $data['clientid'] ] ]);
            if($locationid == "") $locationid = $database->insert("locations", [ "name" => $data['location'], "clientid" => $data['clientid'] ]);
        }

        $customfields = getTable("assets_customfields");
        $customfieldsdata = array();

        foreach ($customfields as $customfield) {
            $customfieldsdata[$customfield['id']] = $data[$customfield['id']];
        }
//cek data lama
        foreach($database->query("select * from assets where id = $data[id]") as $datalama){}

        $prefixCat = getTableFiltered("assetcategories","name",$data['category']);
        foreach($prefixCat as $prfx){}
            
        if($datalama['categoryid'] == $categoryid){
            $machineid =  $data['machine_id'];
            $nodenumber = $data['nodenumber'];
            $sequencenode = $data['sequencenode'];
        } else {
            if($data['assettype']==1){
                $serialnode1=Asset::newassetsnode(1,$prfx['prefixcategory'],$categoryid);
            } else {
                $serialnode1=Asset::newassetsnode(2,"R".$prfx['prefixcategory'],$categoryid);
            }

            if($data['machine_id'] == $data['nodenumber']){
                $machineid =  $serialnode1;
                $nodenumber = $serialnode1;  
            } else {
                $machineid = $data['machine_id'];
                $nodenumber = $serialnode1;
            }

            if($categoryid == 1 || $categoryid == 2 || $categoryid == 7) {
                $wherecat = "and categoryid in (1,2,7)";
            } else {
                $wherecat = "and categoryid=$categoryid";
            }
            foreach($database->query("select max(sequencenode) as maxn from assets where assettype = $data[assettype] $wherecat") as $seqnode){}
            $sequencenode = $seqnode['maxn']+1;
        }

    	$database->update("assets", [
            "categoryid" => $categoryid,
    		"adminid" => $data['adminid'],
    		"clientid" => $data['clientid'],
    		"userid" => $data['userid'],
            "manufacturerid" => $manufacturerid,
    		"modelid" => $modelid,
    		"supplierid" => $supplierid,
    		"statusid" => $data['statusid'],
    		"purchase_date" => dateDb($data['purchase_date']),
    		"warranty_months" => $data['warranty_months'],
    		"tag" => $data['tag'],
    		"name" => $data['name'],
            "machine_id" => $machineid,
    		"serial" => $data['serial'],
    		"notes" => $data['notes'],
            "locationid" => $locationid,
            "customfields" => serialize($customfieldsdata),
            "qrvalue" => $data['qrvalue'],
            "startcontract" => $data['startcontract'],
            "endcontract" => $data['endcontract'],
            "ipaddress" => $data['ipaddress'],
            "userinput" => $liu['id'],
            "nodenumber" => $nodenumber,
            "sequencenode" => $sequencenode,
            "assetroom" => $data['assetroom']

    	], [ "id" => $data['id'] ]);
    	logSystem("Asset Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function editApi($data) {
    	global $database;


        $customfields = getTable("assets_customfields");
        $customfieldsdata = array();

        foreach ($customfields as $customfield) {
            $customfieldsdata[$customfield['id']] = $data[$customfield['id']];
        }

    	$database->update("assets", [
            "categoryid" => $data['categoryid'],
    		"adminid" => $data['adminid'],
    		"clientid" => $data['clientid'],
    		"userid" => $data['userid'],
            "manufacturerid" => $data['manufacturerid'],
    		"modelid" => $data['modelid'],
    		"supplierid" => $data['supplierid'],
    		"statusid" => $data['statusid'],
    		"purchase_date" => $data['purchase_date'],
    		"warranty_months" => $data['warranty_months'],
    		"tag" => $data['tag'],
    		"name" => $data['name'],
    		"serial" => $data['serial'],
    		"notes" => $data['notes'],
            "locationid" => $data['locationid'],
            "customfields" => serialize($customfieldsdata),
            "qrvalue" => $data['qrvalue'],

    	], [ "id" => $data['id'] ]);
    	logSystem("Asset Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function delete($id) {
    	global $database;
        global $liu;
        $database->update("assets", [
    		"userid" => 0,
            "statusid" => 0,
            "userupdate" => $liu['id']

    	], [ "id" => $id ]);
        //$database->delete("assets", [ "id" => $id ]);
    	logSystem("Asset Deleted - ID: " . $id);
    	return "30";
    }

    public static function nextAssetTag() {
    	global $database;
        $max = $database->max("assets", "id");
    	return $max+1;
    	}

    public static function categoryPrefix($id) {
        echo $id;
        global $database;
        $catpr = $database->query("select prefixcategory from assetcategories where name = $id");
        return $catpr;
        }

    public static function newassetstag($purchdate,$tagd){
        global $database;

        $ypurc=date("Y",strtotime($purchdate));
        $mpurc=date("m",strtotime($purchdate));
        if($tagd === "E"){
            $max2 = $database->query("select max(id) as maxn from assets");
        } else {
            $max2 = $database->query("select max(id) as maxn from assets");
        }
        foreach($max2 as $max){}
        $no=$tagd."".$ypurc."".$mpurc."-".sprintf('%04s', $max['maxn']+1);

        return $no;
    }

    public static function newassetsnode($assettype,$ntag,$categoryid){
        global $database;
        if($categoryid == 1 || $categoryid == 2 || $categoryid == 7) {
            $wherecat = "and categoryid in (1,2,7)";
        } else {
            $wherecat = "and categoryid=$categoryid";
        }
        //$max = $database->max("assets", "id");
        //if($assettype === "1"){
            $max2 = $database->query("select max(sequencenode) as maxn from assets where assettype = $assettype $wherecat");
        //} else {
        //    $max2 = $database->query("select COUNT(id) as maxn from assets where assettype = 2");
        //}
        foreach($max2 as $max){}
        $no=$ntag.sprintf('%04s', $max['maxn']+1);

        return $no;
    }

    public static function existassetsnode($ntag,$categoryid){
        global $database;
        //echo "select SUBSTR(max(serial),5) as maxn from assets where serial like '%NODE%'";
        $max2 = $database->query("select max(SUBSTR(machine_id,-3)) as maxn from assets where machine_id like '%$ntag%'");
        foreach($max2 as $max3){}
        
        $no=$ntag.sprintf('%04s', $max3['maxn']+1);
        //echo $no;
        return $no;
    }

    public static function assignLicense($data) { //assign license to asset
    	global $database;
       
        $explic = explode("_",$data['licenseid']);
        if($data['inslicenseid'] == 0) {
            $inst = $explic[0];
            $sel = $explic[1];
        } else {
            $inst = $data['inslicenseid'];
            $sel = 0;
        }
        
    	$lastid = $database->insert("licenses_assets", [
    		"licenseid" => $explic[0],
    		"assetid" => $data['assetid'],
            "typeasset" => 1,
            "notes" => $data['assignnotes'],
            "inslicenseid" => $inst,
            "leaklicense" => $sel,
            "licenseiddtl" => $data['licensedtl'],
    	]);
    	if ($lastid == "0") { return "11"; } else { return "10"; }
    }

    public static function unassignLicense($id) { //unassign license to asset
    	global $database;
        $database->delete("licenses_assets", [ "id" => $id ]);
    	return "30";
    }

    public static function assignts($data) {
        global $database;
        $selaset = $database->query("select a.*,b.email from assets a left join people b on a.userid=b.id where a.id = '$_POST[assetid]'");
        foreach($selaset as $saset){}

        $depts =  getTableFiltered("clients","id","$data[clientid]");
        foreach($depts as $dept);

        $tgl = date('Y-m-d');
        $thn = date("y",strtotime($tgl));
        $bln = date("m",strtotime($tgl));
        $tg = date("d",strtotime($tgl));
        $query = $database->query("select ticket AS maxID from tickets where SUBSTRING_INDEX(SUBSTRING_INDEX(ticket,'/',2),'/',-1)='$dateid' ORDER BY 
    SUBSTRING_INDEX(ticket,'/',-2) desc limit 1");
        foreach($query as $dta){}
        
        $idMaxj = $dta['maxID'];
        $temp=explode("/",$idMaxj);
        
        $noUrutj = intval($temp[2]);
        $noBlnj =  substr($temp[1], 0, 6); 
        
        
        if($noBlnj<> $thn.$bln.$tg)
        {
            $noUrutj=1;
        } else {
            $noUrutj++;
        }

        $noticket= $dept['inisial_client']."/".$thn."".$bln."".$tg."/".sprintf("%03s", $noUrutj);
        // $data3 = array('tickets' =>  $noticket,
        //                     'departmentid' => 1,
        //                     'clientid' => $_POST['clientid'],
        //                     'userid' => $saset['userid'],
        //                     'adminid' => 1,
        //                     'assetid' => $_POST['assetid'],
        //                     'projectid' => 0,
        //                     'email' => 'itsa',
        //                     'subject' => $_POST['tsnotes'],
        //                     'status' => 'Open',
        //                     'priority' => 'Normal',
        //                     'timestamp' => date('Y-m-d H:i:s'),
        //                     'timespent' => 0,
        //                     'reported' => 'SA Team',
        //                     'reported_ip' => '0',
        //                     'typeasset' => 1);
        // var_dump($data3);
        // die();
        $ticketid = $database->insert("tickets", [
                            'ticket' =>  $noticket,
                            'departmentid' => 1,
                            'clientid' => $_POST['clientid'],
                            'userid' => $saset['userid'],
                            'adminid' => $_POST['peopleid'],
                            'assetid' => $_POST['assetid'],
                            'projectid' => 0,
                            'email' => $saset['email'],
                            'subject' => $_POST['tsnotes'],
                            'status' => 'Open',
                            'priority' => 'Normal',
                            'timestamp' => date('Y-m-d H:i:s'),
                            'timespent' => 0,
                            'reported' => 'SA Team',
                            'reported_ip' => '0',
                            'typeasset' => 1,
                            'typeticket' => 2,
                    ]);
            
            $lastid = $database->insert("tickets_replies", [
                    "ticketid" => $ticketid,
                    "peopleid" => $saset['userid'],
                    "message" => $_POST['tsnotes'],
                    "timestamp" => date('Y-m-d H:i:s')
                ]);

            $lastid2 = $database->query("update assets set tsassignstatus = 1,tsassignnotes = '$_POST[tsnotes]' where id = '$_POST[assetid]'");

            Notification::formassets($ticketid, $_POST['tsnotes'], 11,'Open');
        if ($lastid == "0") { return "11"; } else { logSystem("Ticket Added - ID: " . $lastid); return "10"; }
    }
}


?>
