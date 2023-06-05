<?php

$sql = file_get_contents('sql/db_1.2-1.3.sql');
$database->query($sql);
sleep(2);

$asset_tag_prefix = $database->get("config", "value", [ "name" => "asset_tag_prefix" ]);
$license_tag_prefix = $database->get("config", "value", [ "name" => "license_tag_prefix" ]);

$clients = $database->select("clients", "*");
foreach($clients as $client) {
    $database->update("clients", ["asset_tag_prefix" => $asset_tag_prefix, "license_tag_prefix" => $license_tag_prefix], ["id" => $client['id']]);
}

$database->update("config", ["value" => "1.3"], ["name" => "db_version"]);


sleep(2);

?>
