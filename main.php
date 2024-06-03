<?php
  include './modular/filesLogic.php';
  include './modular/logreq.php';
  if(empty($_SESSION['dirt'])){
    $_SESSION['dirt'] = "uploads";
  }
  if($_SESSION['uid'] == ''){
    header("Location: ./index.php?error=UNAUTHORIZED_ACCESS");
  }

  $dirQuery = "SELECT DISTINCT dirGroup FROM files";
  $dirstm = $conn->prepare($dirQuery);
  $dirstm->execute();
  $dirres = $dirstm->get_result();
  $dres = array();

  while($opt = $dirres->fetch_assoc()){
      array_push($dres, $opt['dirGroup']);
  }

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EFC Library</title>
    <!-- Bootstrap CSS -->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./styless/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="scripts.js"></script>
    <script type="text/javascript">
        function myFilter() {
        // Declare variables
          var input, filter, table, tr, td, i, txtValue;
          input = document.getElementById("myInput");
          filter = input.value.toUpperCase();
          table = document.getElementById("myTable2");
          tr = table.getElementsByTagName("tr");

          // Loop through all table rows, and hide those who don't match the search query
          for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
              txtValue = td.textContent || td.innerText;
              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
              } else {
                tr[i].style.display = "none";
              }
            }
          }
        }
    </script>
  </head>
  <body>
    <!-- Navigator Bar (Top part of the website) -->
    <?php include './modular/navbar.php'; ?>

    <div class="float-container">
      <!-- Div for the left panel (list of folders and directories) -->
      <div class="float-child left-float bg-light">  
        <h2>Folders</h2>
        <ul id="myUL">
          <!-- PHP Function for displaying all directories in the system -->
          <?php foreach ($dres as $dira){?>
            <li id="<?php echo $dira;?>"><?php echo $dira;?></li>
          <?php } ?>
        </ul>
      </div>

      <!-- Div for the right panel (list of files) -->
      <div class="float-child right-float prev-panel">
        <!-- Main Table -->
        <?php include "./modular/conTent.php"; ?>
      </div>
    </div>
  </body>
</html>