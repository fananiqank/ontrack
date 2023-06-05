<?php

$sql = file_get_contents('sql/db_1.7-1.8.sql');
$database->query($sql);
sleep(1);


$database->update("config", ["value" => "1.8"], ["name" => "db_version"]);


sleep(1);

?>
