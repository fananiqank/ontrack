<?php

$sql = file_get_contents('sql/db_1.4-1.5.sql');
$database->query($sql);
sleep(1);

$database->update("config", ["value" => "1.5"], ["name" => "db_version"]);


sleep(1);

?>
