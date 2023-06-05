<?php

class Ticket extends App {

    // TICKETS


    public static function add($data) {
        global $database;
        global $liu;
        $dateid = date("ymd");
        $seldepart = $database->query("select inisial_client from clients where id = '$data[depontrack]'");
             foreach ($seldepart as $depart) {}
                $inidept = $depart['inisial_client'];

                //$idgen=$con->nourut('tickets', 'ticket', $dateid, $inidept, date('Y-m-d'));

                foreach($database->query("select count(id) jumreq from tickets where DATE(timestamp) = DATE(now())") as $jumreq){}
                $jumurut = $jumreq['jumreq']+1;
                $idgen = $inidept."/".$dateid."/".sprintf("%03s", $jumurut);
        
        $lastid = $database->insert("tickets", [
            'ticket' => $idgen,
            'departmentid' => 1,
            'clientid' => $data['depontrack'],
            'userid' => $data['user_id'],
            'adminid' => $data['adminid'],
            'assetid' => $data['assetid'],
            'projectid' => 0,
            'email' => $data['email'],
            'subject' => $data['subject'],
            'status' => $data['status'],
            'priority' => $data['priority'],
            'timestamp' => date('Y-m-d H:i:s'),
            'timespent' => 0,
            'reported' => $data['report_name'],
            'ticketroom' => $data['ticketroom'],
            'typeasset' => 1,
            'reported_email' => $data['report_email'],
            'typeticket' => 1,
            'notes' => 'Inputed by IT',
            'ccs' => ''
        ]);
        
        $lastid2 = $database->insert("tickets_replies", [
            'ticketid' => $lastid,
            'peopleid' => $data['user_id'],
            'message' => $data['remark'],
            'timestamp' => date('Y-m-d H:i:s'),
            'typereplies' => 1,
            'statusreplies' => $data['status']
        ]);
       
       if ($lastid == "0") { return "11"; } else {return "10";}
    }

    public static function addPublicTicket($postdata) {
        global $database;

        $data['departmentid'] = $postdata['departmentid'];
        $data['clientid'] = 0;
        $data['adminid'] = 0;
        $data['userid'] = 0;
        $data['assetid'] = 0;
        $data['projectid'] = 0;
        $data['ccs'] = "";

        $data['email'] = $postdata['email'];
        $data['subject'] = $postdata['subject'];
        $data['priority'] = $postdata['priority'];
        $data['message'] = $postdata['message'];


        // match user based on email address
        $data['userid'] = $database->get("people", "id", [ "AND" => [ "type" => "user", "email" => strtolower($data['email']) ] ]); if($data['userid'] == "") $data['userid'] = 0;

        // if we do not know the user we do not know the client
        if($data['userid'] == 0) $data['clientid'] = 0;

        // if we know the user try to assign some data to the ticket
        else {
            // get the user's assigned client
            $data['clientid'] = $database->get("people", "clientid", [ "AND" => [ "type" => "user", "email" => $data['email'] ] ]);

            // get the assigned asset ( it will return only the first one if there are more than one assigned )
            $asset = $database->get("assets","*",[ "userid" => $data['userid'] ]);
            if(!empty($asset['id'])) {
                // assign the asset if any found
                $data['assetid'] = $asset['id'];
                // assign the admin for that asset if found
                $data['adminid'] = $asset['adminid'];
            }

        }

        return self::add($data);
    }

