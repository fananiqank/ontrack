<?php
$isidet=$con->select("trdata a left join mtdept b on a.departemen_data=b.id_dept","a.*,b.nama_dept","a.no_ticket='$idgen'");   
        $no=1;
        foreach($isidet as $det){                   
                        if ($d['status_data'] == '1'){
                          $statused = "OPEN";
                        } else if ($det['status_data'] == '2'){
                          $statused = "ON PROGRESS";
                        } else if ($det['status_data'] == '3'){
                          $statused = "HOLD";
                        } else if ($det['status_data'] == '4'){
                          $statused = "CLOSED";
                        } else if ($det['status_data'] == '5'){
                          $statused = "HOLD REQUEST";
                        } else if ($det['status_data'] == '6'){
                          $statused = "ASSIGN";
                        } else if ($det['status_data'] == '0'){
                          $statused = "REJECT";
                        }
 ?>
<table width="100%"  cellpadding="0" cellspacing="0" border="1">                   
  <tr>
    <td align="left"><strong>Ticket No</strong></td>
    <td align="left"><strong><?php echo $nopj ?></strong></td>
  </tr>
  <tr>
    <td align="left"><strong>Created Date</strong></td>
    <td align="left"><?php echo $det['created_date_data']?></td>
  </tr>
  <tr>
    <td align="left"><strong>Created By</strong></td>
    <td align="left"><?php echo $det['nama_data']?></td>
  </tr>
  <tr>
    <td align="left"><strong>Dept</strong></td>
    <td align="left"><?php echo $det['nama_dept']?></td>
  </tr>
  <tr>
    <td align="left"><strong>Problem</strong></td>
    <td align="left"><?php echo $det['remark_data']?></td>
  </tr>
  <tr>
    <td align="left"><strong>Status</strong></td>
    <td align="left"><strong><?php echo $statused?></strong></td>
  </tr>
</table>
			<?php } ?>
    