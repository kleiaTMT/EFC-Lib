<?php 
    include "FilesLogic.php";
    global $conn;

    $dataPoints = array();

    $tvQuery = $tvQuery = "SELECT DISTINCT lastvisit, COUNT(lastvisit) AS numvisits FROM visits GROUP BY lastvisit ASC";
    $tvQ = $conn->prepare($tvQuery);
    $tvQ->execute();
    $tvQres = $tvQ->get_result();

?>
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Dates', 'Total Visits'],
          <?php 
            while ($row = $tvQres->fetch_assoc()) {
              echo "['".$row['lastvisit']."', ".$row['numvisits']."],";
            }
          ?>
        ]);

        var options = {
          title: 'Number of Unique Visits',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
<div class="flex-container">
  <div class="card" style="width: 20rem;">
    <div class="card-body">
      <h5 class="card-title">Registered Users: </h5>
      <hr>
      <?php
        $uqcheck = "SELECT uid FROM users";
        $uQ = $conn->prepare($uqcheck);
        $uQ->execute();
        $uQres = $uQ->get_result();
        $uQnum = mysqli_num_rows($uQres);
        echo $uQnum;
    ?>
    </div>
  </div>
  <div class="card" style="width: 20rem;">
    <div class="card-body">
      <h5 class="card-title">Uploaded File Count: </h5>
      <hr>
      <?php
        $fqcheck = "SELECT filID FROM files WHERE state = 1";
        $fQ = $conn->prepare($fqcheck);
        $fQ->execute();
        $fQres = $fQ->get_result();
        $fQnum = mysqli_num_rows($fQres);
        echo $fQnum;
      ?>
    </div>
  </div>
  <div class="card" style="width: 20rem;">
    <div class="card-body">
      <h5 class="card-title">Downloads Made: </h5>
      <hr>
      <?php
        $dqcheck = "SELECT SUM(downloads) FROM files";
        $dQ = $conn->prepare($dqcheck);
        $dQ->execute();
        $dQres = $dQ->get_result();
        $row = $dQres->fetch_assoc();
        echo array_sum($row);
      ?>
    </div>
  </div>
  <div class="card" style="width: 20rem;">
    <div class="card-body">
      <h5 class="card-title">Pending Requests: </h5>
      <hr>
      <?php
        $pqcheck = "SELECT filID FROM files WHERE state = 0";
        $pQ = $conn->prepare($pqcheck);
        $pQ->execute();
        $pQres = $pQ->get_result();
        $pQnum = mysqli_num_rows($pQres);
        echo $pQnum;
      ?>
    </div>
  </div>
</div>
<div class="grphs">
  <div class="vst">
  </div>
  <div class="chrts">
  <div id="curve_chart" style="width: 90%; height: 500px"></div>
  </div>
</div>
<style>
  .flex-container {
    width: 95%;
    margin-top: 4vh;
    margin-left: 4vw;
    display: flex;
    justify-content: center;
  }
  .card{
    margin: 3%;
    text-align: center;
  }
  .card-body {
    padding: 5%;
    font-size: 25px;
  }
</style>