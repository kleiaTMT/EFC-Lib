<?php   include './modular/filesLogic.php';?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./styless/styles.css">
    <title>1st Test</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="scripts.js"></script>
  </head>
  <body>
    <!-- Navigator Bar (Top part of the website) -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #ffffff;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="assets/ECF.lib-logo.png" alt="EFC Library Logo" width="240" height="70">
            </a>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <div class="float-container">

      <!-- Div for the left panel (list of folders and directories) -->
      <div class="float-child left-float bg-light">  
        <ul id="myUL">
          <?php 
            //function for directory/folder listing
            function listFolderFiles($dir){
              $ffs = scandir($dir);

              unset($ffs[array_search('.', $ffs, true)]);
              unset($ffs[array_search('..', $ffs, true)]);
              
              //stores the directory/folder path in an array
              $dirTestPath[] = array();
              $i = 0;
          
              // prevent empty ordered elements
              if (count($ffs) < 1)
                return;
          
              // displays directory/folder panel
              echo '<ul>';
              foreach($ffs as $ff){
                if(is_dir($dir . '/' . $ff)) {
                  $dirTestPath[$i] = $dir . '/' . $ff;
                  echo '<li id='.$dirTestPath[$i].'><span class="caret">' . $ff . '</span>';
                  echo '<ul class="nested">';
                  
                  echo $dirTestPath[$i];
                  $i++;
                  //calls the function again when the file encountered is a directory/folder
                  listFolderFiles($dir . '/' . $ff);
                  echo '</ul>';
                }
                echo '</li>';
              }
              echo '</ul>';
            }
            listFolderFiles("uploads");
          ?>
        </ul>
        <script>
          //script for hiding and displaying directory trees when double clicked
          var toggler = document.getElementsByClassName("caret");
          var i;

          for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("dblclick", function() {
              this.parentElement.querySelector(".nested").classList.toggle("active");
              this.classList.toggle("caret-down");
            });
          }
        </script>

        <!-- Adding a folder form // Connects to filesLogic.php when the button is clicked -->
        <h3>Add Folder</h3>
        <form method="POST">
          <input type="text" id="pname" name="pname" placeholder="Enter the name">
          <input type="submit" class="button" value="Next">
        </form>

        <!-- Uploading a file in the database and the pc system // Connects to filesLogic.php when the button is clicked -->
        <form action="index.php" method="post" enctype="multipart/form-data" >
          <h3>Upload File</h3>
          <input type="file" name="myfile"> <br>
          <button class="btn btn-primary" type="submit" name="save">UPLOAD</button>
        </form>
      </div>

      <!-- Div for the right panel (list of files) -->
      <div class="float-child right-float prev-panel">

        <!-- Table -->
        <table id="myTable">

          <!-- Table headers with sortable headers -->
          <thead class="sticky-top">
              <th class="sorta" onclick="sortTable(0)">Name</th>
              <th class="sorta" onclick="sortTable(1)">Date Uploaded</th>
              <th class="sorta" onclick="sortTable(2)">Type</th>
              <th>Size (in KB)</th>
              <th>Downloads</th>
              <th>Action</th>
          </thead>

          <!-- Table body for list of files -->
          <tbody id="conTent">
            <?php 
              // Function for listing files from database
              function listTableContents($dire){

                // Condition for selecting only the rows where dirGroup is the desired directory/folder
                $sql = "SELECT * FROM files WHERE dirGroup='$dire'";
                $conn = mysqli_connect('localhost', 'root', '', 'file-management');
                $resu = mysqli_query($conn, $sql);

                // Condition for displaying each row in the database
                if(mysqli_num_rows($resu) > 0){
                  while($row = mysqli_fetch_assoc($resu)){
                    echo "
                    <tr>
                        <td><b>".$row['name']."</b></td>
                        <td>".$row['dateup']."</td>
                        <td>".$row['ftype']."</td>
                        <td>".floor($row['size'] / 1000) . ' KB'."</td>
                        <td>".$row['downloads']."</td>
                        <td><a href='index.php?file_id=".$row['filID']."'>Download</a></td>
                      </tr>
                  ";
                  }
                } 
              }
              listTableContents("uploads");
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>