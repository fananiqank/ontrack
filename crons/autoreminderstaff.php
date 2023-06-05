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

foreach($database->query("select id,adminid,status,ticket from tickets where (status = 'Open' and CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), `timestamp`),':',1),0) AS SIGNED) > '24') || (status = 'Assigned' and CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), approvedate),':',1),0) AS SIGNED) > '24') || (status = 'In Progress' and CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), modifydate),':',1),0) AS SIGNED) > '24')") as $tclose){
    $expakas = explode(';',$tclose['adminid']);
    $jmlkas = count($expakas);

    for($i=0;$i<$jmlkas;$i++){
        foreach($database->query("select email,name from people where id = '".$expakas[$i]."'") as $email){}
        //Notification::ticketStaffreminder($tclose['id'], "", 18);   
        include "./phpmailer/pushemailreminderstaff.php";
    }

    //Notification::ticketUser($ticketid, $data['message'], 1);
}

//logSystem("Closed Automatic: " . $tclose['id']);


?>
