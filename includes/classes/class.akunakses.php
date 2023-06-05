<?php

class Akunakses extends App {


    public static function add($data) {
    	foreach ($data['ccs'] as $ccl => $value) {
            $v[]=$value;
        }
        $g = implode(";", $v);
        
        global $database;
    	$lastid = $database->insert("akunminta", [
    		"namaakunminta" => $data['name'],
            "peopleid" => $g,
            "statusakunminta" => 1
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Contact Added - ID: " . $lastid); return "10"; }
    	}


    public static function edit($data) {
        
        foreach ($data['ccs'] as $ccl => $value) {
            $v[]=$value;
        }
        $g = implode(";", $v);
       
       
    	global $database;
    	$database->update("akunminta", [
    		"namaakunminta" => $data['name'],
            "peopleid" => $g,
            "parentakunminta" => $data['parent']
        ], [ "idakunminta" => $data['id'] ]);
    	logSystem("Akun Akses Edited - ID: " . $data['id']);
    	return "20";
    	}


    public static function delete($id) {
    	global $database;
        //$database->delete("akunminta", [ "idakunminta" => $id ]);
    	$database->update("akunminta", [
            "statusakunminta" => 0,
        ], [ "idakunminta" => $id ]);
        logSystem("Akun Akses  Deleted - ID: " . $id);
    	return "30";
    	}


}

?>
