<?php

class Notification extends App {


    public static function ticketUser($ticketid,$reply,$templateid,$statusticket) { //send ticket notification
        global $database;
    	$template = getRowById("notificationtemplates",$templateid);
    	$ticket = getRowById("tickets",$ticketid);
    	$ccs = array(); if($ticket['ccs'] != "") $ccs = unserialize($ticket['ccs']);

        $client = __('Unassigned');
        $department = __('Unassigned');

        if($ticket['clientid'] != 0) $client = getSingleValue("clients","name",$ticket['clientid']);
        if($ticket['departmentid'] != 0) $department = getSingleValue("tickets_departments","name",$ticket['departmentid']);

    	if($ticket['userid'] == 0) $contact = $ticket['email']; else $contact = getSingleValue("people","name",$ticket['userid']);

    	$search = array('{ticketid}', '{status}', '{subject}', '{contact}', '{message}', '{company}', '{appurl}', '{client}', '{department}','{idticket}');
    	$replace = array($ticket['ticket'], $ticket['status'], $ticket['subject'], $contact, $reply, getConfigValue("company_name"), getConfigValue("app_url"), $client, $department, $ticketid);

    	$subject = str_replace($search, $replace, $template['subject'])." : ".$statusticket;
    	$message = str_replace($search, $replace, $template['message']);

        // attachments
        $replyid = $database->max("tickets_replies", "id", ["ticketid" => $ticketid]);
        $attachments = $database->select("files", "id", ["ticketreplyid" => $replyid]);

        $smail = $ticket['email'].';'.$ticket['reported_email'];
    	sendEmail($smail,$subject,$message,$ticket['clientid'],$ticket['userid'],$ccs,$attachments);

        sendFCM($ticket['userid'], "Ticket Notification", $subject);
    }


    public static function ticketStaff($ticketid,$reply,$templateid) { //send ticket notification
        global $database;
    	$template = getRowById("notificationtemplates",$templateid);
    	$ticket = getRowById("tickets",$ticketid);

        $client = __('Unassigned');
        $department = __('Unassigned');

        if($ticket['clientid'] != 0) $client = getSingleValue("clients","name",$ticket['clientid']);
        if($ticket['departmentid'] != 0) $department = getSingleValue("tickets_departments","name",$ticket['departmentid']);

        if($ticket['userid'] == 0) $contact = $ticket['email']; else $contact = getSingleValue("people","name",$ticket['userid']);

    	$search = array('{ticketid}', '{status}', '{subject}', '{contact}', '{message}', '{company}', '{appurl}', '{client}', '{department}');
    	$replace = array($ticket['ticket'], $ticket['status'], $ticket['subject'], $contact, $reply, getConfigValue("company_name"), getConfigValue("app_url"), $client, $department);

    	$subject = str_replace($search, $replace, $template['subject']);
    	$message = str_replace($search, $replace, $template['message']);

        // attachments
        $replyid = $database->max("tickets_replies", "id", ["ticketid" => $ticketid]);
        $attachments = $database->select("files", "id", ["ticketreplyid" => $replyid]);

    	$admins = getTableFiltered("people","type","admin","ticketsnotification","1");
    	foreach($admins as $admin) {
    		sendEmail($admin['email'],$subject,$message,0,$admin['id'],$ccs=array(),$attachments);

            sendFCM($admin['id'], "Ticket Notification", $subject);
    	}
    }


    public static function newUser($peopleid,$password) { //send new user/admin notification
    	global $database;
    	$template = getRowById("notificationtemplates",3);
    	$people = getRowById("people",$peopleid);

    	$search = array('{contact}', '{email}', '{password}', '{company}', '{appurl}');
    	$replace = array($people['name'], $people['email'], $password, getConfigValue("company_name"), getConfigValue("app_url"));

    	$subject = str_replace($search, $replace, $template['subject']);
    	$message = str_replace($search, $replace, $template['message']);

    	sendEmail($people['email'],$subject,$message,$people['clientid'],$people['id']);
    }


    public static function passwordReset($peopleid,$resetlink) { //send password reset link
    	global $database;
    	$template = getRowById("notificationtemplates",5);
    	$people = getRowById("people",$peopleid);

    	$search = array('{contact}', '{resetlink}', '{company}', '{appurl}');
    	$replace = array($people['name'], $resetlink, getConfigValue("company_name"), getConfigValue("app_url"));

    	$subject = str_replace($search, $replace, $template['subject']);
    	$message = str_replace($search, $replace, $template['message']);

    	sendEmail($people['email'],$subject,$message,$people['clientid'],$people['id']);
    }


