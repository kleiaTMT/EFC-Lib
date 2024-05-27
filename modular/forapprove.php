<?php
    include 'logreq.php';    
    $_SESSION['searchFile']='';

    $start = 0;
    $perpage = 9;

    if(isset($_GET['page'])){
        $page = $_GET['page'];
    } else
        $page = 1;

    $start_from = ($page-1) * $perpage ;

    $jQuery = "SELECT * FROM files WHERE state = 0 ORDER BY filID LIMIT ?, ?";
    $jstm = $conn->prepare($jQuery);
    $jstm->bind_param("ii", $start_from, $perpage);
    $jstm->execute();
    $jres = $jstm->get_result();

    $kQuery = "SELECT * FROM files WHERE state = 0";
    $kstm = $conn->prepare($kQuery);
    $kstm->execute();
    $kres = $kstm->get_result();
    $ksum = mysqli_num_rows($kres);

    $hpages = ceil($ksum / $perpage);

    ?>
    <body id="myBody">
        <input class="search" type="text" id="myInput" onkeyup="myFilter()" placeholder="Search for file names..." />

        <table id="myTable">
            <thead class="sticky-top">
                <th width="6%">Action</th>
                <th width="38%">Name</th>
                <th width="6%">Location</th>
                <th width="15%">Uploader</th>
                <th width="12%">Date Requested</th>
                <th width="8%">Size</th>
            </thead>    
            <tbody>
                <?php 
                    while ($row = $jres->fetch_assoc()){
                        $formatted = $row['size'] / 1000;
                        $finformat = number_format($formatted, 2, '.', ',');
                        echo '
                            <tr>
                                <form action="./modular/filesLogic.php" method="POST">
                                    <td>
                                        <button type="submit" id="appro" value="'.$row["filID"].'" name="approve" class="btn btn-sm btn-success">✓</button>
                                        <button type="submit" id="decli" value="'.$row["filID"].'" name="decline" class="btn btn-sm btn-danger">✕</button>  
                                    </td>
                                    <td><b>'.$row["name"].'.'.$row["ftype"].'</b></td>
                                    <td>'.$row["dirGroup"].'</td>
                                    <td>'.$row["uname"].'</td>
                                    <td>'.$row["dateup"].'</td>
                                    <td>'.$finformat .' KB'.'</td> 
                                </form>   
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
        <?php include "pagination.php" ?>
    </body>
    <script type="text/javascript">
        function myFilter() {
        // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[2];
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

