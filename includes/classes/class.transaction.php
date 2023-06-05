<?php

class Transaction extends App {

    // ----------------------------------------------------------------------------------------------
    // CATEGORIES

    public static function addIncoming($data) {
    	global $database;
        global $liu;
        $year = date('Y');

        $jmlid = $database->count("tx_barangmasuk");
        $nourut = $jmlid+1;
        foreach($database->query("select count(id) jmlid,YEAR(CURDATE()) as thn from tx_barangmasuk group by YEAR(CURDATE())") as $brgmsk){}

        $nomasuk = "BM/".$year."/".sprintf('%04s', $nourut);
        // $data = array(
        // "nomasuk" => $nomasuk, 
        //       "grin" => $data['grin'] , 
        //       "grin_qty" => $data['grin_qty'],
        //       "qty" => $data['qty'],
        //       "userid" => $liu[id],
        //       "gudangid" => $data['gudangid'],
        //       "notes" => $data['notes'] );
      
        $lastid = $database->insert("tx_barangmasuk", 
            [ "nomasuk" => $nomasuk, 
              //"grin" => $data['grin'] , 
              //"grin_qty" => $data['grin_qty'],
              "qty" => $data['qty'],
              "userid" => $liu[id],
              "gudangid" => $data['gudangid'],
              "notes" => $data['notes'] 
            ]);

        foreach($database->query("select * from tx_barangmasuk_dtl where statusid = 0 and userid = '".$liu[id]."'") as $brgdtl){
            $lastid2 = $database->insert("tx_mutasi", 
                [   'id_transmutasi' => $lastid,
                    'no_transmutasi' => $nomasuk,
                    'sparepartid' => $brgdtl['sparepartid'],
                    'masukmutasi' => $brgdtl['qty'],
                    'keluarmutasi' => 0,
                    'jenismutasi' => 1,
                    'id_gudang' => $data['gudangid'],
                    'created_by' => $brgdtl['userid'],
                    'jenisbrg' => $brgdtl['jenisbrg'],

                ]);
        }
       
        $database->query("update tx_barangmasuk_dtl set statusid = 1, masukid = '".$lastid."' where userid = '".$liu[id]."' and statusid = 0");


    	if ($lastid == "0") { return "11"; } else { logSystem("Incoming Part Added - ID: " . $lastid); return "10"; }
    	}

