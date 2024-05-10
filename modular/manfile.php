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
                    <td>'.$row["name"].'.'.$row["ftype"].'</td>
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
    <body>
        <input type="text" id="myInput" onkeyup="myFilter()" placeholder="Search for file names..." />
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Launch demo modal
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
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

