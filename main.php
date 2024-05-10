<?php
  include './modular/filesLogic.php';
  include './modular/logreq.php';
  $_SESSION['dirt'] = "uploads";
  if($_SESSION['uid'] == ''){
    header("Location: ./index.php?error=UNAUTHORIZED_ACCESS");
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
            td = tr[i].getElementsByTagName("td")[0];
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
    <?php include './modular/navbar.php'; 
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <div class="float-container">
      <?php
        function insertToTable( $dir=false ){
          if( !realpath( $dir ) )return false;
          
          # declare global variable (or include as a function parameter)
          global $conn;
          
          # basic sql cmds for use in "prepared statements"
          $sql=(object)array(
              'select'=>'SELECT * FROM `files` WHERE `name`=?',
              'insert'=>'INSERT INTO `files` ( `name`, `ftype`, `dateup`, `size`, `downloads`, `dirGroup` ) VALUES (?,?,now(),?,0,?)'
          );
          # create the prepared statements
          $stmts=(object)array(
              'select'=>$conn->prepare($sql->select),
              'insert'=>$conn->prepare($sql->insert)
          );
          # bind placeholders to variables (variables created later)
          $stmts->select->bind_param('s',$name);
          $stmts->insert->bind_param('ssss',$name,$type,$size,$folderpath);
          
          # counter variable for info
          $counter=0;
      
          # create the recursiveIterators & scan the directory
          $dirItr=new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::KEY_AS_PATHNAME );
          $recItr=new RecursiveIteratorIterator( $dirItr, RecursiveIteratorIterator::CHILD_FIRST );
      
          # iterate through scan results
          foreach( $recItr as $obj => $entity ) {
            # process files only
            if( $entity->isFile() ){
              $type  = pathinfo( $entity->getPathName(), PATHINFO_EXTENSION );
              $name  = pathinfo( $entity->getPathName(), PATHINFO_FILENAME );
              $folder= realpath( pathinfo( $entity->getPathName(), PATHINFO_DIRNAME ) );
              $folderp = explode("\\",$folder);
              $folderc = sizeof($folderp)-1;
              $folderpath = $folderp[$folderc];
              $size=filesize( $entity->getPathName() );
              
              # query db to see if file already exists
              $stmts->select->execute();
              $stmts->select->store_result();
              
              # If file does not exist in db, add it.
              if( $stmts->select->num_rows==0 ){
                  $stmts->insert->execute();
                  $counter++;
              }
              $stmts->select->free_result();
            }
          }
          # close statement objects
          $stmts->select->close();
          $stmts->insert->close();
          
          # leave db open if other tasks are to follow
          # otherwise call $conn->close() and return.
          //return $counter ? sprintf('Scan finished: %d files processed', $counter ) : 'Scan completed: No new files added';
      }
      
      print insertToTable( $_SESSION['dirt'] );
      ?>
      <!-- Div for the left panel (list of folders and directories) -->
      <div class="float-child left-float bg-light">  
      <input type="text" id="myInput" onkeyup="myFilter()" placeholder="Search for file names..." />
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

                  $dTP = explode("/",$dirTestPath[$i]);
                  $dTPC = sizeof($dTP)-1;
                  $dTPath = $dTP[$dTPC];

                  echo '<li id="'.$dirTestPath[$i].'"><span class="caret">' . $ff . '</span>';
                  echo '<ul class="nested">';

                  $i++;
                  //calls the function again when the file encountered is a directory/folder
                  listFolderFiles($dir . '/' . $ff);
                  echo '</ul>';
                }
                echo '</li>';
              }
              echo '</ul>';
            }
            print listFolderFiles($_SESSION['dirt']);
          ?>
        </ul>
        <script>
          //script for hiding and displaying directory trees when double clicked
          var toggler = document.getElementsByClassName("caret");
          var i;

          for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function() {
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
        <form action="main.php" method="post" enctype="multipart/form-data" >
          <h3>Upload File</h3>
          <input type="file" name="myfile"> <br>
          <button class="btn btn-primary" type="submit" name="save">UPLOAD</button>
        </form>
      </div>

      <!-- Div for the right panel (list of files) -->
      <div class="float-child right-float prev-panel">
        <!-- Table -->
        <?php include "./modular/conTent.php"; ?>
      </div>
    </div>
  </body>
</html>