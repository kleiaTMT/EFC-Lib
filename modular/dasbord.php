<?php 
    include "FilesLogic.php";
    global $conn;

    $dataPoints = array();

    $tvQuery = $tvQuery = "SELECT DISTINCT lastvisit, COUNT(lastvisit) AS numvisits FROM visits GROUP BY lastvisit ASC";
    $tvQ = $conn->prepare($tvQuery);
    $tvQ->execute();
    $tvQres = $tvQ->get_result();
    while ($row = $tvQres->fetch_assoc()) {
      $newPoints = array("y" => $row['numvisits'], "label" => $row['lastvisit']);
      array_push($dataPoints, $newPoints);
    }

?>
<script>
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
      title: {
      text: "Total Website Visits"
      },
      axisY: {
        title: "Number of Unique Visits"
      },
      data: [{
        type: "line",
        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
      }]
    });
    chart.render();
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
    <div id="chartContainer" style="height: 370px; width: 80%;"></div>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
  </div>
  <div class="chrts">
    <div class="piechrts">

    </div>
    <div class="piechrts">
      
    </div>
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