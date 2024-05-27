<?php 
    include "filesLogic.php";
    @session_start();

    if(!empty($_POST['newDirect'])){
        $_SESSION['dirt'] = $_POST['newDirect'];
    }
    global $conn;
    $start = 0;
    $perpage = 10;

    if(isset($_GET['page'])){
        $page = $_GET['page'];
    } else
        $page = 1;

    $start_from = ($page-1) * $perpage ;

    // QUERY FOR FILTERING BETWEEN DIRECTORIES, HIDDEN, AND APPROVED FILES
    $hQuery = "SELECT * FROM files WHERE dirGroup=? AND hidden=0 AND state=1 ORDER BY name LIMIT ?, ?";
    $hstm = $conn->prepare($hQuery);
    $hstm->bind_param("sii", $_SESSION['dirt'], $start_from, $perpage);
    $hstm->execute();
    $hres = $hstm->get_result();

    // QUERY FOR COUNTING THE PAGES FOR ALL FILES DISPLAYED
    $iQuery = "SELECT * FROM files WHERE dirGroup=? AND hidden=0 AND state=1";
    $istm = $conn->prepare($iQuery);
    $istm->bind_param("s", $_SESSION['dirt']);
    $istm->execute();
    $ires = $istm->get_result();
    $isum = mysqli_num_rows($ires);

    $hpages = ceil($isum / $perpage);
?>
<div class="replace" id="repla">
    <input class="search" type="text" id="myInput" onkeyup="myFilter()" placeholder="Search for file names..." />

    <!-- Uploading a file in the database and the pc system // Connects to filesLogic.php when the button is clicked -->
    <form class="upsload" action="main.php" method="post" enctype="multipart/form-data" >
        <button class="btn btn-sm btn-primary" type="submit" name="save">UPLOAD</button>    
        <input type="file" name="myfile">    
    </form>
    
    <!-- Table headers with sortable headers -->
    <table class="overflow" id="myTable2">
        <thead class="sticky-top">
            <th>Action</th>
            <th class="sorta" onclick="sortTable(1)">Name</th>
            <th class="sorta" onclick="sortTable(2)">Type</th>
            <th class="sorta" onclick="sortTable(3)">Date Uploaded</th>
            <th>Size (in KB)</th>
            <th>Downloads</th>
        </thead>
        <!-- Table body for list of files -->
        <tbody id="conTent">
            <?php
                while ($row = $hres->fetch_assoc()) {  
                    echo "
                        <tr>
                            <td width=10%><a href='main.php?file_id=".$row['filID']."'>Download</a></td>
                            <td width=50%><b>".$row['name']."</b></td>
                            <td>".$row['ftype']."</td>
                            <td>".$row['dateup']."</td>
                            <td>".floor($row['size'] / 1000) . ' KB'."</td>
                            <td>".$row['downloads']."</td>
                        </tr>
                    ";
                }
            ?>
        </tbody>
    </table>
    <?php include_once "pagination.php"; ?>
</div>