<?php

class maintenance extends App {


    // ARTICLES

    public static function add($data) {
        global $database;
        global $liu;
        $year = date('Y');

        if(empty($data['clients'])) $clients = ""; else $clients = serialize($data['clients']);

        $jmlid = $database->count("maintenance");
        $nourut = $jmlid+1;
        foreach($database->query("select count(id) jmlid,MAX(YEAR(tgl_input)) as thn from maintenance where YEAR(tgl_input) = (select MAX(YEAR(tgl_input)) from maintenance)") as $brgmsk){}

        $nomtc = "MTC/".$year."/".sprintf('%04s', $nourut);

        $lastid = $database->insert("maintenance", [
            "assetid" => $data['assetid'],
            "type_input" => $data['typemtc'],
            "tgl_mtc" => $data['tgl_mtc'],
            "tgl_input" => date('Y-m-d H:i:s'),
            "userid" => $liu['id'],
            "notedmtc" => $data['notedmtc'],
            "nomtc" => $nomtc,
            "ticketid" => $data['ticketid']
            ]);
        

    if($data['typemtc'] == 2){
        foreach($database->query("select * from maintenance_dtl where statusid = 0 and userid = '".$liu[id]."'") as $brgdtl){
            if($brgdtl['jenisganti'] != 3){
            //keluar barang baru/bekas
                $database->query("
                        insert into tx_mutasi (id_transmutasi,no_transmutasi,no_refmutasi1,id_refmutasi1,sparepartid,masukmutasi,keluarmutasi,jenismutasi,id_gudang,tgl_mutasi,created_by,jenisbrg,harga,hargajual)
                        select '".$lastid."','".$nomtc."',no_transmutasi,id, sparepartid,'0', case when cumulative_sum>0 then stok-cumulative_sum else stok end as k,3,'".$data['gudangid']."',NOW(),'".$liu[id]."', '".$brgdtl[jenisbrg]."',harga, hargajual from (select a.*,@running_total:=stok - abs(@running_total) AS cumulative_sum
                from (select * from (select sparepartid, no_transmutasi, id, sum(stok) stok, harga, hargajual from (select sparepartid, no_transmutasi, case when masukmutasi>0 then id else id_refmutasi1 end as id, sum(masukmutasi)-sum(keluarmutasi) as stok,harga,hargajual from tx_mutasi where sparepartid='".$brgdtl[sparepartid]."' and jenisbrg='".$brgdtl[jenisbrg]."' group by id order by id) a group by id) a where stok>0) a JOIN (SELECT @running_total:=".$brgdtl[qty].", @s:=0) b)a 
                ");
                
            }
        
            if($brgdtl['jenisganti'] != 1){
            //masuk barang bekas
            $lastid2 = $database->insert("tx_mutasi", 
                [   'id_transmutasi' => $lastid,
                    'no_transmutasi' => $nomtc,
                    'sparepartid' => $brgdtl['sparepartid'],
                    'masukmutasi' => $brgdtl['qty'],
                    'keluarmutasi' => 0,
                    'jenismutasi' => 3,
                    'id_gudang' => $data['gudangid'],
                    'created_by' => $brgdtl['userid'],
                    'jenisbrg' => 2,
                ]);
            }
        }
        
        $database->query("update maintenance_dtl set statusid = 1, mtcid = '".$lastid."' where userid = '".$liu[id]."' and statusid = 0");
        
    }

        if ($lastid == "0") { return "11"; } else { logSystem("Maintenance Added - ID: " . $lastid); return "10"; }
        
    }

    public static function edit($data) {
        global $database;
        if(empty($data['clients'])) $clients = ""; else $clients = serialize($data['clients']);

        $database->update("kb_articles", [
            "categoryid" => $data['categoryid'],
            "clients" => $clients,
            "name" => $data['name'],
            "content" => $data['content']

            ], [ "id" => $data['id'] ]);

        logSystem("Knowledge Base Article Edited - ID: " . $data['id']);
        return "20";

    }

    public static function delete($id) {
        global $database;

        $database->delete("kb_articles", [ "id" => $id ]);

        logSystem("Knowledge Base Article Deleted - ID: " . $id);
        return "30";

    }



    // CATEGORIES

    public static function addCategory($data) {
        global $database;
        if(empty($data['clients'])) $clients = ""; else $clients = serialize($data['clients']);

        $lastid = $database->insert("kb_categories", [
            "clients" => $clients,
            "name" => $data['name']
            ]);

        if ($lastid == "0") { return "11"; } else { logSystem("Knowledge Base Category Added - ID: " . $lastid); return "10"; }

    }

    public static function editCategory($data) {
        global $database;
        if(empty($data['clients'])) $clients = ""; else $clients = serialize($data['clients']);

        $database->update("kb_categories", [
            "clients" => $clients,
            "name" => $data['name']

            ], [ "id" => $data['id'] ]);

        logSystem("Knowledge Base Category Edited - ID: " . $data['id']);
        return "20";

    }

    public static function deleteCategory($id) {
        global $database;

        $database->delete("kb_categories", [ "id" => $id ]);

        logSystem("Knowledge Base Category Deleted - ID: " . $id);
        return "30";

    }




}

?>
