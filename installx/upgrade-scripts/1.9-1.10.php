<?php

$sql = file_get_contents('sql/db_1.9-1.10.sql');
$database->query($sql);
sleep(1);

$database->update("config", ["value" => "1.10"], ["name" => "db_version"]);


sleep(1);

?>