    public static function addOutgoing($data) {
        global $database;
        global $liu;
        $year = date('Y');

        $jmlid = $database->count("tx_barangkeluar");
        $nourut = $jmlid+1;
        foreach($database->query("select count(id) jmlid,YEAR(CURDATE()) as thn from tx_barangkeluar group by YEAR(CURDATE())") as $brgmsk){}

        $nokeluar = "BK/".$year."/".sprintf('%04s', $nourut);
        $data = array(
        "nokeluar" => $nokeluar, 
              "qty" => $data['qty'],
              "userid" => $liu[id],
              "gudangid" => $data['gudangid'],
              "notes" => $data['notes'],
              "jeniskeluar" => $data['jeniskeluar'] );
      //  var_dump($data);
        $lastid = $database->insert("tx_barangkeluar", 
            [ "nokeluar" => $nokeluar, 
              "qty" => $data['qty'],
              "userid" => $liu[id],
              "gudangid" => $data['gudangid'],
              "notes" => $data['notes'],
              "jeniskeluar" => $data['jeniskeluar']
            ]);

        foreach($database->query("select * from tx_barangkeluar_dtl where statusid = 0 and userid = '".$liu[id]."'") as $brgdtl){
            
            $database->query("
                        insert into tx_mutasi (id_transmutasi,no_transmutasi,no_refmutasi1,id_refmutasi1,sparepartid,masukmutasi,keluarmutasi,jenismutasi,id_gudang,tgl_mutasi,created_by,jenisbrg,harga,hargajual)
                        select '".$lastid."','".$nokeluar."',no_transmutasi,id, sparepartid,'0', case when cumulative_sum>0 then stok-cumulative_sum else stok end as k,2,'".$data['gudangid']."',NOW(),'".$liu[id]."', '".$brgdtl[jenisbrg]."',harga, hargajual from (select a.*,@running_total:=stok - abs(@running_total) AS cumulative_sum
                from (select * from (select sparepartid, no_transmutasi, id, sum(stok) stok, harga, hargajual from (select sparepartid, no_transmutasi, case when masukmutasi>0 then id else id_refmutasi1 end as id, sum(masukmutasi)-sum(keluarmutasi) as stok,harga,hargajual from tx_mutasi where sparepartid='".$brgdtl[sparepartid]."' and jenisbrg='".$brgdtl[jenisbrg]."' group by id order by id) a group by id) a where stok>0) a JOIN (SELECT @running_total:=".$brgdtl[qty].", @s:=0) b)a 
            ");
        }
        //die();
        $database->query("update tx_barangkeluar_dtl set statusid = 1, keluarid = '".$lastid."' where userid = '".$liu[id]."' and statusid = 0");


        if ($lastid == "0") { return "11"; } else { logSystem("Outgoing Part Added - ID: " . $lastid); return "10"; }
        }

     public static function addStockOpname($data) {
        global $database;
        global $liu;
        $year = date('Y');

        $jmlid = $database->count("tx_barangmasuk");
        $nourut = $jmlid+1;
        foreach($database->query("select count(id) jmlid,YEAR(CURDATE()) as thn from tx_stockopname group by YEAR(CURDATE())") as $brgmsk){}

        $noso = "SO/".$year."/".sprintf('%04s', $nourut);

        $lastid = $database->insert("tx_stockopname", 
            [ "noso" => $noso, 
              "userid" => $liu[id],
              "gudangid" => $data['gudangid'],
              "jenis_brg" => $data['jenisbrg'] 
            ]);

        foreach ($data['sparepartid'] as $key => $value) {
            if ($data['selisih'][$key] == 'NaN' || $data['selisih'][$key] == '') {
                $selisihnya = 0;
            } else {
                $selisihnya = $data['selisih'][$key];
            }

            if ($data['remark'][$key] == '') {
                $keter = " ";
            } else {
                $keter = $data['remark'][$key];
            }
            // $data4 = array(
            // 'soid'  => $lastid,
            //     'sparepartid'  => $data['sparepartid'][$key],
            //     'qtyakhir'  => $data['qtysystem'][$key],
            //     'qtyfisik'  => $data['qtyfisik'][$key],
            //     'selisih'  => $selisihnya,
            //     'keterangan_so_dtl'  => $keter,
            //     'noref'  => $data['brgmasuk'][$key],
            //     'hargabeli'  => $data['hargabeli'][$key]);
            // var_dump($data4);
            $lastidstl = $database->insert("tx_stockopname_dtl", 
            [   'soid'  => $lastid,
                'sparepartid'  => $data['sparepartid'][$key],
                'qtyakhir'  => $data['qtysystem'][$key],
                'qtyfisik'  => $data['qtyfisik'][$key],
                'selisih'  => $selisihnya,
                'keterangan_so_dtl'  => $keter,
                'noref'  => $data['brgmasuk'][$key],
                'hargabeli'  => $data['hargabeli'][$key],
            ]);
            
            if($selisihnya==0 OR $selisihnya=="" OR empty($selisihnya)){

            } else {
                if($selisihnya<0){
                    $k=abs($selisihnya);
                    $m=0;
                } else {
                    $m=abs($selisihnya);
                    $k=0;
                }

                if ($data['brgmasuk'][$key] == '') {
                    $norefmutasi1 = $noso;
                } else {
                    $norefmutasi1 = $data['brgmasuk'][$key];
                }
                
                $lastidmts = $database->insert("tx_mutasi", 
                [   'id_transmutasi'=>$lastid,
                    'no_transmutasi'=>$noso,
                    'no_refmutasi1'=> $norefmutasi1,
                    'sparepartid'=>$data['sparepartid'][$key],
                    'harga' => $data['hargabeli'][$key],
                    'masukmutasi'=>"$m",
                    'keluarmutasi'=>"$k",
                    'jenismutasi'=>"4",
                    'created_by'=>$liu[id],
                    'jenisbrg'=>$data['jenisbrg'],
                    'id_gudang'=>$data['gudangid']
                ]);
                
            }
        }
    }

    public static function editIncoming($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$database->update("assetcategories", [ "name" => $data['name'], "color" => $data['color'] , "prefixcategory" => $data['prefixcategory'] ], [ "id" => $data['id'] ]);
    	logSystem("AssetCategory Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteAssetCategory($id) {
    	global $database;
        $database->delete("assetcategories", [ "id" => $id ]);
    	logSystem("AssetCategory Deleted - ID: " . $id);
    	return "30";
    	}



    // ----------------------------------------------------------------------------------------------
    // LICENSE TYPES

    public static function addLicenseCategory($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$lastid = $database->insert("licensecategories", [ "name" => $data['name'], "color" => $data['color'], "prefixcategory" => $data['prefixcategory'], "typecategory" => $data['typecategory'] ]);
    	if ($lastid == "0") { return "11"; } else {logSystem("LicenseCategory Added - ID: " . $lastid);  return "10"; }
    	}

    public static function editLicenseCategory($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$database->update("licensecategories", [ "name" => $data['name'], "color" => $data['color'] , "prefixcategory" => $data['prefixcategory'], "typecategory" => $data['typecategory']], [ "id" => $data['id'] ]);
    	logSystem("LicenseCategory Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteLicenseCategory($id) {
    	global $database;
        $database->delete("licensecategories", [ "id" => $id ]);
    	logSystem("LicenseCategory Deleted - ID: " . $id);
    	return "30";
    	}

    // ----------------------------------------------------------------------------------------------
    // ASSET STATES

    public static function addLabel($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$lastid = $database->insert("labels", [ "name" => $data['name'], "color" => $data['color'] ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Label Added - ID: " . $lastid); return "10"; }
    	}

    public static function editLabel($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$database->update("labels", [ "name" => $data['name'], "color" => $data['color'] ], [ "id" => $data['id'] ]);
    	logSystem("Label Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteLabel($id) {
    	global $database;
        $database->delete("labels", [ "id" => $id ]);
    	logSystem("Label Deleted - ID: " . $id);
    	return "30";
    	}

    // ----------------------------------------------------------------------------------------------
    // MANUFACTURERS

    public static function addManufacturer($data) {
    	global $database;
    	$lastid = $database->insert("manufacturers", [ "name" => $data['name'] ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Manufacturer Added - ID: " . $lastid); return "10"; }
    	}

    public static function editManufacturer($data) {
    	global $database;
    	$database->update("manufacturers", [ "name" => $data['name'] ], [ "id" => $data['id'] ]);
    	logSystem("Manufacturer Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteManufacturer($id) {
    	global $database;
        $database->delete("manufacturers", [ "id" => $id ]);
    	logSystem("Manufacturer Deleted - ID: " . $id);
    	return "30";
    	}



    // ----------------------------------------------------------------------------------------------
    // LOCATIONS

    public static function addLocation($data) {
    	global $database;

        foreach ($data['peopleid'] as $ccl => $value) {
            $v[]=$value;
        }
        $g = implode(";", $v);

    	$lastid = $database->insert("locations", [ "name" => $data['name'], 
            "clientid" => $data['clientid'], 
            "peopleid" => $g ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Location Added - ID: " . $lastid); return "10"; }
    	}

    public static function editLocation($data) {
    	global $database;

        foreach ($data['peopleid'] as $ccl => $value) {
            $v[]=$value;
        }
        $g = implode(";", $v);

    	$database->update("locations", [ "name" => $data['name'], 
            "clientid" => $data['clientid'], 
            "peopleid" => $g ], 
            [ "id" => $data['id'] ]);
    	logSystem("Location Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteLocation($id) {
    	global $database;
        $database->delete("locations", [ "id" => $id ]);
    	logSystem("Location Deleted - ID: " . $id);
    	return "30";
    	}


    // ----------------------------------------------------------------------------------------------
    // ASSET MODELS

    public static function addModel($data) {
    	global $database;
    	$lastid = $database->insert("models", [ "name" => $data['name'] ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Model Added - ID: " . $lastid); return "10"; }
    	}

    public static function editModel($data) {
    	global $database;
    	$database->update("models", [ "name" => $data['name'] ], [ "id" => $data['id'] ]);
    	logSystem("Model Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteModel($id) {
    	global $database;
        $database->delete("models", [ "id" => $id ]);
    	logSystem("Model Deleted - ID: " . $id);
    	return "30";
    	}


    // ----------------------------------------------------------------------------------------------
    // SUPPLIERS

    public static function addSupplier($data) {
    	global $database;
    	$lastid = $database->insert("suppliers", [
    		"name" => $data['name'],
    		"address" => $data['address'],
    		"contactname" => $data['contactname'],
    		"phone" => $data['phone'],
    		"email" => $data['email'],
    		"web" => $data['web'],
    		"notes" => $data['notes']
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Supplier Added - ID: " . $lastid); return "10"; }
    	}


    public static function editSupplier($data) {
    	global $database;
    	$database->update("suppliers", [
    		"name" => $data['name'],
    		"address" => $data['address'],
    		"contactname" => $data['contactname'],
    		"phone" => $data['phone'],
    		"email" => $data['email'],
    		"web" => $data['web'],
    		"notes" => $data['notes']
    	], [ "id" => $data['id'] ]);
    	logSystem("Supplier Edited - ID: " . $data['id']);
    	return "20";
    	}


    public static function deleteSupplier($id) {
    	global $database;
        $database->delete("suppliers", [ "id" => $id ]);
    	logSystem("Supplier Deleted - ID: " . $id);
    	return "30";
    	}


 // GUDANG

    public static function addGudang($data) {
        global $database;
        $lastid = $database->insert("gudang", [
            "name" => $data['name'],
            "statusid" => 1,
            "notes" => $data['notes']
        ]);
        if ($lastid == "0") { return "11"; } else { logSystem("Gudang Added - ID: " . $lastid); return "10"; }
        }


    public static function editGudang($data) {
        global $database;
        $database->update("gudang", [
            "name" => $data['name'],
            "notes" => $data['notes']
        ], [ "id" => $data['id'] ]);
        logSystem("Gudang Edited - ID: " . $data['id']);
        return "20";
        }


    public static function deleteGudang($id) {
        global $database;
        
        $database->update("gudang", [
           "statusid" => 0
        ], [ "id" => $id ]);
        logSystem("Gudang Deleted - ID: " . $id);
        return "30";
        }



    // ----------------------------------------------------------------------------------------------
    // QR CODES

    public static function generateQrcodes($data) {
    	global $database;
        global $date;

        $ids = array();

        for ($x = 1; $x <= $data['count']; $x++) {
            $current_id = $database->insert("qrcodes", [ "value" => randomString($data['length']) ]);
            array_push($ids, $current_id);
        }

        $lastid = $database->insert("qrcodes_batches", [ "date" => $date, "ids" => serialize($ids) ]);


    	if ($lastid == "0") { return "11"; } else { logSystem("QR Codes Generated - ID: " . $lastid); return "10"; }
    	}




    public static function editQrcode($data) {
    	global $database;

        $qrcode = getRowById("qrcodes",$data['id']);

    	$database->update("qrcodes", [ "value" => $data['value'] ], [ "id" => $data['id'] ]);

        $database->update("assets", [ "qrvalue" => $data['value'] ], [ "qrvalue" => $qrcode['value'] ]);
        $database->update("licenses", [ "qrvalue" => $data['value'] ], [ "qrvalue" => $qrcode['value'] ]);

    	logSystem("QR Code Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteQrcode($id) {
    	global $database;

        $qrcode = getRowById("qrcodes",$data['id']);

        $database->delete("qrcodes", [ "id" => $id ]);

        $database->update("assets", [ "qrvalue" => "" ], [ "qrvalue" => $qrcode['value'] ]);
        $database->update("licenses", [ "qrvalue" => "" ], [ "qrvalue" => $qrcode['value'] ]);

    	logSystem("QR Code Deleted - ID: " . $id);
    	return "30";
    	}

    public static function deleteBatch($id) {
    	global $database;

        $database->delete("qrcodes_batches", [ "id" => $id ]);
        
    	logSystem("QR Code Batch Deleted - ID: " . $id);
    	return "30";
    	}


    public static function attachQrcode($data) {
    	global $database;

        if(isset($data['id'])) {
            $qrcode = getRowById("qrcodes",$data['id']);
        }

        if(isset($data['value'])) {
            $qrcode = $database->get("qrcodes", "*", ["value" => $data['value']]);
        }


        if(!empty($qrcode)) {
            if($database->has("assets", ["qrvalue" => $qrcode['value']])) return "11";
            elseif($database->has("licenses", ["qrvalue" => $qrcode['value']])) return "11";

            else {
                if($data['asset_id'] != 0) {
                    $database->update("assets", [ "qrvalue" => $qrcode['value'] ], [ "id" => $data['asset_id'] ]);

                }  elseif($data['license_id'] != 0) {
                    $database->update("licenses", [ "qrvalue" => $qrcode['value'] ], [ "id" => $data['license_id'] ]);
                }



                logSystem("QR Code Edited - ID: " . $qrcode['id']);
                return "10";
            }
        }
        else return "11";



    	}

    public static function detachQrcode($id) {
    	global $database;

        $qrcode = getRowById("qrcodes",$id);

        $database->update("assets", [ "qrvalue" => "" ], [ "qrvalue" => $qrcode['value'] ]);
        $database->update("licenses", [ "qrvalue" => "" ], [ "qrvalue" => $qrcode['value'] ]);

    	logSystem("QR Code Detached - ID: " . $id);
    	return "20";
    	}






}

?>
