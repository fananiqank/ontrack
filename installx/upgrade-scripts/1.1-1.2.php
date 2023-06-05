<?php

$sql = file_get_contents('sql/db_1.1-1.2.sql');
$database->query($sql);
sleep(1);

$replies = $database->select("tickets_replies", "*");
foreach($replies as $reply) {
    $peopleid = 0;
    if($reply['adminid'] != 0) $peopleid = $reply['adminid'];
    if($reply['userid'] != 0) $peopleid = $reply['userid'];
    $database->update("tickets_replies", ["peopleid" => $peopleid], ["id" => $reply['id']]);
}

$database->query("ALTER TABLE `tickets_replies` DROP `adminid` , DROP `userid` ;");
$database->update("config", ["value" => "1.2"], ["name" => "db_version"]);


sleep(1);

?>