    public static function addReply($data) {
        global $database;
        if($data['adminid'] != "0") $peopleid = $data['adminid']; else $peopleid = $data['userid'];
        if($data['message'] == "") $messageReply = $data['status']; else $messageReply = $data['message'];
        // insert reply
        $lastid = $database->insert("tickets_replies", [
            "ticketid" => $data['ticketid'],
            "peopleid" => $peopleid,
            "message" => $messageReply,
            "timestamp" => date('Y-m-d H:i:s'),
            "typereplies" => 2,
            "statusreplies" => $data['status'],
            "ipaddress" => gethostbyaddr($_SERVER['REMOTE_ADDR']),
        ]);

        if(isset($_FILES["file"]["name"][0])) {
            if(!empty($_FILES["file"]["name"][0])) {
                $file_data = array();
                $file_data['clientid'] = 0;
                $file_data['projectid'] = 0;
                $file_data['assetid'] = 0;
                $file_data['ticketreplyid'] = $lastid;
                File::upload($file_data,$_FILES);
            }
        }

        if($data['idakunmintaticket'] == '2'){
            if($data['emailinputnow'] == ''){
                $database->update("people", [
                    "email" => $data['emailinput'],
                ], [ "id" => $data['useridpeople'] ]);

                $database->update("tickets", [
                    "email" => $data['emailinput'],
                ], [ "people_dtl_request_id" => $data['peopledtlreqid'] ]);
            }
        }
        
        // update ticket status
        if(isset($data['status'])) self::updateStatus($data['ticketid'],$data['status'],$messageReply);


        if ($lastid == "0") {
            return "11";
        } else {
            // user notification
            if(isset($data['notification']) && $data['status'] != 'Finished') { 
                //jika status finished maka akan mengirim redaksional email finish
                // if($data['status'] == 'Finished'){ 
                //     if($data['notification'] == true){ Notification::ticketUser($data['ticketid'],$data['message'],16,$data['status']); }
                // } else {
                //     if($data['notification'] == true){ Notification::ticketUser($data['ticketid'],$data['message'],2,$data['status']); }    
                // }
                include "./phpmailer/pushemailticketuser.php";
            }    

            // admin notification
            // if(getSingleValue("people","type",$peopleid) == "user") Notification::ticketStaff($data['ticketid'],$data['message'],8);

      //       // send admin notification if guest ticket
            // if($peopleid == "0") Notification::ticketStaff($data['ticketid'],$data['message'],8);

            logSystem("Ticket Reply Added - ID: " . $lastid);
            return "10"; 
        }
    }

    public static function edit($data) {
        global $database;
        global $liu;
        if(empty($data['ccs'])) $ccs = ""; else $ccs = serialize($data['ccs']);
        if(empty($data['approvedby'])) $approvedby = $data['approvedid']; else $approvedby = $data['approvedby'];
        if(empty($data['approvedate'])) $approvedate = date('Y-m-d H:i:s'); else $approvedate = $data['approvedate'];
        if($data['adminid'] == 0){$status = "Open";} else {$status = $data['status'];}

        foreach ($data['adminid'] as $ccl => $value) {
            $v[]=$value;
        }
        $g = implode(";", $v);

        if($data['adminid'] == '') {
            $database->update("tickets", [
             //   "departmentid" => $data['departmentid'],
             //   "clientid" => $data['clientid'],
             //   "userid" => $data['userid'],
                "adminid" => $g,
             //   "assetid" => $data['assetid'],
                "projectid" => $data['projectid'],
             //   "email" => $data['email'],
                "subject" => $data['subject'],
                "status" => $status,
                "priority" => $data['priority'],
                "ccs" => $ccs,
                "modifydate" => date('Y-m-d H:i:s'),
                "slaid" => $data['slaid'],
            ], [ "id" => $data['id'] ]);
        } else {

            $database->update("tickets", [
                "adminid" => $g,
                "projectid" => $data['projectid'],
                "subject" => $data['subject'],
                "status" => $status,
                "priority" => $data['priority'],
                "ccs" => $ccs,
                "modifydate" => date('Y-m-d H:i:s'),
                "approvedby" => $approvedby,
                "approvedate" => $approvedate,
                "slaid" => $data['slaid'],
            ], [ "id" => $data['id'] ]);
        }

        if($data['adminid'] != ""){
           // Notification::ticketassigned($data['id'],'Ticket Assigned',14);
           include "./phpmailer/pushemailticketassignts.php";
        }
        
        logSystem("Ticket Edited - ID: " . $data['id']);
        return "20";
        }

