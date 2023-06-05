<?php 

// $date1 = date('Y-m-d').' 06:00:00';
// $startDate = time();
// $date2_2 = date('Y-m-d', strtotime('+1 day', $startDate)).' 06:00:00';
// $cekdate = date('Y-m-d H:i:s');

$date1 = date('Y-m-d');
$startDate = time();
$date2 = date('Y-m-d', strtotime('+1 day', $startDate));

foreach($database->query("select count(bulan) banyak
from (select MONTH(`timestamp`) bulan,YEAR(`timestamp`) tahun from tickets where YEAR(timestamp)='".$_GET['th']."' GROUP BY MONTH(`timestamp`),YEAR(`timestamp`)) a ") as $cmy){}

//select IN 
$selectin = $database->query("select MONTH(timestamp) bulan,YEAR(timestamp) tahun,coalesce(b.jumticket,0) jumticket,concat(MONTH(timestamp),'-',YEAR(timestamp)) butah 
from tickets a 
left join (select MONTH(timestamp) bulan,YEAR(timestamp) tahun,count(id) jumticket,concat(MONTH(timestamp),'-',YEAR(timestamp)) butah from tickets WHERE typeticket in (1,2) and YEAR(timestamp)='".$_GET['th']."' GROUP BY MONTH(timestamp),YEAR(timestamp) order by MONTH(timestamp)) b on MONTH(timestamp) =b.bulan
WHERE YEAR(timestamp)='".$_GET['th']."' GROUP BY MONTH(timestamp),YEAR(timestamp) order by MONTH(timestamp)");
$nin = 0;
foreach ($selectin as $sse) { 
    $jam=$nin;
    $datanyain[$jam]['jumticket']=$sse['jumticket'];
    $nin ++;
} 
//var_dump($datanyain);
//select Out
$selectout = $database->query("select MONTH(timestamp) bulan,YEAR(timestamp) tahun,coalesce(b.jumticket,0) jumticket,concat(MONTH(timestamp),'-',YEAR(timestamp)) butah 
from tickets a 
left join (select MONTH(timestamp) bulan,YEAR(timestamp) tahun,count(id) jumticket,concat(MONTH(timestamp),'-',YEAR(timestamp)) butah from tickets WHERE typeticket in (3) and YEAR(timestamp)='".$_GET['th']."' GROUP BY MONTH(timestamp),YEAR(timestamp) order by MONTH(timestamp)) b on MONTH(timestamp) =b.bulan
WHERE YEAR(timestamp)='".$_GET['th']."' GROUP BY MONTH(timestamp),YEAR(timestamp) order by MONTH(timestamp);");
$non = 0;
foreach ($selectout as $sso) {
    $jam2=$non;
    $datanyaout[$jam2]["jumticket"]=$sso['jumticket'];
    $non ++;
}

?>

<style type="text/css">
  .heightcardtask{
    height: 200px;
    width: 100%;
  }
</style>

<div class="col-lg-12 col-md-12">
  <button id="print-chart-btn">Print Chart</button>
  <div class="card card-chart heightcardtask" >
   <div class="card-body" align="center">
      <div class="chart-area heightcardtask">
        <canvas id="myChart"></canvas>
        
        <input type="hidden" name="test" id="test" >
      </div>
    </div>
  </div>
</div>
<hr>
<!-- <div class="col-lg-12 col-md-12" style="margin-top: 25px;">
  <div class="card card-chart heightcardtask2">
    <div class="card-body" style="padding: 0px;">
      <div class="table-full-width table-responsive tabledawip">     
        <table class="table pre-scrollable" border="1" style="font-size: 10px;">
          <thead>
              <tr>
                <th>M/Y</th>
            <?php 
                  $jmy = $database->query("select concat(MONTH(`timestamp`),'-',YEAR(`timestamp`)) buhun from tickets GROUP BY MONTH(`timestamp`),YEAR(`timestamp`)");
                  foreach($jmy as $my){
                      echo "<th style='text-align:center'>".$my['buhun']."</th>";
                      
                  }
            ?>
          </tr>
          </thead>
          <tbody id="wasinout" style="overflow: scroll;">
            <tr align="center">
              <td>TS Ticket</td>
              <?php 

                  for( $i=0; $i<$cmy['banyak']; $i++) {
                    if($datanyain[$i]["jumticket"] == '') {
                      echo "<td align='center'>0</td>";
                    } else {
                      echo "<td align='center'>".$datanyain[$i]["jumticket"]."</td>";
                    }
                  }
              ?>
            </tr>
            <tr align="center">
              <td>Account</td>
            <?php 
                  for( $i=0; $i<$cmy['banyak']; $i++) {
                    if($datanyaout[$i]["jumticket"] == '') {
                      echo "<td align='center'>0</td>";
                    } else {
                      echo "<td align='center'>".$datanyaout[$i]["jumticket"]."</td>";
                    }
                  }
            ?>
            </tr>
          </tbody>
        </table>
    </div>
    </div>
  </div>
</div> -->
<script src="template/assets/Chart.js"></script> 
<script type="text/javascript">
  var ctx = document.getElementById("myChart").getContext('2d');
  var barChartData = {
        labels: [
          <?php 
          // $jmy = $database->query("select MONTH(timestamp) bulan from tickets where YEAR(timestamp) = YEAR(now()) GROUP BY MONTH(timestamp)");
          $jmy = $database->query("select MONTH(timestamp) bulan from tickets where YEAR(timestamp) = '".$_GET['th']."' GROUP BY MONTH(timestamp) order by MONTH(timestamp)");
              foreach($jmy as $my){
                    echo $my['bulan'].",";
              }
          ?>
        ],
        datasets: [{
          label: 'TS',
          data: [
          <?php 
              for( $i=0; $i<$cmy['banyak']; $i++) {
                  echo $datanyain[$i]['jumticket'].",";
                  
              }
          ?>,
          ],
           //data: isidata1,
           lineTension: 0,
           fill: false,
           borderColor: 'yellow',
           backgroundColor: 'rgba(255, 206, 86, 0.4)',
           pointBorderColor: '#FFDAB9',
           pointBackgroundColor: 'black',
           pointRadius: 3,
           pointHoverRadius: 5,
           pointHitRadius: 5,
           pointBorderWidth: 1,
           pointStyle: 'rectRounded',
           borderWidth: 1,
        },
        {
          label: 'Account',
          data: [
          <?php 
              
              for( $i=0; $i<$cmy['banyak']; $i++) {
                  echo $datanyaout[$i]['jumticket'].",";
              }
          ?>,
          ],
           //data: isidata2,
           lineTension: 0,
           fill: false,
           borderColor: 'blue',
           backgroundColor: 'rgba(54, 162, 235, 0.4)',
           pointBorderColor: '#000080',
           pointBackgroundColor: 'black',
           pointRadius: 3,
           pointHoverRadius: 5,
           pointHitRadius: 5,
           pointBorderWidth: 1,
           pointStyle: 'rectRounded',
           borderWidth: 1,
        }]
      };

    myChart = new Chart(ctx, {
        type: 'bar',
        data: barChartData,
        options: {
          animation: {
              duration: 0,
              "onComplete": function() {
                var chartInstance = this.chart,
                  ctx = chartInstance.ctx;
 
                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';
 
                this.data.datasets.forEach(function(dataset, i) {
                  var meta = chartInstance.controller.getDatasetMeta(i);
                  meta.data.forEach(function(bar, index) {
                    var data = dataset.data[index];
                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                  });
                });
              }
          },
          title: {
             display: true,
             text: 'Tickets Request Th.'+<?php echo $_GET['th']; ?>
          },
          tooltips: {
            enabled: true,
            mode: 'index',
            intersect: false
          },
          legend: {
              display: true,
              labels: {
                  fontColor: '#000000'
              }

          },
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            pointLabels :{
             fontSize: 10,
            },
            yAxes: [{
              scaleLabel: {
                display: true,
                labelString: 'Total Tickets',
                fontSize: 10
              },
              ticks: {
                beginAtZero:true,
                fontSize: 10
              }
            }],
            xAxes: [{
              scaleLabel: {
                display: true,
                labelString: 'Bulan',
                fontSize: 10
              },
              ticks: {
                fontSize: 10
              }
            }]
          }
        }
      });

    $('#print-chart-btn').on('click', function() {
      var canvas = document.querySelector("#myChart");
      var canvas_img = canvas.toDataURL("image/png",1.0); //JPEG will not match background color

      var pdf = new jsPDF('Portrait','in', 'A5'); //orientation, units, page size
      pdf.addImage(canvas_img, 'png', .1, .5, 7, 3.3); //image, type, padding left, padding top, width, height
      pdf.autoPrint(); //print window automatically opened with pdf
      var blob = pdf.output("bloburl");
      window.open(blob);
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@1.5.3/dist/jspdf.min.js"></script>