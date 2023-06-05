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
$no = 0;
$tlicdtl = $database->query("select b.name as licensename,a.id as iddtl,a.startsubsdate,a.endsubsdate from licenses_dtl a join licenses b on a.licensesid=b.id
where COALESCE(startsubsdate,0) <> 0 and DATEDIFF(endsubsdate,DATE(now())) < 60 and b.statusid > 0 and a.statusid > 0");
foreach($tlicdtl as $tlic){
     $no ++;
}
    if($no > 0){
        foreach($database->query("select email,name from people where roleid in (3,6)") as $email){
            //Notification::ticketStaffreminder($tclose['id'], "", 18);   
            include "../phpmailer/pushemailremindersubs.php";
            
        }
    }

   
?>
