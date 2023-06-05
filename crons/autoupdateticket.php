<?php

// ERROR REPORTING

$debug = false;

if($debug == false) {
    error_reporting(0);
    ini_set('display_errors', '0');
}

if($debug == true) {
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', '1');
}

$scriptpath = dirname(__DIR__);

// LOAD FUNCTIONS
require($scriptpath . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php');

// AUTOLOAD CLASSES
spl_autoload_register('vendorClassAutoload');
spl_autoload_register('appClassAutoload');

// composer autoload
require $scriptpath . '/vendor/autoload.php';

# LOAD CONFIGURAGION FILE
require($scriptpath . DIRECTORY_SEPARATOR . 'config.php');

# INITIALIZE MEDOO
$database = new medoo($config);

# DATE & TIME
date_default_timezone_set(getConfigValue("timezone"));

$datenow = date('Y-m-d H:i:s');

foreach($database->query("select sla from tickets_sla") as $slt){}
foreach($database->query("select * from tickets where status = 'Finished' and CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), finishdate),':',1),0) AS SIGNED) > '24'") as $tclose){
    $database->query("update tickets set status = 'Closed', closedate='".$datenow."', closeauto = 1 where id=$tclose[id]");    
    $database->insert("tickets_replies", [
        "ticketid" => $tclose['id'],
        "peopleid" => $tclose['userid'],
        "message" => 'Automatic Closed Ticket',
        "timestamp" => date('Y-m-d H:i:s')
    ]);

    //Notification::ticketUser($ticketid, $data['message'], 1);
}

logSystem("Closed Automatic: " . $tclose['id']);


?>
