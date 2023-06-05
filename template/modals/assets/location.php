<option value='0'>All</option>
<?php
include "../../../funlibs.php";
session_start();
$con=new Database();

    foreach($con->query("select * from locations where clientid=$_GET[id]") as $loc) {
        echo "<option value='".$loc['id']."'>";
                echo $loc['name'];
        echo "</option>";
    }
?>