    public static function updateStatus($id,$status,$messageReply) {
        global $database;
        global $liu;

        foreach($database->query("select status,idakunmintaticket,people_dtl_request_id,typeticket,id from tickets where id = '$id'") as $cekold){}
        $datenow = date('Y-m-d H:i:s');
//jika ada hold
        if($cekold['status'] == 'Hold'){
            $database->query("update ticket_holdtime set closed_date = '$datenow' where ticketid = '$id' ORDER BY id DESC limit 1");
        }
// end hold ======  
        if($status == 'Finished'){
            $database->update("tickets", [
            "status" => $status,
            "modifydate" => date('Y-m-d H:i:s'),
            "finishdate" => date('Y-m-d H:i:s'),
            "finishby" => $liu['id'],
            ], [ "id" => $id ]);    

            // cek parent akunminta
            if($cekold['people_dtl_request_id'] != '' && $cekold['typeticket'] == 3){
                foreach($database->query("select idakunminta from akunminta where parentakunminta = '$cekold[idakunmintaticket]' and statusakunminta = 1") as $pak){
                    
                    foreach($database->query("select id,adminid from tickets where idakunmintaticket = $pak[idakunminta] and people_dtl_request_id = $cekold[people_dtl_request_id]") as $cpa){
                            $epp[]=$cpa['adminid']."_".$cpa['id'];

                            $database->update("tickets", [
                            "modifydate" => date('Y-m-d H:i:s'),
                            ], [ "id" => $cpa['id'] ]);
                    }
                }
            // end parent akunminta
            $eppa =implode("|",$epp);
            $jumepp = count($epp);
                if($jumepp > 0){
                    include "./phpmailer/pushemailticketparentreminder.php";
                }
                
                include "./phpmailer/pushemailticketuser2.php";
            }  else {
                $jumepp = 0;
                include "./phpmailer/pushemailticketuser2.php";
            }

        } else if($status == 'Hold'){
            $database->update("tickets", [
            "status" => $status,
            "modifydate" => date('Y-m-d H:i:s'),
            "adminid" => $liu['id'],
            ], [ "id" => $id ]);

            $database->insert("ticket_holdtime",[
                "ticketid" => $id,
                "created_date" => date('Y-m-d H:i:s'),
                "remark" => $messageReply,
            ]);   
        } else {
            $database->update("tickets", [
            "status" => $status,
            "modifydate" => date('Y-m-d H:i:s'),
            ], [ "id" => $id ]);
        }
        
        logSystem("Ticket Status Update - ID: " . $id);
        return "20";
    }

    public static function assignTo($id,$adminid,$beforeid) {
        global $database;
        $database->update("tickets", [
            "adminid" => $adminid
        ], [ "id" => $id ]);

        $database->insert("ticket_assign_history", [
            "ticketid" => $id,
            "peopleid" => $adminid,
            "peopleidreport" => $adminid,
            "peopleidbefore" => $beforeid,
            "created_date" => date('Y-m-d H:i:s'),
        ]);
        logSystem("Ticket Assigned - ID: " . $id);
        return "20";
    }

    public static function updateNotes($data) {
        global $database;
        $database->update("tickets", [
            "notes" => $data['notes'],
        ], [ "id" => $data['id'] ]);
        logSystem("Ticket Notes Update - ID: " . $data['id']);
        return "20";
    }

    public static function mentionPeople($data) {
        global $database;
        foreach($database->query("select mentionid from tickets where id = '$data[id]'") as $mtik){}
        $newdiscuss = $mtik[mentionid].$data[mentionid].";";
        $database->update("tickets", [
            "mentionid" => $newdiscuss,
        ], [ "id" => $data['id'] ]);
        //echo $data['id'];
        include "./phpmailer/pushemailmention.php";
       
        logSystem("Ticket Discuss Update - ID: " . $data['id']);
        return "20";
    }
    
    public static function delete($id) {
        global $database;
        File::delete_ticket_files($id);

        $database->delete("tickets", [ "id" => $id ]);
        $database->delete("tickets_replies", [ "ticketid" => $id ]);
        logSystem("Ticket Deleted - ID: " . $id);
        return "30";
    }

    public static function lastReply($id) {
        global $database;
        $maxdate = $database->max("tickets_replies", "timestamp", ["ticketid" => $id]);
        $timestamp = strtotime($maxdate);
        return smartDate($timestamp);
    }


    public static function lastReplyText($ticketid) {
        global $database;
        $admins = $database->select("people", "id", ["type" => "admin"]);

        $lastReplyText = $database->get("tickets_replies", "*",
            [ "AND" => [ "ticketid" => $ticketid, "peopleid" => $admins ], "ORDER" => [ "timestamp" => "DESC" ] ]
        );

        if($lastReplyText['message']) return $lastReplyText['message']; else return "";

    }

