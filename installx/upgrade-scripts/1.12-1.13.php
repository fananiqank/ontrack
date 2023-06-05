<?php

$sql = file_get_contents('sql/db_1.12-1.13.sql');
$database->query($sql);
sleep(1);


$assets = $database->select("assets", "*");


foreach($assets as $asset) {
    $customfieldsdata = array();


    $customfieldsdata[1] = $asset['purchase_order']; // Purchase Order Number
    $customfieldsdata[2] = $asset['value']; // Value
    $customfieldsdata[3] = $asset['condition']; // Condition
    $customfieldsdata[4] = $asset['removal_date']; // Removal Date


    $database->update("assets", [
        "customfields" => serialize($customfieldsdata),
    ], [ "id" => $asset['id'] ]);

}



$database->query("ALTER TABLE `assets` DROP `purchase_order`;");
$database->query("ALTER TABLE `assets` DROP `value`;");
$database->query("ALTER TABLE `assets` DROP `condition`;");
$database->query("ALTER TABLE `assets` DROP `removal_date`;");




$database->update("config", ["value" => "1.13"], ["name" => "db_version"]);


sleep(1);


?>
