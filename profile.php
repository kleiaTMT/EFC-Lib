<?php 
    include "./modular/filesLogic.php";
    include "./modular/logreq.php";

    $profQuery = "SELECT * FROM files WHERE uname = ?";
    $profStm = $conn->prepare($profQuery);
    $profStm->bind_param("s", $_SESSION['uname']);
    $profStm->execute();
    $profRes = $profStm->get_result();

?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Profile Page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="./styless/styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="scripts.js"></script>
        
    </head>
    <body class="profile-card">
        <?php include './modular/navbar.php'; ?>
        <div>
            <div class="prof-info">
                <div class="tabswtch">
                    <button class="tablinks" onclick="openTab(event, 'Details')" id="defaultOpen">Details</button>
                    <button class="tablinks" onclick="openTab(event, 'Uploads')">Uploads</button>
                </div>
                <div id="Details" class="prof-info-content">
                    <table class="prof-table">
                        <tr>
                            <td>Name</td>
                            <td><?php echo $_SESSION['uname'];?></td>
                        </tr>
                        <tr>
                            <td>Email Address</td>
                            <td><?php echo $_SESSION['emailAddr'];?></td>
                        </tr>
                        <tr>
                            <td>Company/Branch</td>
                            <td><?php echo $_SESSION['company'];?></td>
                        </tr>
                        <tr>
                            <td>Last Visit</td>
                            <td><?php echo $_SESSION['lastdate'];?></td>
                        </tr>
                        <tr>
                            <td>Date Created</td>
                            <td><?php echo $_SESSION['datecreate'];?></td>
                        </tr>
                    </table>
                </div>
                <div id="Uploads" class="prof-info-content">
                    <input class="search" type="text" id="myInput" onkeyup="myFilter()" placeholder="Search for file names..." />
                    <table id="myTable4" class="prof-uploads">
                        <thead class="sticky-top">
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Size</th>
                            <th>Date Approved</th>
                            <th>Date Uploaded</th>
                        </thead>
                        <tbody><?php 
                            while($row = $profRes->fetch_assoc()){ 
                                $formatted = $row['size'] / 1000;
                                $finformat = number_format($formatted, 2, '.', ',');?> 
                                <tr>
                                    <td><?php echo $row['name']; ?> </td>
                                    <td><?php echo $row['ftype']; ?> </td>
                                    <td><?php echo $row['dirGroup']; ?> </td>
                                    <td><?php echo $finformat.' KB'; ?> </td>
                                    <td><?php echo $row['dateappr']; ?> </td>
                                    <td><?php echo $row['dateup']; ?> </td>
                                </tr><?php
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="prof-btns">
                <div class="prof-holder"></div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePW">Change Password</button>
                <div class="modal fade" id="changePW" tabindex="-1" aria-labelledby="changePWLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <form method="POST">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="changePWLabel">Change Password Form</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label for="oldpw">Old Password</label>
                                        <input type="password" name="oldpw">
                                    <label for="newpw">New Password</label>
                                        <input type="password" name="newpw">
                                    <label for="renewpw">Confirm Password</label>
                                        <input type="password" name="renewpw">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="changePass" class="btn btn-primary">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <button type="button" class="btn btn-primary">View Documentation</button>
            </div>
        </div>
    </body>
    <script>
        function openTab(evt, tabName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("prof-info-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";

        }
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();

        function myFilter() {
        // Declare variables
          var input, filter, table, tr, td, i, txtValue;
          input = document.getElementById("myInput");
          filter = input.value.toUpperCase();
          table = document.getElementById("myTable4");
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
    <footer>
    </footer>
</html>