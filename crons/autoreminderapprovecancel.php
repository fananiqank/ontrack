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

    $tclosed = $database->query("select a.id,case when typereq=1 then 'Permintaan Akun' when typereq=2 then 'Izin Akses Data' when typereq=4 then 'Penutupan Akun' end typereqshow,c.name as namepeople,c.email,d.name as namedept,e.name as locationname,a.title,e.peopleid,a.locationid,
        a.peopleid as peopleiddtlreq 
    from people_dtl_request a join people_dtl_approve b on a.id =b.peopledtlid join people c on a.peopleid=c.id
    left join clients d on a.clientid=d.id left join locations e on a.locationid=e.id
    where (headstatus is null and CAST(COALESCE(SUBSTRING_INDEX(TIMEDIFF(now(), a.created_date),':',1),0) AS SIGNED) > '72')");
    foreach($tclosed as $tclose){
            
            $database->query("delete from people where id = '$tclose[peopleiddtlreq]'");

            $database->query("update people_dtl_request set statusid = 'Cancel' where id = $tclose[id]");

            $data2 = array(
                "headpeopleid" => "0",
                "headnotes" => "Automatically Cancel by System",
                "headappdate" => $datenow,
                "headstatus" => "Cancel",
                "itpeopleid" => "0",
                "itnotes" => "Automatically Cancel by System",
                "itappdate" => $datenow,
                "itstatus" => "Cancel",
            );
            $database->query("update people_dtl_approve set headpeopleid = '0',headnotes = 'Automatically Cancel by System',headappdate = '".$datenow."',headstatus='Cancel',itpeopleid = '0',itnotes = 'Automatically Cancel by System',itappdate = '".$datenow."',itstatus='Cancel' where peopledtlid = $tclose[id]");



        include "./phpmailer/pushemailreminderheadapprovecancel.php";
    

    //Notification::ticketUser($ticketid, $data['message'], 1);
}

//logSystem("Closed Automatic: " . $tclose['id']);


?>
