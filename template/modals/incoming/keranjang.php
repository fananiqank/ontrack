<?php 
if($_GET[userid]){
    require_once "../../../funlibs.php";
    session_start();
    $con=new Database();

    $lists = $con->select("tx_barangmasuk_dtl a join spareparts b on a.sparepartid=b.id","a.*,b.name,case when a.jenisbrg=1 then 'Baru' else 'Bekas' end typebrg","userid = '$_GET[userid]' and a.statusid = 0");
} else {
    $lists = $database->query("select a.*,b.name,case when a.jenisbrg=1 then 'Baru' else 'Bekas' end typebrg from tx_barangmasuk_dtl a join spareparts b on a.sparepartid=b.id where userid = '$liu[id]' and a.statusid = 0");
}
?>
<b><u>List Sparepart</u></b>
        <table class="table">
            <thead style=" position: sticky; top: 0; z-index: 1;background-color: white;color: black">
                <tr>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Type</th>
                    <th>Act</th>
                </tr>
            </thead>
            <tbody>
               <?php 

               foreach($lists as $brgmsk){ ?>
    
                <tr>
                    <td><?=$brgmsk['name']?></td>
                    <td><?=$brgmsk['qty']?></td>
                    <td><?=$brgmsk['typebrg']?></td>
                    <td><a href="javascript:void(0)" onclick="deleterec(<?=$brgmsk[id]?>)"><i class="fa fa-trash" style="color: red;"></i></a></td>
                </tr>
                <?php 
                }
                ?>
            </tbody>
        </table>
