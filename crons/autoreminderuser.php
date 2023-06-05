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

foreach($database->query("select a.*,b.name as namepeople from tickets a join people b on a.userid=b.id where (a.status = 'Finished' and CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.finishdate),':',1),0) AS SIGNED) > '12')") as $tclose){
        //Notification::ticketStaffreminder($tclose['id'], "", 18);   
        include "./phpmailer/pushemailreminderuser.php";
 
    //Notification::ticketUser($ticketid, $data['message'], 1);
}

//logSystem("Closed Automatic: " . $tclose['id']);


?>
