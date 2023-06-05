<?php

$database->update("config", ["value" => "1.9"], ["name" => "db_version"]);


sleep(1);

?>
