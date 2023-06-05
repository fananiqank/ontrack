<?php

// add encryption iv to config
$encryption_iv = randomString(64);
$data = '<?php $config = array(
 "database_type"=>"mysql",
 "database_name"=>"'.$config['database_name'].'",
 "server"=>"'.$config['server'].'",
 "username"=>"'.$config['username'].'",
 "password"=>"'.$config['password'].'",
 "charset"=>"utf8",
 "port"=>3306,
 "encryption_key"=>"'.$config['encryption_key'].'",
 "encryption_iv"=>"'.$encryption_iv.'" ); ?>';
$file = fopen("../config.php","w+");
fwrite($file,$data);
fclose($file);

// db operations

$credentials = $database->select("credentials", "*");
foreach($credentials as $credential) {
    $openssl_password = encrypt_decrypt('encrypt', mc_decrypt($credential['password'],$config['encryption_key']), $config['encryption_key'], $encryption_iv);
    $database->update("credentials", [ "password" => $openssl_password ], ["id" => $credential['id'] ]);
}

$licenses = $database->select("licenses", "*");
foreach($licenses as $license) {
    $openssl_license = encrypt_decrypt('encrypt', mc_decrypt($license['serial'],$config['encryption_key']), $config['encryption_key'], $encryption_iv);
    $database->update("licenses", [ "serial" => $openssl_license ], ["id" => $license['id'] ]);
}


$database->update("config", ["value" => "1.12"], ["name" => "db_version"]);


sleep(1);


?>
