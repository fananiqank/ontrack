<?php
// update database
$sql = file_get_contents('sql/db_1.6-1.7.sql');
$database->query($sql);
sleep(2);

// add encryption key to config
$encryption_key = randomString(64);
$data = '<?php $config = array(
 "database_type"=>"mysql",
 "database_name"=>"'.$config['database_name'].'",
 "server"=>"'.$config['server'].'",
 "username"=>"'.$config['username'].'",
 "password"=>"'.$config['password'].'",
 "charset"=>"utf8",
 "port"=>3306,
 "encryption_key"=>"'.$encryption_key.'" ); ?>';
$file = fopen("../config.php","w+");
fwrite($file,$data);
fclose($file);

// db operations
$adminPerms = 'a:90:{i:0;s:9:"addClient";i:1;s:10:"editClient";i:2;s:12:"deleteClient";i:3;s:12:"manageClient";i:4;s:12:"adminsClient";i:5;s:11:"viewClients";i:6;s:8:"addAsset";i:7;s:9:"editAsset";i:8;s:11:"deleteAsset";i:9;s:11:"manageAsset";i:10;s:12:"licenseAsset";i:11;s:10:"viewAssets";i:12;s:10:"addLicense";i:13;s:11:"editLicense";i:14;s:13:"deleteLicense";i:15;s:13:"manageLicense";i:16;s:12:"assetLicense";i:17;s:12:"viewLicenses";i:18;s:10:"addProject";i:19;s:11:"editProject";i:20;s:13:"deleteProject";i:21;s:13:"manageProject";i:22;s:18:"manageProjectNotes";i:23;s:13:"adminsProject";i:24;s:12:"viewProjects";i:25;s:9:"addTicket";i:26;s:10:"editTicket";i:27;s:12:"deleteTicket";i:28;s:12:"manageTicket";i:29;s:17:"manageTicketRules";i:30;s:17:"manageTicketNotes";i:31;s:22:"manageTicketAssignment";i:32;s:11:"viewTickets";i:33;s:8:"addIssue";i:34;s:9:"editIssue";i:35;s:11:"deleteIssue";i:36;s:11:"manageIssue";i:37;s:10:"viewIssues";i:38;s:10:"addComment";i:39;s:11:"editComment";i:40;s:13:"deleteComment";i:41;s:13:"assignComment";i:42;s:12:"viewComments";i:43;s:13:"addCredential";i:44;s:14:"editCredential";i:45;s:16:"deleteCredential";i:46;s:14:"viewCredential";i:47;s:15:"viewCredentials";i:48;s:5:"addKB";i:49;s:6:"editKB";i:50;s:8:"deleteKB";i:51;s:6:"viewKB";i:52;s:9:"addPReply";i:53;s:10:"editPReply";i:54;s:12:"deletePReply";i:55;s:12:"viewPReplies";i:56;s:10:"uploadFile";i:57;s:12:"downloadFile";i:58;s:10:"deleteFile";i:59;s:9:"viewFiles";i:60;s:7:"addHost";i:61;s:8:"editHost";i:62;s:10:"deleteHost";i:63;s:10:"manageHost";i:64;s:14:"viewMonitoring";i:65;s:7:"addUser";i:66;s:8:"editUser";i:67;s:10:"deleteUser";i:68;s:9:"viewUsers";i:69;s:8:"addStaff";i:70;s:9:"editStaff";i:71;s:11:"deleteStaff";i:72;s:9:"viewStaff";i:73;s:7:"addRole";i:74;s:8:"editRole";i:75;s:10:"deleteRole";i:76;s:9:"viewRoles";i:77;s:10:"addContact";i:78;s:11:"editContact";i:79;s:13:"deleteContact";i:80;s:12:"viewContacts";i:81;s:10:"manageData";i:82;s:14:"manageSettings";i:83;s:8:"viewLogs";i:84;s:10:"viewSystem";i:85;s:10:"viewPeople";i:86;s:11:"viewReports";i:87;s:11:"autorefresh";i:88;s:6:"search";i:89;s:4:"Null";}';

$database->update("roles", ["perms" => $adminPerms], [ "id" => 1 ]);

$credentials = $database->select("credentials", "*");
foreach($credentials as $credential) {
    $database->update("credentials", [ "password" => mc_encrypt($credential['password'],$encryption_key) ], ["id" => $credential['id'] ]);
}

$licenses = $database->select("licenses", "*");
foreach($licenses as $license) {
    $database->update("licenses", [ "serial" => mc_encrypt($license['serial'],$encryption_key) ], ["id" => $license['id'] ]);
}


$database->update("config", ["value" => "1.7"], ["name" => "db_version"]);


sleep(1);


?>