    public static function monitoringEmail($peopleid,$hostid,$hostinfo,$status) { //send monitoting email alert
    	global $database;
    	$template = getRowById("notificationtemplates",6);
    	$people = getRowById("people",$peopleid);
    	$host = getRowById("hosts",$hostid);

    	$search = array('{hostinfo}', '{status}', '{contact}', '{company}', '{appurl}');
    	$replace = array($hostinfo, $status, $people['name'], getConfigValue("company_name"), getConfigValue("app_url"));

    	$subject = str_replace($search, $replace, $template['subject']);
    	$message = str_replace($search, $replace, $template['message']);

    	sendEmail($people['email'],$subject,$message,$host['clientid'],$people['id']);
    }


    public static function monitoringSMS($peopleid,$hostid,$hostinfo,$status) { //send monitoring SMS alert
    	global $database;
    	$template = getRowById("notificationtemplates",6);
    	$people = getRowById("people",$peopleid);
    	$host = getRowById("hosts",$hostid);

    	$search = array('{hostinfo}', '{status}', '{contact}', '{company}', '{appurl}');
    	$replace = array($hostinfo, $status, $people['name'], getConfigValue("company_name"), getConfigValue("app_url"));

    	$sms = str_replace($search, $replace, $template['sms']);

    	sendSMS($people['mobile'],$sms,$host['clientid'],$people['id']);
    }

    public static function formassets($ticketid,$reply,$templateid,$statusticket) { //send ticket notification
        global $database;
        $template = getRowById("notificationtemplates",$templateid);
        $ticket = getRowById("tickets",$ticketid);
        $emailassign = getRowById("people",$ticketid['adminid']);

        $ccs = array(); if($ticket['ccs'] != "") $ccs = unserialize($ticket['ccs']);

        $client = __('Unassigned');
        $department = __('Unassigned');

        if($ticket['clientid'] != 0) $client = getSingleValue("clients","name",$ticket['clientid']);
        if($ticket['departmentid'] != 0) $department = getSingleValue("tickets_departments","name",$ticket['departmentid']);

        if($ticket['userid'] == 0) $contact = $ticket['email']; else $contact = getSingleValue("people","name",$ticket['userid']);

        $search = array('{ticketid}', '{status}', '{subject}', '{contact}', '{message}', '{company}', '{appurl}', '{client}', '{department}');
        $replace = array($ticket['ticket'], $ticket['status'], $ticket['subject'], $contact, $reply, getConfigValue("company_name"), getConfigValue("app_url"), $client, $department);

        $subject = str_replace($search, $replace, $template['subject'])." : ".$statusticket;
        $message = str_replace($search, $replace, $template['message']);

        // attachments
        $replyid = $database->max("tickets_replies", "id", ["ticketid" => $ticketid]);
        $attachments = $database->select("files", "id", ["ticketreplyid" => $replyid]);
        
        sendEmail($emailassign['email'],$subject,$message,$ticket['clientid'],$ticket['userid'],$ccs,$attachments);
        //sendEmail("abdullah.fanani@eratex.co.id",$subject,$message,$ticket['clientid'],$ticket['userid'],$ccs,$attachments);

        sendFCM($ticket['userid'], "Ticket Notification", $subject);
    }

    public static function formassets2($ticketid,$reply,$templateid,$statusticket) { //send ticket notification
        global $database;
        $template = getRowById("notificationtemplates",$templateid);
        $ticket = getRowById("tickets",$ticketid);
        $emailassign = getRowById("people",$ticketid['adminid']);

        $ccs = array(); if($ticket['ccs'] != "") $ccs = unserialize($ticket['ccs']);

        $client = __('Unassigned');
        $department = __('Unassigned');

        if($ticket['clientid'] != 0) $client = getSingleValue("clients","name",$ticket['clientid']);
        if($ticket['departmentid'] != 0) $department = getSingleValue("tickets_departments","name",$ticket['departmentid']);

        if($ticket['userid'] == 0) $contact = $ticket['email']; else $contact = getSingleValue("people","name",$ticket['userid']);

        $search = array('{ticketid}', '{status}', '{subject}', '{contact}', '{message}', '{company}', '{appurl}', '{client}', '{department}');
        $replace = array($ticket['ticket'], $ticket['status'], $ticket['subject'], $contact, $reply, getConfigValue("company_name"), getConfigValue("app_url"), $client, $department);

        $subject = str_replace($search, $replace, $template['subject'])." : ".$statusticket;
        $message = str_replace($search, $replace, $template['message']);

        // attachments
        $replyid = $database->max("tickets_replies", "id", ["ticketid" => $ticketid]);
        $attachments = $database->select("files", "id", ["ticketreplyid" => $replyid]);
        
        sendEmail($emailassign['email'],$subject,$message,$ticket['clientid'],$ticket['userid'],$ccs,$attachments);
        //sendEmail("abdullah.fanani@eratex.co.id",$subject,$message,$ticket['clientid'],$ticket['userid'],$ccs,$attachments);

        sendFCM($ticket['userid'], "Ticket Notification", $subject);
    }