    public static function lastReplyStamp($id) {
        global $database;
        $maxdate = $database->max("tickets_replies", "timestamp", ["ticketid" => $id]);
        $timestamp = $maxdate . " ";
        return $timestamp;
    }

    public static function extractEmail($string) { //extract first email address from string
        $pattern = '/[\\w\\.\\-+=*_]*@[\\w\\.\\-+=*_]*/';
        preg_match($pattern, $string, $matches);
        return $matches[0];
    }

    public static function emailToTicket($to,$from,$subject,$body,$importance,$ccs){
        
        global $database;

        // initialize arrays
        $data = array();
        $data['ccs'] = array();

        // extracs ccs, if any
        if(!empty($ccs)) {
            foreach($ccs as $cc) {
                $ccemail = self::extractEmail($cc);
                array_push($data['ccs'],$ccemail);
            }
        }

        // extract from email address
        $data['email'] = self::extractEmail($from);

        // match user based on email address
        $data['userid'] = $database->get("people", "id", [ "AND" => [ "type" => "user", "email" => $data['email'] ] ]); if($data['userid'] == "") $data['userid'] = 0;

        // match admin based on email address
        $data['adminid'] = $database->get("people", "id", [ "AND" => [ "type" => "admin", "email" => $data['email'] ] ]); if($data['adminid'] == "") $data['adminid'] = 0;

        // if email from user reopen ticket, if email from admin set status to answered
        if($data['adminid'] != 0) $data['status'] = "Answered"; else $data['status'] = "Reopened";

        // we do not know the asset at this time
        $data['assetid'] = 0;

        // if we do not know the user we do not know the client
        if($data['userid'] == 0) $data['clientid'] = 0;

        // if we know the user try to assign some data to the ticket
        else {
            // get the user's assigned client
            $data['clientid'] = $database->get("people", "clientid", [ "AND" => [ "type" => "user", "email" => $data['email'] ] ]);

            // get the assigned asset ( it will return only the first one if there are more than one assigned )
            $asset = $database->get("assets","*",[ "userid" => $data['userid'] ]);
            if(!empty($asset['id'])) {
                // assign the asset if any found
                $data['assetid'] = $asset['id'];
                // assign the admin for that asset if found
                $data['adminid'] = $asset['adminid'];
            }

        }


        $data['subject'] = $subject;
        $data['priority'] = $importance;
        $data['message'] = $body;
        $data['departmentid'] = getConfigValue("tickets_defaultdepartment");
        $data['projectid'] = 0;

        foreach($to as $item) {
            $toaddr = self::extractEmail($item);
            $department = $database->get("tickets_departments", "*", [ "email[~]" => $toaddr ] );
            if(!empty($department)) { $data['departmentid'] = $department['id']; break; }
        }

        // select all the tickets
        $tickets = $database->select("tickets", ["id", "ticket"]);
        // see if we can get a ticket match
        foreach($tickets as $ticket) {

            if (strpos($subject,$ticket['ticket']) !== false) { // match found, will add as ticket reply
                $data['ticketid'] = $ticket['id'];

                // do not send user notification if user sent the reply
                $data['notification'] = false;

                // if admin made the reply send notification to user
                if ($data['status'] == "Answered") $data['notification'] = true;

                break; // exit the loop prematurely if we find a match

            } else { // no match, new ticket will be created
                $data['ticketid'] = 0;
                if(getConfigValue("tickets_notification") == "false") $data['notification'] = false;
                if(getConfigValue("tickets_notification") == "true") $data['notification'] = true;

            }

        }

        // in case we have an empty ticket table
        if(empty($tickets)) { $data['ticketid'] = 0; $data['notification'] = getConfigValue("tickets_notification"); }

        if($data['ticketid'] == 0) {
            // no match, create new ticket
            self::add($data);
        } else {
            // match found, add reply to matched ticket
            self::addReply($data);
        }

        // return last ticket reply id, required by cron job
        return $database->max("tickets_replies","id");
    }



    // ESCALATION RULES

