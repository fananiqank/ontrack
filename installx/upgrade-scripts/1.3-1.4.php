<?php

$sql = file_get_contents('sql/db_1.3-1.4.sql');
$database->query($sql);
sleep(1);

$people = $database->select("people", "*");
foreach($people as $item) {
    if($item['type'] == "admin") $database->update("people", ["roleid" => 1], ["id" => $item['id']]);
    if($item['type'] == "user") $database->update("people", ["roleid" => 2], ["id" => $item['id']]);
}

$database->update("config", ["value" => "1.4"], ["name" => "db_version"]);


sleep(1);

?>