    public static function blastrequest($peopleid,$templateid,$statusticket,$spemail) { //send ticket notification
        global $database;
        $template = getRowById("notificationtemplates",$templateid);
        $tickets = $database->query("select a.name as namepeople, c.name as namedept, b.title,b.mobile,b.statusid,b.id as iddtl,b.typereq,a.email,b.alasanbutuh,b.tgl_butuh1,b.tgl_butuh2,b.akunakses,b.websiteakses,b.hasilpengecekanaset,b.assetid,d.headpeopleid,headstatus,DATE(headappdate) as headappdate,d.itpeopleid,itstatus,DATE(itappdate) as itappdate,itnotes,b.locationid,DATE(b.created_date) as created_date_dtl,e.name as locationname,a.id as idpeople,b.clientid from people a join people_dtl_request b on a.id=b.peopleid join clients c on b.clientid=c.id join people_dtl_approve d on b.id=d.peopledtlid join locations e on b.locationid=e.id
where b.id = '$peopleid' ORDER BY b.id DESC");
        foreach($tickets as $ticket){}

        if($ticket[typereq] == 1){$ctk = "newrequestacc";$typereq = 'Permintaan AKun';}else if($ticket[typereq] == 2){$ctk = "newrequestaci";$typereq = 'Izin Akses Data';}else if($ticket[typereq] == 4){$ctk = "newrequestacp";$typereq = 'Penutupan Akun';}

        $ccs = array(); if($ticket['ccs'] != "") $ccs = unserialize($ticket['ccs']);

        $client = __('Unassigned');
        $department = __('Unassigned')
;
        if($ticket['clientid'] != 0) $client = getSingleValue("clients","name",$ticket['clientid']);
        if($ticket['departmentid'] != 0) $department = getSingleValue("tickets_departments","name",$ticket['departmentid']);

        if($ticket['userid'] == 0) $contact = $ticket['email']; else $contact = getSingleValue("people","name",$ticket['userid']);

        if($ticket[typereq] == 1){$ctk = "newrequestacc";}else if($ticket[typereq] == 2){$ctk = "newrequestaci";}else if($ticket[typereq] == 4){$ctk = "newrequestacp";}
        
        $search = array('{ticketid}', '{status}', '{subject}', '{contact}', '{message}', '{company}', '{appurl}', '{client}', '{department}');
        $replace = array($noticket, 'Open',$typereq , $contact, $reply, getConfigValue("company_name"), getConfigValue("app_url"), $client, $department);

        $subject = str_replace($search, $replace, $template['subject'])." : ".$statusticket;

        //send email =========================================================
                    
            $message = "<p>Hello IT Team,<br><br>A new ticket has been created for your task.<br>Ticket ID: <b>".$noticket."</b><br><br> 
            Name : ".$ticket['namepeople']."<br>
            Dept : ".$ticket['namedept']."-".$ticket['locationname']."<br>
            Jabatan : ".$ticket['title']."<br>
            Please check your ticket task in <a href='http://192.168.31.25/ontrack'>Erassets</a><br><br>
            View Form Request Follow this <a href='http://192.168.31.25/eraticket/cetak.php?page=$ctk&id=$peopleid'>Link</a>
            <br><br>.<br><br>Best regards,<br>Eratex IT Assets - Eratex Djaja .Tbk</p>";

            $replyid = $database->max("tickets_replies", "id", ["ticketid" => $ticketid]);
            $attachments = $database->select("files", "id", ["ticketreplyid" => $replyid]);
            //echo $spemail;
            //die();
            //sendEmail($cos['email'],$subject,$message,$ticket['clientid'],$ticket['idpeople'],$ccs,$attachments);
            sendEmail($spemail,$subject,$message,$ticket['clientid'],$ticket['idpeople'],$ccs,$attachments);
            //sendFCM($ticket['idpeople'], "Ticket Notification", $subject);
    }

    public static function ticketassigned($ticketid,$reply,$templateid) { //send ticket notification
        global $database;
        $template = getRowById("notificationtemplates",$templateid);
        $ticket = getRowById("tickets",$ticketid);
        
        $admins = getTableFiltered("people","id",$ticket['adminid']);
        foreach($admins as $admin) {}

        $client = __('Unassigned');
        $department = __('Unassigned');

        if($ticket['clientid'] != 0) $client = getSingleValue("clients","name",$ticket['clientid']);
        if($ticket['departmentid'] != 0) $department = getSingleValue("tickets_departments","name",$ticket['departmentid']);

        if($ticket['userid'] == 0) $contact = $ticket['email']; else $contact = getSingleValue("people","name",$ticket['userid']);

        $search = array('{ticketid}', '{status}', '{subject}', '{contact}', '{message}', '{company}', '{appurl}', '{client}', '{department}');
        $replace = array($ticket['ticket'], $ticket['status'], $ticket['subject'], $contact, $reply, getConfigValue("company_name"), getConfigValue("app_url"), $client, $department);

        $replace2 = array($ticket['ticket'], $ticket['status'], $ticket['subject'], $contact, $reply, getConfigValue("company_name"), getConfigValue("app_url"), $admin['name'], $department);

        $subject = str_replace($search, $replace, $template['subject']);
        $message = str_replace($search, $replace2, $template['message']);

        // attachments
        $replyid = $database->max("tickets_replies", "id", ["ticketid" => $ticketid]);
        $attachments = $database->select("files", "id", ["ticketreplyid" => $replyid]);

        $spmail = $admin['email'].';'.$ticket['email'].';'.$ticket['reported_email']; 
        
            sendEmail($spmail,$subject,$message,0,$admin['id'],$ccs=array(),$attachments);
            //sendEmail($ticket['email'],$subject,$message,0,$ticket['userid'],$ccs=array(),$attachments);
            sendFCM($admin['id'], "Ticket Notification", $subject);
        
    }

    public static function rejectrequestit($ticketid,$reply,$templateid) { //send ticket notification
        global $database;
        $template = getRowById("notificationtemplates",$templateid);
        foreach($database->query("select a.*,case when typereq = 1 then 'Permintaan Akun' when typereq = 2 then 'Izin Akses Data' when typereq = 4 then 'Penutupan Akun' end as typereqshow,b.name,b.email from people_dtl_request a join people b on a.peopleid=b.id where a.id = '$ticketid'") as $ticket){}
        
        $client = __('Unassigned');
        $department = __('Unassigned');

        if($ticket['clientid'] != 0) $client = getSingleValue("clients","name",$ticket['clientid']);
        $contact = $ticket['name'];

        $search = array('{ticketid}', '{status}', '{contact}', '{message}', '{company}', '{appurl}', '{client}', '{department}');
        $replace = array($ticket['typereqshow'], "Reject", $ticket['name'], $reply, getConfigValue("company_name"), getConfigValue("app_url"), $client, $department);

        $replace2 = array($ticket['typereqshow'], "Reject", $ticket['name'], $reply, getConfigValue("company_name"), getConfigValue("app_url"), $client, $department);

        $subject = str_replace($search, $replace, $template['subject']);
        $message = str_replace($search, $replace2, $template['message']);

        // attachments
        $replyid = $database->max("tickets_replies", "id", ["ticketid" => $ticketid]);
        $attachments = $database->select("files", "id", ["ticketreplyid" => $replyid]);

        sendEmaildua($ticket['email'],$subject,$message,0,$ticket['peopleid'],$ccs=array(),$attachments);
            //sendEmail($ticket['email'],$subject,$message,0,$ticket['userid'],$ccs=array(),$attachments);
        sendFCM($ticket['peopleid'], "Ticket Notification", $subject);
        
    }

    public static function ticketStaffreminder($ticketid,$reply,$templateid) { //send ticket notification
        global $database;
        $template = getRowById("notificationtemplates",$templateid);
        $ticket = getRowById("tickets",$ticketid);

        $client = __('Unassigned');
        $department = __('Unassigned');

        if($ticket['clientid'] != 0) $client = getSingleValue("clients","name",$ticket['clientid']);
        if($ticket['departmentid'] != 0) $department = getSingleValue("tickets_departments","name",$ticket['departmentid']);

        if($ticket['userid'] == 0) $contact = $ticket['email']; else $contact = getSingleValue("people","name",$ticket['userid']);

        $search = array('{ticketid}', '{status}', '{subject}', '{contact}', '{message}', '{company}', '{appurl}', '{client}', '{department}');
        $replace = array($ticket['ticket'], $ticket['status'], $ticket['subject'], $contact, $reply, getConfigValue("company_name"), getConfigValue("app_url"), $client, $department);

        $subject = str_replace($search, $replace, $template['subject']);
        $message = str_replace($search, $replace, $template['message']);

        // attachments
        $replyid = $database->max("tickets_replies", "id", ["ticketid" => $ticketid]);
        $attachments = $database->select("files", "id", ["ticketreplyid" => $replyid]);

        $admins = getTableFiltered("people","type","admin","ticketsnotification","1");
        foreach($admins as $admin) {
            sendEmail($admin['email'],$subject,$message,0,$admin['id'],$ccs=array(),$attachments);

            sendFCM($admin['id'], "Ticket Notification", $subject);
        }
    }

    public static function ticketUserapproveit($ticketid,$namepeople,$headstatus,$headpeople,$headappdate,$itpeople,$itstatus,$title,$namedept,$locationname,$createdate,$ctk,$email,$reply,$templateid,$statusticket) { //send ticket notification
        global $database;
        $template = getRowById("notificationtemplates",$templateid);
        $ticket = getRowById("tickets",$ticketid);
        $peopledtl = getRowById("people_dtl_request",$ticketid);
        $head = getRowById("people",$headpeople);
        $ithead = getRowById("people",$itpeople);
        $datenow = date('Y-m-d H:i:s');

        if($itstatus == "Approve"){$itstatus2 = "Approved";}else{$itstatus2=$itstatus;}
        $ccs = array();

        $subject = "IT APPROVAL Request ".$ctk." : ".$namepeople;
        $message = "Form Request ".$ctk.", here's the details : <br>
                    <br>
                        <table width='50%' cellpadding='0' cellspacing='0' border='0'>                   
                          <tr>
                            <td align='left' width='20%'><strong>Name</strong></td>
                            <td align='left' width='2%'>:</td>
                            <td align='left'><strong>$namepeople</strong></td>
                          </tr>
                          <tr>
                            <td align='left'><strong>Dept</strong></td>
                            <td align='left' width='2%'>:</td>
                            <td align='left'>$namedept - $locationname</td>
                          </tr>
                          <tr>
                            <td align='left'><strong>Jabatan</strong></td>
                            <td align='left' width='2%'>:</td>
                            <td align='left'>$title</td>
                          </tr>
                          <tr>
                            <td align='left'><strong>Created Date</strong></td>
                            <td align='left' width='2%'>:</td>
                            <td align='left'>$createdate</td>
                          </tr>
                          <tr>
                            <td align='left'>&nbsp;</td>
                            <td align='left' width='2%'>&nbsp;</td>
                            <td align='left'>&nbsp;</td>
                          </tr>
                          <tr>
                            <td align='left'><strong>Head Approve</strong></td>
                            <td align='left' width='2%'>:</td>
                            <td align='left'>$head[name]</td>
                          </tr>
                          <tr>
                            <td align='left'><strong>Status</strong></td>
                            <td align='left' width='2%'>:</td>
                            <td align='left'>$headstatus</td>
                          </tr>
                          <tr>
                            <td align='left'><strong>Approve Date</strong></td>
                            <td align='left' width='2%'>:</td>
                            <td align='left'>$headappdate</td>
                          </tr>
                          <tr>
                            <td align='left'>&nbsp;</td>
                            <td align='left' width='2%'>&nbsp;</td>
                            <td align='left'>&nbsp;</td>
                          </tr>
                          <tr>
                            <td align='left'><strong>IT Approve</strong></td>
                            <td align='left' width='2%'>:</td>
                            <td align='left'>$ithead[name]</td>
                          </tr>
                          <tr>
                            <td align='left'><strong>Status</strong></td>
                            <td align='left' width='2%'>:</td>
                            <td align='left'>$itstatus2</td>
                          </tr>
                          <tr>
                            <td align='left'><strong>IT Approve Date</strong></td>
                            <td align='left' width='2%'>:</td>
                            <td align='left'>$datenow</td>
                          </tr>
                        </table>

                     <br>
                    <br>
                    View Progress Request Follow this <a href='http://192.168.31.25/eraticket/index.php?x=viewnewreq&d=$ticketid'>Link</a>
                        <br>
                        <br>
                        regards, <br>
                        IT Ticketing Admin <br>
                            ";
        $attachments = array();
        sendEmail($email,$subject,$message,$peopledtl['clientid'],$peopledtl['peopleid'],$ccs,$attachments);

        //sendFCM($peopledtl['peopleid'], "Ticket Notification", $subject);
    }

    public static function breq($peopleid,$templateid,$statusticket,$spemail) { //send ticket notification
        global $database;
        $template = getRowById("notificationtemplates",$templateid);
        $tickets = $database->query("select a.name as namepeople, c.name as namedept, b.title,b.mobile,b.statusid,b.id as iddtl,b.typereq,a.email,b.alasanbutuh,b.tgl_butuh1,b.tgl_butuh2,b.akunakses,b.websiteakses,b.hasilpengecekanaset,b.assetid,d.headpeopleid,headstatus,DATE(headappdate) as headappdate,d.itpeopleid,itstatus,DATE(itappdate) as itappdate,itnotes,b.locationid,DATE(b.created_date) as created_date_dtl,e.name as locationname,a.id as idpeople,b.clientid from people a join people_dtl_request b on a.id=b.peopleid join clients c on b.clientid=c.id join people_dtl_approve d on b.id=d.peopledtlid join locations e on b.locationid=e.id
where b.id = '$peopleid' ORDER BY b.id DESC");
        foreach($tickets as $ticket){}

        if($ticket[typereq] == 1){$ctk = "newrequestacc";$typereq = 'Permintaan AKun';}else if($ticket[typereq] == 2){$ctk = "newrequestaci";$typereq = 'Izin Akses Data';}else if($ticket[typereq] == 4){$ctk = "newrequestacp";$typereq = 'Penutupan Akun';}

        $ccs = array(); if($ticket['ccs'] != "") $ccs = unserialize($ticket['ccs']);

        $client = __('Unassigned');
        $department = __('Unassigned')
;
        if($ticket['clientid'] != 0) $client = getSingleValue("clients","name",$ticket['clientid']);
        if($ticket['departmentid'] != 0) $department = getSingleValue("tickets_departments","name",$ticket['departmentid']);

        if($ticket['userid'] == 0) $contact = $ticket['email']; else $contact = getSingleValue("people","name",$ticket['userid']);

        if($ticket[typereq] == 1){$ctk = "newrequestacc";}else if($ticket[typereq] == 2){$ctk = "newrequestaci";}else if($ticket[typereq] == 4){$ctk = "newrequestacp";}
        
        $search = array('{ticketid}', '{status}', '{subject}', '{contact}', '{message}', '{company}', '{appurl}', '{client}', '{department}');
        $replace = array($noticket, 'Open',$typereq , $contact, $reply, getConfigValue("company_name"), getConfigValue("app_url"), $client, $department);

        $subject = str_replace($search, $replace, $template['subject'])." : ".$statusticket;

        //send email =========================================================
                    
            $message = "<p>Hello IT Team,<br><br>A new ticket has been created for your task.<br>Ticket ID: <b>".$noticket."</b><br><br> 
            Name : ".$ticket['namepeople']."<br>
            Dept : ".$ticket['namedept']."-".$ticket['locationname']."<br>
            Jabatan : ".$ticket['title']."<br>
            Please check your ticket task in Erassets<br><br>
            View Form Request Click <a href='http://192.168.31.25/eraticket/cetak.php?page=$ctk&id=$peopleid'>Here</a>
            <br><br>.<br><br>Best regards,<br>Eratex IT Assets - Eratex Djaja .Tbk</p>";

            $replyid = $database->max("tickets_replies", "id", ["ticketid" => $ticketid]);
            $attachments = $database->select("files", "id", ["ticketreplyid" => $replyid]);
            //echo $spemail;
            //die();
            //sendEmail($cos['email'],$subject,$message,$ticket['clientid'],$ticket['idpeople'],$ccs,$attachments);
            sendEmail($spemail,$subject,$message,$ticket['clientid'],$ticket['idpeople'],$ccs,$attachments);
            //sendFCM($ticket['idpeople'], "Ticket Notification", $subject);
    }
    
}


?>
