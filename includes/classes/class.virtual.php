<?php
class Virtual extends App {


    public static function add($data) {
    	global $database;
		global $liu;

		$ypurc=date("Y");
        $mpurc=date("m");
		//echo $ypurc;
        $max2 = $database->query("select COUNT(id) as maxn from virtuals where statusid <> 0");
        foreach($max2 as $max){}
		$urut = $max['maxn']+1;
        $no="VM".$ypurc."".$mpurc."-".sprintf('%04s', $urut);

		//$tagn=Virtual::newvirtualtag("VM");
		//echo $no;
		//die();
		//echo "b";
    	$lastid = $database->insert("virtuals", [
    		"name" => $data['name'],
    		"statusid" => $data['statusid'],
    		"assetid" => $data['asref'],
    		"tag" => $no,
			"notes" => $data['notes'],
			"ipaddress_vm" => $data['ipaddress'],
            "createdby" => $liu['id'],
            "parentid" => $data['parentid'],
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("virtual Added - ID: " . $lastid); return "10"; }
    }


    public static function edit($data) {
    	global $database;
		global $liu;

    	$database->update("virtuals", [
    		"name" => $data['name'],
    		"statusid" => $data['statusid'],
    		"assetid" => $data['asref'],
			"notes" => $data['notes'],
            "updatedby" => $liu['id'],
			"ipaddress_vm" => $data['ipaddress'],
            "parentid" => $data['parentid'],
    	], [ "id" => $data['id'] ]);
    	logSystem("virtual Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function delete($id) {
    	global $database;
		global $liu;
		
		$database->update("virtuals", [
    		"statusid" => 0,
    		"notes" => $data['notes'],
            "updatedby" => $liu['id'],
    	], [ "id" => $id ]);
        //$database->delete("virtuals", [ "id" => $id ]);
    	logSystem("virtual Deleted - ID: " . $id);
    	return "30";
    }

    public static function nextVirtualTag() {
    	global $database;
        $max = $database->max("virtual", "id");
    	return $max+1;
    }


    public static function assignLicense($data) { //assign virtual to asset
    	global $database;
        $explic = explode("_",$data['licenseid']);
        if($explic[2] == 'Free'){$leaklicense = 0;}else{$leaklicense=$explic[1];}
    	$lastid = $database->insert("licenses_assets", [
    		"licenseid" => $explic[0],
    		"assetid" => $data['assetid'],
            "typeasset" => '2',
            "inslicenseid" => $explic[0],
            "leaklicense" => $leaklicense
    	]);
        // $fafa = array(
        //     "licenseid" => $data['licenseid'],
        //     "assetid" => $data['assetid'],
        //     "typeasset" => '2');
         //var_dump($lastid);
         //die();
    	if ($lastid == "0") { return "11"; } else { return "10"; }
    }

    public static function unassignLicense($id) { //unassign virtual to asset
    	global $database;
        $database->delete("licenses_assets", [ "id" => $id ]);
        logSystem("virtual License Deleted - ID: " . $id);
    	return "30";
	}


	public static function countUsedSeats($id) { //unassign virtual to asset
    	global $database;
        return $database->count("virtuals_assets", [ "virtualid" => $id ] );
    }

	public static function newVirtualtag($tagd){
        global $database;
		
        $ypurc=date("Y");
        $mpurc=date("m");
		//echo $ypurc;
        $max2 = $database->query("select COUNT(id) as maxn from virtuals where statusid <> 0");
        foreach($max2 as $max){}
        $no=$tagd."".$ypurc."".$mpurc."-".sprintf('%04s', $max['maxn']+1);

        return $no;
    }

	public static function qtyvirtual($id){
        global $database;

        $max2 = $database->query("select * from assets_eis where id = '".$id."'");
        
        foreach($max2 as $max){}
        $no=$max['actual_qty_s'];

        return $id;
    }
}


?>