    public static function addRule($data) {
        global $database;
        if(isset($data['act_notifyadmins'])) $act_notifyadmins = 1; else $act_notifyadmins = 0;
        if(isset($data['act_addreply'])) $act_addreply = 1; else $act_addreply = 0;
        if(empty($data['cond_status'])) $cond_status = ""; else $cond_status = serialize($data['cond_status']);
        if(empty($data['cond_priority'])) $cond_priority = ""; else $cond_priority = serialize($data['cond_priority']);

        if($data['cond_datetime_date'] == "0000-00-00") $cond_datetime = $data['cond_datetime_date'] . " " . $data['cond_datetime_time'];
        else $cond_datetime = dateDb($data['cond_datetime_date']) . " " . $data['cond_datetime_time'];

        $lastid = $database->insert("tickets_rules", [
            "ticketid" => $data['ticketid'],
            "executed" => 0,
            "name" => $data['name'],
            "cond_status" => $cond_status,
            "cond_priority" => $cond_priority,
            "cond_timeelapsed" => $data['cond_timeelapsed'],
            "cond_datetime" => $cond_datetime,
            "act_status" => $data['act_status'],
            "act_priority" => $data['act_priority'],
            "act_assignto" => $data['act_assignto'],
            "act_notifyadmins" => $act_notifyadmins,
            "act_addreply" => $act_addreply,
            "reply" => $data['reply']
        ]);
        if ($lastid == "0") { return "11"; } else { logSystem("Escalation Rule Added - ID: " . $lastid); return "10"; }
        }


    public static function editRule($data) {
        global $database;
        if(isset($data['act_notifyadmins'])) $act_notifyadmins = 1; else $act_notifyadmins = 0;
        if(isset($data['act_addreply'])) $act_addreply = 1; else $act_addreply = 0;
        if(empty($data['cond_status'])) $cond_status = ""; else $cond_status = serialize($data['cond_status']);
        if(empty($data['cond_priority'])) $cond_priority = ""; else $cond_priority = serialize($data['cond_priority']);

        if($data['cond_datetime_date'] == "0000-00-00") $cond_datetime = $data['cond_datetime_date'] . " " . $data['cond_datetime_time'];
        else $cond_datetime = dateDb($data['cond_datetime_date']) . " " . $data['cond_datetime_time'];

        $database->update("tickets_rules", [
            "ticketid" => $data['ticketid'],
            "name" => $data['name'],
            "cond_status" => $cond_status,
            "cond_priority" => $cond_priority,
            "cond_timeelapsed" => $data['cond_timeelapsed'],
            "cond_datetime" => $cond_datetime,
            "act_status" => $data['act_status'],
            "act_priority" => $data['act_priority'],
            "act_assignto" => $data['act_assignto'],
            "act_notifyadmins" => $act_notifyadmins,
            "act_addreply" => $act_addreply,
            "reply" => $data['reply']
        ], [ "id" => $data['id'] ]);
        logSystem("Escalation Rule Edited - ID: " . $data['id']);
        return "20";
        }



    public static function deleteRule($id) {
        global $database;
        $database->delete("tickets_rules", [ "id" => $id ]);
        logSystem("Esclation Rule Deleted - ID: " . $id);
        return "30";
        }


