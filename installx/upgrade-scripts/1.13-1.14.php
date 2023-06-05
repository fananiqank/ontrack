<?php



$sql = file_get_contents('sql/db_1.13-1.14.sql');
$database->query($sql);
sleep(1);



$database->update("config", ["value" => "1.14"], ["name" => "db_version"]);


sleep(1);


?>
