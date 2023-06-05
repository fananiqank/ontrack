<?php

##################################
###           MODALS           ###
##################################

switch($_GET['modal']) {
   // global $database;
    // system
    case "suppliers/edit":
        $supplier = getRowById("suppliers",$_GET['id']);
        break;

    case "suppliers/view":
        $supplier = getRowById("suppliers",$_GET['id']);
        break;

    case "gudang/edit":
        $gudang = getRowById("gudang",$_GET['id']);
        break;

    case "gudang/view":
        $gudang = getRowById("gudang",$_GET['id']);
        break;

    case "labels/edit":
        $label = getRowById("labels",$_GET['id']);
        break;

    case "models/add":
        $manufacturers = getTable("manufacturers");
        break;

    case "models/edit":
        $manufacturers = getTable("manufacturers"); $model = getRowById("models",$_GET['id']);
        break;

    case "manufacturers/edit":
        $manufacturer = getRowById("manufacturers",$_GET['id']);
        break;

    case "qrcodes/edit":
        $qrcode = getRowById("qrcodes",$_GET['id']);
    break;

    case "qrcodes/attach":
        $qrcode = getRowById("qrcodes",$_GET['id']);

        $assets = getTableFiltered("assets","qrvalue","");
        $licenses = getTableFiltered("licenses","qrvalue","");
        $virtuals = getTableFiltered("virtual","qrvalue","");
    break;

    case "qrcodes/detach":
        $qrcode = getRowById("qrcodes",$_GET['id']);

    break;


    case "locations/add":
        $clients = getTable("clients");
        break;

    case "locations/edit":
        $clients = getTable("clients");
        $location = getRowById("locations",$_GET['id']);
        break;

    case "assetcategories/edit":
        $category = getRowById("assetcategories",$_GET['id']);
        break;

    case "licensecategories/edit":
        $category = getRowById("licensecategories",$_GET['id']);
        break;

    //transaction incoming
    case "incoming/add":
        $gudangs = getTable("gudang");
    break;

    //transaction outgoing
    case "outgoing/add":
        $gudangs = getTable("gudang");
    break;

    // people
    case "users/add":
        $clients = getTable("clients");
        $roles = getTableFiltered("roles","type","user");
        break;

    case "users/assignTSuser":
        $peoples = $database->query("select * from (select id,name from people where id = 4) a order by id"); 
    break;

    case "staff/add":
        $roles = getTableFiltered("roles","type","admin");
        break;


    // monitoring
    case "hosts/add":
        $clients = getTable("clients");
        break;

    case "hosts/edit":
        $clients = getTable("clients");
        $host = getRowById("hosts",$_GET['id']);
        break;

    case "hosts/assignPeople":
        $people = getTable("people");
        break;

    case "checks/edit":
        $check = getRowById("hosts_checks",$_GET['id']);
        break;


    // clients
    case "clients/edit":
        $client = getRowById("clients",$_GET['id']);
        break;

    case "clients/assignAdmin":
        $admins = getTableFiltered("people","type","admin");
        break;


    // credentials
    case "credentials/add":
        if($isAdmin) { $assets = getTable("assets"); } else { $assets = getTableFiltered("assets","clientid",$liu['clientid']); }
        $clients = getTable("clients");
        break;

    case "credentials/edit":
        $credential = getRowById("credentials",$_GET['id']);
        if($isAdmin) { $assets = getTable("assets"); } else { $assets = getTableFiltered("assets","clientid",$liu['clientid']); }
        $clients = getTable("clients");
        break;

    case "credentials/view":
        $credential = getRowById("credentials",$_GET['id']);
        logSystem("Credential Viewed -ID: " . $_GET['id']);
        break;


    // issues
    case "issues/add":
        if($isAdmin) { $assets = getTable("assets"); } else { $assets = getTableFiltered("assets","clientid",$liu['clientid']); }
        $clients = getTable("clients");
        if($isAdmin) { $projects = getTable("projects"); } else { $projects = getTableFiltered("projects","clientid",$liu['clientid']); }
        $admins = getTableFiltered("people","type","admin");
        break;

    case "issues/edit":
        $issue = getRowById("issues",$_GET['id']);
        if($isAdmin) { $assets = getTable("assets"); } else { $assets = getTableFiltered("assets","clientid",$liu['clientid']); }
        $clients = getTable("clients");
        if($isAdmin) { $projects = getTable("projects"); } else { $projects = getTableFiltered("projects","clientid",$liu['clientid']); }
        $admins = getTableFiltered("people","type","admin");
        break;


    // tickets
    case "tickets/add":
        $contacts = getTable("contacts");
        if($isAdmin) { $assets = getTable("assets"); } else { $assets = getTableFiltered("assets","clientid",$liu['clientid']); }
        $clients = getTable("clients");
        $departments = getTable("tickets_departments");
        $admins = getTableFiltered("people","type","admin");
        if($isAdmin) { $users = getTableFiltered("people","type","user"); } else { $users = getTableFiltered("people","type","user","clientid",$liu['clientid']); }
        break;

    case "tickets/edit":
        $ticket = getRowById("tickets",$_GET['id']);
        $ccs = array(); if($ticket['ccs'] != "") $ccs = unserialize($ticket['ccs']);
        $contacts = getTable("contacts");
        if($isAdmin) { $assets = getTable("assets"); } else { $assets = getTableFiltered("assets","clientid",$liu['clientid']); }
        $clients = getTable("clients");
        $departments = getTable("tickets_departments");
        //$admins = getTableFiltered("people","type","admin");
        $admins = getTableFiltered("people","type","admin","","");
        
        if($isAdmin) { $users = getTableFiltered("people","type","user"); } else { $users = getTableFiltered("people","type","user","clientid",$liu['clientid']); }
        break;


    // escalation rules
    case "escalationrules/add":
        $admins = getTableFiltered("people","type","admin");
        break;

    case "escalationrules/edit":
        $rule = getRowById("tickets_rules",$_GET['id']);
        $statuses = array();
        $priorities = array();
        if($rule['cond_status'] != "") $statuses = unserialize($rule['cond_status']);
        if($rule['cond_priority'] != "") $priorities = unserialize($rule['cond_priority']);
        $admins = getTableFiltered("people","type","admin");
        break;


    // assets
    case "assets/assignLicense":
        $licenses = $database->query("select a.id,a.name,a.tag,a.categoryid,sum(b.seats) as seats,c.typecategory 
from  licenses a left join licenses_dtl b on a.id=b.licensesid and b.statusid > 0 
join licensecategories c on a.categoryid=c.id
where a.statusid > 0 GROUP BY a.id,a.name,a.tag,a.categoryid"); 
        $licenses2 = $database->query("select a.id,a.name,a.tag,a.categoryid,sum(b.seats) as seats,c.typecategory  
from  licenses a left join licenses_dtl b on a.id=b.licensesid and b.statusid > 0 
join licensecategories c on a.categoryid=c.id
where a.statusid > 0 GROUP BY a.id,a.name,a.tag,a.categoryid"); 
    break;
    case "assets/assignTS":
        $peoples = $database->query("select * from (select id,name from people where id = 4) a order by id"); 
    break;

    // licenses
    case "licenses/assignAsset":
        if($isAdmin) { $assets= $database->query("select * from assets where statusid > 0"); }
        else { $assets = $database->query("select * from assets where statusid > 0 and clientid = '$liu[clientid]'"); }
        break;
    
    // Virtuals
    case "virtuals/assignLicense":
        if($isAdmin) {  $licenses = $database->query("select a.* from licenses a join licensecategories b on a.categoryid=b.id where statusid > 0");}
        else { $licenses = $database->query("select a.* from licenses a join licensecategories b on a.categoryid=b.id where statusid > 0 and clientid = '$liu[clientid]'"); }
        break;


    // projects
    case "projects/add":
        $clients = getTableFiltered("clients","id","6");
        break;

    case "projects/edit":
        $project = getRowById("projects",$_GET['id']);
        $clients = getTable("clients");
        break;

    case "projects/assignAdmin":
        $admins = getTableFiltered("people","type","admin");
        break;

    case "projects/addIssue":
        if($isAdmin) { $assets = getTable("assets"); } else { $assets = getTableFiltered("assets","clientid",$liu['clientid']); }
        $clients = getTable("clients");
        if($isAdmin) { $projects = getTable("projects"); } else { $projects = getTableFiltered("projects","clientid",$liu['clientid']); }
        $admins = getTableFiltered("people","type","admin");
    break;

    // Milestones
    case "milestones/edit":
        $milestone = getRowById("milestones",$_GET['id']);

    break;

    case "milestones/release":

        $milestones = getTableFiltered("milestones","projectid",$_GET['id']);

    break;


    // comments
    case "comments/add":
        $people = getTable("people");
        break;

    case "comments/edit":
        $comment = getRowById("comments",$_GET['id']);
        $people = getTable("people"); break;


    // contacts
    case "contacts/edit":
        $contact = getRowById("contacts",$_GET['id']);
        break;

    // akunakses
    case "akunakses/edit":
        //echo "select * from akunminta where idakunminta = '$_GET[id]'";
        //$akunakses = getRowById("akunminta",$_GET['id']);
        //$akunakses = $database->query("select * from akunminta where idakunminta = $_GET['id']");
        foreach($database->query("select * from akunminta where idakunminta = '$_GET[id]'") as $akunminta){}
        break;

    // notifications
    case "notifications/edit":
        $template = getRowById("notificationtemplates",$_GET['id']);
        break;

    // support departments
    case "supportdepartments/edit":
        $department = getRowById("tickets_departments",$_GET['id']);
        break;

    // predefined replies
	case "preplies/edit":
        $preply = getRowById("tickets_pr",$_GET['id']);
        break;

	case "preplies/insert":
        $preplies = getTable("tickets_pr");
        break;

    //history assign
    case "hisassign/view":
        $hisassign = getRowById("tickets",$_GET['id']);
        break;

    // api keys
    case "apikeys/add":
        $roles = getTableFiltered("roles","type","admin");
    break;

	case "apikeys/edit":
        $roles = getTableFiltered("roles","type","admin");
        $apikey = getRowById("api_keys",$_GET['id']);
    break;

    // assets custom fields
    case "assetscf/edit":
        $assetcf = getRowById("assets_customfields",$_GET['id']);
    break;

    // licenses custom fields
    case "licensescf/edit":
        $licensecf = getRowById("licenses_customfields",$_GET['id']);
    break;


    // kb
    case "kb/addCategory":
        $clients = getTable("clients");
        break;

	case "kb/editCategory":
        $kbcategory = getRowById("kb_categories",$_GET['id']);
        $selectedClients = array(); if($kbcategory['clients'] != "") $selectedClients = unserialize($kbcategory['clients']);
        $clients = getTable("clients");
        break;

	case "kb/addArticle":
        $kbcategories = getTable("kb_categories");
        $clients = getTable("clients");
        break;

	case "kb/viewArticle":
        $kbarticle = getRowById("kb_articles",$_GET['id']);
        break;

	case "kb/editArticle":
        $kbarticle = getRowById("kb_articles",$_GET['id']);
        $selectedClients = array(); if($kbarticle['clients'] != "") $selectedClients = unserialize($kbarticle['clients']);
        $kbcategories = getTable("kb_categories");
        $clients = getTable("clients");
        break;


    // timelog
    case "time/add":
        $clients = getTable("clients");
    break;

    case "time/edit":
        $log = getRowById("timelog",$_GET['id']);

        $tag_issues = unserialize($log['issues']);
        $tag_tickets = unserialize($log['tickets']);

        if(empty($tag_issues)) $tag_issues = [];
        if(empty($tag_tickets)) $tag_tickets = [];

        $clients = getTable("clients");
    break;


    // files
    case "files/view":
        $file = getRowById("files",$_GET['id']);
        $mimetype = get_mime_content($file['file']);
    break;

    case "slaticket/edit":
        $slatickets = $database->query("select * from tickets_sla where id = $_GET[id]");
        foreach($slatickets as $slat){}
    break;


}

?>
