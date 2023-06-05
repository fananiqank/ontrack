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

foreach($database->query("select a.id,case when typereq=1 then 'Permintaan Akun' when typereq=2 then 'Izin Akses Data' when typereq=4 then 'Penutupan Akun' end typereqshow,c.name as namepeople,d.name as namedept,e.name as locationname,a.title,e.peopleid,a.locationid,a.norequest 
    from people_dtl_request a join people_dtl_approve b on a.id =b.peopledtlid join people c on a.peopleid=c.id
    left join clients d on a.clientid=d.id left join locations e on a.locationid=e.id
    where (headstatus is null and CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.created_date),':',1),0) AS SIGNED) > '24')") as $tclose){
    $expakas = explode(';',$tclose['peopleid']);
    $jmlkas = count($expakas);

    for($i=0;$i<$jmlkas;$i++){
        foreach($database->query("select email,name from people where id = '".$expakas[$i]."'") as $email){}
        //Notification::ticketStaffreminder($tclose['id'], "", 18);   
        include "./phpmailer/pushemailreminderheadapprove.php";
    }

    //Notification::ticketUser($ticketid, $data['message'], 1);
}

//logSystem("Closed Automatic: " . $tclose['id']);


?>
