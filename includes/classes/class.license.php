<?php

class License extends App {


    public static function add($data) {

        global $database;
        global $liu;
        global $scriptpath;

        // $customfields = getTable("licenses_customfields");
        // $customfieldsdata = array();

        // foreach ($customfields as $customfield) {
        //     $customfieldsdata[$customfield['id']] = $data[$customfield['id']];
        // }

        $expaset = explode('_', $data['assetslicenseeisid']);

        $tag=License::newlicensestag(dateDb($data['purchase_date']),"EL");
        $selurut = $database->query("select max(sequenceno) as maxn from licenses");
        foreach($selurut as $urut){}
        $sequrut = $urut['maxn']+1;

        $expcate = explode("_",$data['categoryid']);

        if($data['serial'] != ''){
            $serialcript = encrypt_decrypt('encrypt', $data['serial']);
        } else {
            $serialcript = "";
        }

        if($data['inputtype'] == 1){
            // $dada = array (
            //     "clientid" => $data['clientid'],
            //     "statusid" => $data['statusid'],
            //     "categoryid" => $data['categoryid'],
            //     "tag" => $tag,
            //     "name" => $data['name'],
            //     "sequenceno" => $sequrut,
            //     );

            $lastid = $database->insert("licenses", [
                "clientid" => $data['clientid'],
                "statusid" => $data['statusid'],
                "categoryid" => $expcate[0],
                "tag" => $tag,
                "name" => $data['name'],
                "sequenceno" => $sequrut,
            ]);
            //var_dump($dada);
            //die();
            foreach($database->query("select max(id) id from licenses") as $idheadlc){}
           
            $lastid2 = $database->insert("licenses_dtl", [
                "licensesid" => $idheadlc['id'],
                "statusid" => $data['statusid'],
                "supplierid" => $data['supplierid'],
                "seats" => $data['seats'],
                "serial" => $serialcript,
                "notes" => $data['notes'],
                "assetslicenseseisid" => $expaset[0],
                "purchase_date" => $data['purchase_date'],
                "created_by" => $liu['id'],
                "startsubsdate" => $data['startcontract'],
                "endsubsdate" => $data['endcontract'],
            ]);
            
            //foreach($database->query("select max(id) id from licenses_dtl") as $iddtlc){}

        } else {
            $lastid2 = $database->insert("licenses_dtl", [
                "licensesid" => $data['name2'],
                "statusid" => $data['statusid'],
                "supplierid" => $data['supplierid'],
                "seats" => $data['seats'],
                "serial" => $serialcript,
                "notes" => $data['notes'],
                "assetslicenseseisid" => $expaset[0],
                "purchase_date" => $data['purchase_date'],
                "created_by" => $liu['id'],
                "startsubsdate" => $data['startcontract'],
                "endsubsdate" => $data['endcontract'],
            ]);

            //foreach($database->query("select max(id) id from licenses_dtl") as $iddtlc){}
            
            
           
        }

        if ($lastid2 == "0") { return "11"; } else { logSystem("License Added - ID: " . $lastid2); return "10"; }
    }


    public static function edit($data) {
        global $database;

        $customfields = getTable("licenses_customfields");
        $customfieldsdata = array();

        foreach ($customfields as $customfield) {
            $customfieldsdata[$customfield['id']] = $data[$customfield['id']];
        }

        $database->update("licenses", [
            //"clientid" => $data['clientid'],
            "statusid" => $data['statusid'],
            "categoryid" => $data['categoryid'],
            "supplierid" => $data['supplierid'],
            "seats" => $data['seats'],
            "tag" => $data['tag'],
            "name" => $data['name'],
            "serial" => encrypt_decrypt('encrypt', $data['serial']),
            "notes" => $data['notes'],
            "customfields" => serialize($customfieldsdata),
            "qrvalue" => $data['qrvalue'],
        ], [ "id" => $data['id'] ]);
        logSystem("License Edited - ID: " . $data['id']);
        return "20";
    }

    public static function delete($id) {
        global $database;
        $database->delete("licenses", [ "id" => $id ]);
        logSystem("License Deleted - ID: " . $id);
        return "30";
    }

    public static function deletedetail($id) {
        global $database;
        $database->update("licenses_dtl", [
            "statusid" => 0,
        ], [ "id" => $id ]);
        $database->update("licenses_assets", [
            "leaklicense" => '1',
            "licenseiddtl" => null            
        ], [ "licenseiddtl" => $id ]);
        
        //$database->delete("licenses_dtl", [ "id" => $id ]);
        logSystem("License Detail Deleted - ID: " . $id);
        return "30";
    }

