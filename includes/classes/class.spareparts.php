<?php
class Spareparts extends App {

    public static function add($data) {
    	global $database;
		global $liu;
		echo "ada";
		$ypurc=date("Y");
        $mpurc=date("m");
		//echo $ypurc;
        $max2 = $database->query("select COUNT(id) as maxn from spareparts where statusid <> 0");
        foreach($max2 as $max){}
		$urut = $max['maxn']+1;
        $no="SP".$ypurc."".$mpurc."-".sprintf('%04s', $urut);

		//$tagn=Virtual::newvirtualtag("VM");
		//echo $no;
		//die();
		//echo "b";
    	$lastid = $database->insert("spareparts", [
    		"name" => $data['name'],
    		"min_stock" => $data['min_stock'],
            "tag" => $no,    		
            "remark" => $data['notes'],
            // "kode_part" => $data['kode_part']
    	]);

    	if ($lastid == "0") { return "11"; } else { logSystem("Spareparts Added - ID: " . $lastid); return "10"; }
    }

    public static function edit($data) {
    	global $database;
		global $liu;

    	$database->update("spareparts", [
    		"name" => $data['name'],
    		"min_stock" => $data['min_stock'],
            "remark" => $data['notes']
    	], [ "id" => $data['id'] ]);
    	logSystem("Spareparts Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function delete($id) {
    	global $database;
		global $liu;
		
		$database->update("spareparts", [
    		"statusid" => 0,
    	], [ "id" => $id ]);
        //$database->delete("virtuals", [ "id" => $id ]);
    	logSystem("Spareparts Deleted - ID: " . $id);
    	return "30";
    }

}


?>
