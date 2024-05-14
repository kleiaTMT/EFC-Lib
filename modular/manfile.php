<?php
    include 'logreq.php';    
    $_SESSION['searchFile']='';

    function printFiles($searchFile){
        global $conn;

        $seqel = 'SELECT * FROM files WHERE name LIKE ? ';
        $stetmt = $conn->prepare($seqel);
        $searchFilePerc = '%'.$searchFile.'%';
        $stetmt->bind_param('s', $searchFilePerc);
        $stetmt->execute();
        $results = $stetmt->get_result();
        
        if (mysqli_num_rows($results) == 0){
            echo 'No files are stored yet...';
        }
        while ($row = $results->fetch_assoc()) {  
            echo '
                <tr>
                    <td>
                        <a href="">Edit</a>
                        <a href="">Hide</a>
                    </td>
                    <td><b>'.$row["filID"].'</b></td>
                    <td><b>'.$row["name"].'.'.$row["ftype"].'</b></td>
                    <td>'.$row["dirGroup"].'</td>
                    <td>'.$row["dateup"].'</td>
                    <td>'.$row["size"]/1000 .' KB'.'</td>
                    <td>'.$row["downloads"].'</td>    
                    <td>';
                        if ($row['hidden'] == 0)
                            echo 'No';
                        else 
                            echo 'Yes';
                        echo '
                    </td>
                </tr>
            ';
        }
    }
    ?>
    <body id="myBody">
        <input type="text" id="myInput" onkeyup="myFilter()" placeholder="Search for file names..." />
        
        <table id="myTable">
            <thead class="sticky-top">
                <th>Action</th>
                <th>File ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>Date Uploaded</th>
                <th>Size</th>
                <th>Downloads</th>
                <th>Hidden?</th>
            </thead>    
            <tbody>
                <?php print printFiles($_SESSION['searchFile']); ?>
            </tbody>
        </table>
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

