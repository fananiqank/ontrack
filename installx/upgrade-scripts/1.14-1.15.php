<?php

$sql = file_get_contents('sql/db_1.14-1.15.sql');
$database->query($sql);
sleep(1);



$database->update("config", ["value" => "1.15"], ["name" => "db_version"]);


sleep(1);


?>
