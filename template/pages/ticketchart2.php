<?php 

// $date1 = date('Y-m-d').' 06:00:00';
// $startDate = time();
// $date2_2 = date('Y-m-d', strtotime('+1 day', $startDate)).' 06:00:00';
// $cekdate = date('Y-m-d H:i:s');

$date1 = date('Y-m-d');
$startDate = time();
$date2 = date('Y-m-d', strtotime('+1 day', $startDate));


//select IN 
$selectaset = $database->query("select b.name,count(a.id) as jumasset 
from tickets a join clients b on a.clientid=b.id where a.clientid != '' and YEAR(`timestamp`) = '".$_GET['th']."'
GROUP BY a.clientid ORDER BY count(a.id) DESC limit 5");
$ninaset = 0;
foreach ($selectaset as $ssaset) { 
    $jamaset=$ninaset;
    $datanyaaset[$jamaset]['jumasset']=$ssaset['jumasset'];
    $ninaset ++;
} 

?>

<style type="text/css">
  .heightcardtask{
    height: 200px;
    width: 100%;
  }
</style>

<div class="col-lg-12 col-md-12">
  <button id="print-chart-btn2">Print Chart</button>
  <div class="card card-chart heightcardtask" >
   <div class="card-body" align="center">
      <div class="chart-area heightcardtask">
        <canvas id="myChartreq"></canvas>
        
        <input type="hidden" name="test" id="test" >
      </div>
    </div>
  </div>
</div>
<hr>

<script src="template/assets/Chart.js"></script> 
<script type="text/javascript">
  var ctx = document.getElementById("myChartreq").getContext('2d');
  var barChartDatareq = {
        labels: [
          <?php 
          $selectaset2 = $database->query("select b.name,count(a.id) as jumasset 
from tickets a join clients b on a.clientid=b.id where a.clientid != '' and YEAR(`timestamp`) = '".$_GET['th']."' GROUP BY a.clientid ORDER BY count(a.id) DESC limit 5");
            foreach ($selectaset2 as $ssaset2) { 
                  echo "'".$ssaset2['name']."',";
              }
          ?>
        ],
        datasets: [{
          label: 'Assets',
          data: [
          <?php 
              for( $i=0; $i<6; $i++) {
                  echo $datanyaaset[$i]['jumasset'].",";
              }
          ?>,
          ],
           //data: isidata1,
           lineTension: 0,
           fill: false,
           borderColor: 'rgba(75, 192, 192, 1)',
           backgroundColor: 'rgba(75, 192, 192, 0.4)',
           pointBorderColor: '#FFDAB9',
           pointBackgroundColor: 'black',
           pointRadius: 3,
           pointHoverRadius: 5,
           pointHitRadius: 5,
           pointBorderWidth: 1,
           pointStyle: 'rectRounded',
           borderWidth: 0,
        },
        ]
      };

    myChartreq = new Chart(ctx, {
        type: 'bar',
        data: barChartDatareq,
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
             text: 'Most 5 Department Th.'+<?php echo $_GET['th']; ?>,
          },
          tooltips: {
            enabled: true,
            mode: 'index',
            intersect: false
          },
          legend: {
              display: true,
              labels: {
                  fontColor: 'white',
                  backgroundColor: 'white'
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
                labelString: 'Department',
                fontSize: 10
              },
              ticks: {
                fontSize: 10
              }
            }]
          }
        }
      });

    $('#print-chart-btn1').on('click', function() {
      var canvas = document.querySelector("#myChartreq");
      var canvas_img = canvas.toDataURL("image/png",1.0); //JPEG will not match background color

      var pdf = new jsPDF('Portrait','in', 'A5'); //orientation, units, page size
      pdf.addImage(canvas_img, 'png', .1, .5, 7, 3.3); //image, type, padding left, padding top, width, height
      pdf.autoPrint(); //print window automatically opened with pdf
      var blob = pdf.output("bloburl");
      window.open(blob);
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@1.5.3/dist/jspdf.min.js"></script>