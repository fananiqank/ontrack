<select class="form-control select2" id="licensedtl" name="licensedtl" style="width: 100%;">
<?php 
	  include "../../../funlibs.php";
	  $con=new Database();
	
	$liss2= $con->query("select a.name,b.id as iddtl,b.startsubsdate,b.endsubsdate,b.seats from licenses a join licenses_dtl b on a.id=b.licensesid where a.id = '$_GET[iddtl]'");
            foreach($liss2 as $lis2) {

            foreach($con->select("licenses_assets","count(id) jmlid","licenseiddtl = '$lis2[iddtl]'") as $pakai){}
            	$tseats2 = $lis2['seats']-$pakai2['jmlid'];
                if($tseats2 > 0){
                    echo "<option value='".$lis2['iddtl']."'>";
                        echo $lis2['name']." ( ".$lis2['startsubsdate']." - ".$lis2['endsubsdate']." ) - [".$tseats2."]";
                    echo "</option>";
                }
           	}
?>
</select>