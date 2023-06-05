<?php

$sql = file_get_contents('sql/db_1.0-1.1.sql');
$database->query($sql);
sleep(1);

$database->update("config", ["value" => "1.1"], ["name" => "db_version"]);
$issues = $database->select("issues", "*");
foreach($issues as $issue) { $database->update("issues", ["dateadded" => $issue['duedate'] . " 00:00:00"]); }
sleep(1);

?>
