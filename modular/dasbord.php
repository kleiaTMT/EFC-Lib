<?php 
    include "FilesLogic.php";
    global $conn;
?>
    <div class="card" style="width: 18rem;">
    <img src="assets\users.png" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Registered Users: </h5>
      <?php
        $dqcheck = "SELECT count(*) FROM users";
        $dQ = $conn->prepare($dqcheck);
        $dQ->execute();
        $dQres = $dQ->get_result();

        while($row = $dQres->fetch_assoc()) {
            echo $dqcheck;
        } 
    ?>
    </div>
    <div class="card" style="width: 18rem;">
    <img src="assets\files.png" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Uploaded Files: </h5>
      <?php
        $fqcheck = "SELECT count(*) FROM files";
        $fQ = $conn->prepare($dqcheck);
        $fQ->execute();
        $fQres = $fQ->get_result();

        while($row = $fQres->fetch_assoc()) {
            echo $fqcheck;
        } 
    ?>
    </div>
  </div>