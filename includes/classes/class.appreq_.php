<?php

class Appreq extends App {

    public static function edit($data) {
    	global $database;
        global $liu;
        
        $tickets = $database->query("select a.name as namepeople, c.name as namedept, b.title,b.mobile,b.statusid,b.id as iddtl,b.typereq,a.email,b.alasanbutuh,b.tgl_butuh1,b.tgl_butuh2,b.akunakses,b.websiteakses,b.hasilpengecekanaset,b.assetid,d.headpeopleid,headstatus,DATE(headappdate) as headappdate,d.itpeopleid,itstatus,DATE(itappdate) as itappdate,itnotes,b.locationid,DATE(b.created_date) as created_date_dtl,e.name as locationname,a.id as idpeople,b.clientid,b.peopledtlidparent,c.approve_type from people a join people_dtl_request b on a.id=b.peopleid join clients c on b.clientid=c.id join people_dtl_approve d on b.id=d.peopledtlid join locations e on b.locationid=e.id
where b.id = '$data[id]' ORDER BY b.id DESC");
        foreach($tickets as $ticket){}

//update approval
        //jika departemen IT akan otomatis approve all
        if($ticket['approve_type'] == 1){
            $database->update("people_dtl_approve", [
                "headstatus" => $data['itstatus'],
                "headpeopleid" => $data['sessionid'],
                "headnotes" => $data['itnotes'],
                "headappdate" => date('Y-m-d H:i:s'),
                "itstatus" => $data['itstatus'],
                "itpeopleid" => $data['sessionid'],
                "itnotes" => $data['itnotes'],
                "itappdate" => date('Y-m-d H:i:s'),
            ], [ "peopledtlid" => $data['id'] ]);
            logSystem("Approve Request Edited - ID: " . $data['id']);
        } else {
            $database->update("people_dtl_approve", [
                "itstatus" => $data['itstatus'],
                "itpeopleid" => $data['sessionid'],
                "itnotes" => $data['itnotes'],
                "itappdate" => date('Y-m-d H:i:s'),
            ], [ "peopledtlid" => $data['id'] ]);
            logSystem("Approve Request Edited - ID: " . $data['id']);
        }

        if($data['statuspeople'] == 'Request'){
            $database->update("people", [
                "statusid" => "Active",
            ], [ "id" => $data['idpeople'] ]);
        }
        
// end update approval

        if($ticket[typereq] == 1){$ctk = "newrequestacc";$typereq = 'Permintaan Akun';
            if($ticket['peopledtlidparent'] > 0){
                $database->query("update assets set userid = '$ticket[idpeople]',statusid='3' where id = '$ticket[assetid]'");
            }
        }
        else if($ticket[typereq] == 2){$ctk = "newrequestaci";$typereq = 'Izin Akses Data';}
        else if($ticket[typereq] == 4){$ctk = "newrequestacp";$typereq = 'Penutupan Akun';
            $database->query("update people set statusid = 'Nonactive' where id = '$ticket[idpeople]'");
            $database->query("update assets set userid = '0',statusid='4' where id = '$ticket[assetid]'");
        }        

        //lootping email
        $expakas = explode(';',$ticket['akunakses']);
        $jmlkas = count($expakas);
        $num = 1;
        for($i=0;$i<$jmlkas;$i++){
            foreach($database->query("select idakunminta,namaakunminta,peopleid from akunminta where idakunminta = '$expakas[$i]'") as $coc){}

            $depts =  getTableFiltered("clients","id","$ticket[clientid]");
                foreach($depts as $dept);
                $dateid = date('ymd');
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
                
                
                if($noBlnj<> $thn.$bln.$tg){ $noUrutj=1;} else { $noUrutj++;}

                if($dept['inisial_client'] != ''){ $depc = $dept['inisial_client'];} else {$depc = 'OTH';}
                
                $noticket= $depc."/".$thn."".$bln."".$tg."/".sprintf("%03s", $noUrutj);

                $ticketids = $database->insert("tickets", [
                    'ticket' =>  $noticket,
                    'departmentid' => 1,
                    'clientid' => $ticket['clientid'],
                    'userid' => $ticket['idpeople'],
                    'adminid' => $coc['peopleid'],
                    'projectid' => 0,
                    'email' => $ticket['email'],
                    //'email' => 'abdullah.fanani@eratex.co.id',
                    'subject' => "Form Request ".$typereq." ".$coc['namaakunminta'],
                    'status' => 'Assigned',
                    'priority' => 'Normal',
                    'timestamp' => date('Y-m-d H:i:s'),
                    'timespent' => 0,
                    'reported' => $liu['name'].' Approve Request',
                    'reported_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                    'typeasset' => 0,
                    'people_dtl_request_id' => $data[id],
                    'typeticket' => 3,
                    'approvedby' => $liu['id'],
                    'approvedate' => date('Y-m-d H:i:s'),
                    'slaid' => 1
                ]);

                $lastid = $database->insert("tickets_replies", [
                    "ticketid" => $ticketids,
                    "peopleid" => $ticket['idpeople'],
                    "message" => "Approve IT, ".$ticket['itnotes'],
                    "timestamp" => date('Y-m-d H:i:s')
                ]);

                 $em[]=$coc['peopleid'];
        }
        
        $emm = implode(';', $em);    
        $expem = explode(';',$emm);
        foreach($expem as $key => $value){
            foreach($database->query("select email from people where id = '$value'") as $pemail){}
            $sp[]=$pemail['email']; 
        
        }
        $spp = implode(';',$sp);
        //echo $spp;
        //die();
        Notification::blastrequest($data['id'], 13,'Open',$spp);
    	return "20";
    }

}

?>
