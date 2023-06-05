<?php

$sql = file_get_contents('sql/db_1.10-1.11.sql');
$database->query($sql);
sleep(1);







$issues = $database->select("issues", "*", [ "timespent[!]" => 0 ]);

foreach($issues as $issue) {
    $issuedate = explode(" ", $issue['dateadded']);
    $endtime = date('H:i:s',strtotime('+'.$issue['timespent'].' minutes',strtotime($issue['dateadded'])));
    $database->insert("timelog", [
        "staffid" => $issue['adminid'],
        "clientid" => $issue['clientid'],
        "projectid" => $issue['projectid'],
        "assetid" => $issue['assetid'],
        "issues" => serialize( array($issue['id']) ),
        "tickets" => serialize( array() ),
        "description" => "Coverted from issue's time spent during 1.11 version upgrade.",
        "date" => $issuedate[0],
        "start" => $issuedate[1],
        "end" => $endtime,
    ]);
}



$tickets = $database->select("tickets", "*", [ "timespent[!]" => 0 ]);

foreach($tickets as $ticket) {
    $ticketdate = explode(" ", $ticket['timestamp']);
    $endtime = date('H:i:s',strtotime('+'.$ticketdate['timespent'].' minutes',strtotime($ticketdate['timestamp'])));
    $database->insert("timelog", [
        "staffid" => $ticket['adminid'],
        "clientid" => $ticket['clientid'],
        "projectid" => $ticket['projectid'],
        "assetid" => $ticket['assetid'],
        "issues" => serialize( array() ),
        "tickets" => serialize( array($ticket['id']) ),
        "description" => "Coverted from ticket's time spent during 1.11 version upgrade.",
        "date" => $ticketdate[0],
        "start" => $ticketdate[1],
        "end" => $endtime,
    ]);
}


$database->update("config", ["value" => "1.11"], ["name" => "db_version"]);

sleep(1);

?>