    public static function nextLicenseTag() {
        global $database;
        $max = $database->max("licenses", "id");
        return $max+1;
    }


    public static function assignAsset($data) { //assign license to asset
        global $database;
        $explic = explode("_",$data['licenseid']);
        if($data['inslicenseid'] == 0) {
            $inst = $explic[0];
            if($explic[2] == 'Free'){$sel = 0;}else{$sel = $explic[1];}
        } else {
            $inst = $data['inslicenseid'];
            $sel = 0;
        }

        if($data[tipeadd] == 1){
            $lastid = $database->insert("licenses_assets", [
                "licenseid" => $explic[0],
                "assetid" => $data['assetid'],
                "typeasset" => 1,
                "notes" => $data['assignnotes'],
                "inslicenseid" => $inst,
                "leaklicense" => $sel,
                "licenseiddtl" => $data['licensedtl']
            ]);
        } else {
            $lastid = $database->insert("licenses_assets", [
                "licenseid" => $explic[0],
                "assetid" => $data['assetid']
,                "typeasset" => 1,
                "notes" => $data['assignnotes'],
                "inslicenseid" => $inst,
                "leaklicense" => $sel,
                "licenseiddtl" => $data['licensedtl'],
                "ticketid" => $data['ticketid']
            ]);
        }
        if ($lastid == "0") { return "11"; } else { return "10"; }
    }
    
    public static function assignAsset2($data) { //assign license to asset
        global $database;
        if($data['balance'] > 0) {
            $sel = 0;
        } else {
            if($data['typecat'] == 'Free'){$sel = 0;}else{$sel = 1;}
        }

        $lastid = $database->insert("licenses_assets", [
            "licenseid" => $data['licenseid'],
            "assetid" => $data['assetid'],
            "typeasset" => 1,
            "notes" => $data['assignnotes'],
            "inslicenseid" => $data['licenseid'],
            "leaklicense" => $sel,
            "licenseiddtl" => $data['licensedtl']
        ]);
        if ($lastid == "0") { return "11"; } else { return "10"; }
    }

    public static function unassignAsset($id) { //unassign license to asset
        global $database;
        $database->delete("licenses_assets", [ "id" => $id ]);
        return "30";
    }

    public static function updatenolicenseAsset($data) { //unassign license to asset
        global $database;
        $database->update("licenses_assets", [
            "leaklicense" => 0,
            "inslicenseid" => $data['routeid'],
            "notes" => "",
            "licenseiddtl" => $data['licensedtl']
        ], [ "id" => $data['id'] ]);
        //$database->delete("licenses_dtl", [ "id" => $id ]);
        logSystem("License Detail Update - ID: " . $data['id']);
        return "30";
    }

    public static function extendsubscription($data) { //unassign license to asset
        global $database;
        global $liu;
        $database->update("licenses_dtl", [
            "startsubsdate" => $data['startcontract'],
            "endsubsdate" => $data['endcontract'],
            "notes" => $data['editnotes'],
        ], [ "id" => $data['licensedtlid'] ]);

       $database->insert("licenses_dtl_history", [
            "licensedtlid" => $data['licensedtlid'],
            "startdate" => $data['startcontractnow'],
            "enddate" => $data['endcontractnow'],
            "userid" => $liu['id'],
        ]);

        //$database->delete("licenses_dtl", [ "id" => $id ]);
        logSystem("License Detail Update - ID: " . $data['licensedtlid']);
        return "30";
    }

    public static function countUsedSeats($id) { //unassign license to asset
        global $database;
        return $database->count("licenses_assets", [ "licenseid" => $id ] );
    }

    public static function newlicensestag($purchdate,$tagd){
        global $database;

        $ypurc=date("Y",strtotime($purchdate));
        $mpurc=date("m",strtotime($purchdate));
        
        $max2 = $database->query("select max(sequenceno) as maxn from licenses");
        
        foreach($max2 as $max){}
        $no=$tagd."".$ypurc."".$mpurc."-".sprintf('%04s', $max['maxn']+1);

        return $no;
    }

    public static function qtylicenses($id){
        global $database;

        $max2 = $database->query("select * from assets_eis where id = '".$id."'");
        
        foreach($max2 as $max){}
        $no=$max['actual_qty_s'];

        return $id;
    }
}


?>