    public static function processRules() {
        global $database;
        $generalRules = getTableFiltered("tickets_rules","ticketid","0");
        $ticketRules = $database->select("tickets_rules", "*", [ "AND" => [ "ticketid[!]" => 0, "executed" => 0 ] ]);


        foreach ($generalRules as $rule) {
            if($rule['cond_status'] == "") $cond_status = ["Open","In Progress","Answered","Reopened","Closed"]; else $cond_status = unserialize($rule['cond_status']);
            if($rule['cond_priority'] == "") $cond_priority = ["Low","Normal","High"]; else $cond_priority = unserialize($rule['cond_priority']);

            $tickets = $database->select("tickets", "*", [ "AND" => [ "status" => $cond_status, "priority" => $cond_priority ] ]);

            foreach ($tickets as $ticket) {
                $lastreply = $database->max("tickets_replies", "timestamp", ["ticketid" => $ticket['id']]);

                $lastreply = strtotime($lastreply) / 60;
                $now = strtotime("now") / 60;
                $difference = $now - $lastreply;

                if ($rule['cond_timeelapsed'] == "" or $difference > $rule['cond_timeelapsed']) {
                    $message = "";
                    if($rule['act_status'] != "0") { $database->update("tickets", [ "status" => $rule['act_status'] ], [ "id" => $ticket['id'] ]); $message .= "Status changed to <b>" . $rule['act_status'] . "</b><br>"; }
                    if($rule['act_priority'] != "0") { $database->update("tickets", [ "priority" => $rule['act_priority'] ], [ "id" => $ticket['id'] ]); $message .= "Proirity changed to <b>" . $rule['act_priority'] . "</b><br>"; }
                    if($rule['act_assignto'] != "0") { $database->update("tickets", [ "adminid" => $rule['act_assignto'] ], [ "id" => $ticket['id'] ]); $message .= "Assigned to <b>" . getSingleValue("people","name",$rule['act_assignto']) . "</b><br>"; }
                    if($rule['act_addreply'] == "1") { $message .= "Reply added<br>";
                        $data = array();
                        $data['adminid'] = -1;
                        $data['ticketid'] = $ticket['id'];
                        $data['message'] = $rule['reply'];
                        $data['notification'] = true;
                        self::addReply($data);
                    }
                    if($rule['act_notifyadmins'] == "1") { Notification::ticketStaff($ticket['id'],$message,9); }
                }

            }

        }

        foreach ($ticketRules as $rule) {
            if($rule['cond_status'] == "") $cond_status = ["Open","In Progress","Answered","Reopened","Closed"]; else $cond_status = unserialize($rule['cond_status']);
            if($rule['cond_priority'] == "") $cond_priority = ["Low","Normal","High"]; else $cond_priority = unserialize($rule['cond_priority']);


            $ticket = $database->get("tickets","*",[ "id" => $rule['ticketid'] ]);

            if(in_array($ticket['status'],$cond_status) && in_array($ticket['priority'],$cond_priority)) {



                $processat = new DateTime($rule['cond_datetime']);
                $now = new DateTime(date("Y-m-d H:i:s"));


                if ($now->format('U') >= $processat->format('U')) {
                    $message = "";
                    if($rule['act_status'] != "0") { $database->update("tickets", [ "status" => $rule['act_status'] ], [ "id" => $ticket['id'] ]); $message .= "Status changed to <b>" . $rule['act_status'] . "</b><br>"; }
                    if($rule['act_priority'] != "0") { $database->update("tickets", [ "priority" => $rule['act_priority'] ], [ "id" => $ticket['id'] ]); $message .= "Proirity changed to <b>" . $rule['act_priority'] . "</b><br>"; }
                    if($rule['act_assignto'] != "0") { $database->update("tickets", [ "adminid" => $rule['act_assignto'] ], [ "id" => $ticket['id'] ]); $message .= "Assigned to <b>" . getSingleValue("people","name",$rule['act_assignto']) . "</b><br>"; }
                    if($rule['act_addreply'] == "1") { $message .= "Reply Added<br>";
                        $data = array();
                        $data['adminid'] = -1;
                        $data['ticketid'] = $ticket['id'];
                        $data['message'] = $rule['reply'];
                        $data['notification'] = true;
                        self::addReply($data);
                    }
                    if($rule['act_notifyadmins'] == "1") { Notification::ticketStaff($ticket['id'],$message,9); }

                    $database->update("tickets_rules", [ "executed" => 1 ], [ "id" => $rule['id'] ]);
                }
            }
        }

    }

    public static function autoClose() {
        global $database;
        $autoclose = getConfigValue("auto_close_tickets") * 3600;

        if($autoclose > 0) {
            $tickets = $database->select("tickets", "*", [ "status" => "Answered" ]);

            foreach ($tickets as $ticket) {
                $lastreply = $database->max("tickets_replies", "timestamp", ["ticketid" => $ticket['id']]);
                $lastreply = strtotime($lastreply);
                $now = strtotime("now");

                $difference = $now - $lastreply;

                if ($difference > $autoclose) {
                    self::updateStatus($ticket['id'],"Closed");
                    if( getConfigValue("auto_close_tickets_notify") == "true" ) Notification::ticketUser($ticket['id'],"",10);
                }
            }
        }
    }

  


}

?>
