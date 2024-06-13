<?php
    include 'logreq.php';    

    $start = 0;
    $perpage = 9;

    if(isset($_GET['page'])){
        $page = $_GET['page'];
    } else
        $page = 1;
    $root = "C:\\xampp\htdocs\\testBS";//$_SERVER['DOCUMENT_ROOT'];
    $start_from = ($page-1) * $perpage ;

    $jQuery = "SELECT * FROM files WHERE state = 1 ORDER BY filID LIMIT ?, ?";
    $jstm = $conn->prepare($jQuery);
    $jstm->bind_param("ii", $start_from, $perpage);
    $jstm->execute();
    $jres = $jstm->get_result();

    $kQuery = "SELECT * FROM files WHERE state = 1";
    $kstm = $conn->prepare($kQuery);
    $kstm->execute();
    $kres = $kstm->get_result();
    $ksum = mysqli_num_rows($kres);

    $hpages = ceil($ksum / $perpage);

    $mQuery = "SELECT DISTINCT dirGroup FROM files";
    $mstm = $conn->prepare($mQuery);
    $mstm->execute();
    $mres = $mstm->get_result();
    
    $directory = $root . "\uploads\\";

    $directs = scandir($directory);
    unset($directs[array_search('.', $directs, true)]);
    unset($directs[array_search('..', $directs, true)]);
    $dir = array("uploads");

    foreach($directs as $direc){
        if(is_dir($directory . $direc)) {
            array_push($dir, $direc);
        }
    }
    //require "scanfiles.php";
    ?>
    <body id="myBody">
        <input class="search" type="text" id="myInput" onkeyup="myFilter()" placeholder="Search for file names..." />

        <!-- Uploading a file in the database and the pc system // Connects to filesLogic.php when the button is clicked -->
        <div class="upfile">
            <form class="upsload" action="admin.php" method="post" enctype="multipart/form-data" >
                <button class="btn btn-sm btn-primary" type="submit" name="save">UPLOAD</button>    
                <input type="file" name="myfile">    
            </form>
        </div>

        <!-- Adding a folder form // Connects to filesLogic.php when the button is clicked --> 
        <div class="addfile">
            <form class="upsload" method="POST">
                <input type="text" id="pname" name="pname" placeholder="Add folder directory">
                <input type="submit" class="btn btn-sm btn-primary" value="ADD">
            </form>
        </div>

        <table id="myTable">
            <thead class="sticky-top">
                <th width="5%">Action</th>
                <th width="6%">File ID</th>
                <th width="35%">Name</th>
                <th width="6%">Location</th>
                <th width="14%">Uploader</th>
                <th width="11%">Date Uploaded</th>
                <th width="9%">Size</th>
                <th width="8%">Downloads</th>
                <th width="4%">Hidden?</th>
            </thead>    
            <tbody>
                <?php 
                    while ($row = $jres->fetch_assoc()){
                        $formatted = $row['size'] / 1000;
                        $finformat = number_format($formatted, 2, '.', ',');
                        if($row['dirGroup'] == "uploads") {
                            $ifrm = $root.'\\'.$row['dirGroup'].'\\'.$row['name'].'.'.$row['ftype'];
                        } else {
                            $ifrm = $root.'\uploads\\'.$row['dirGroup'].'\\'.$row['name'].'.'.$row['ftype'];
                        }
                        ?>
                            <tr>
                                <td> 
                                    <!--MODAL FOR EDITING FILE FORM-->
                                    <button type="button" id="edit" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editFile<?php echo $row['filID']?>">✎</button>
                                    <div class="modal fade" id="editFile<?php echo $row['filID']?>" tabindex="-1" aria-labelledby="editFileForm<?php echo $row['filID']?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="editFileForm"<?php echo $row['filID']?>>Edit File Form</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="leftlabel">
                                                                <input type="hidden" name="fileID" value="<?php echo $row['filID'];?>">
                                                            <label>Name:</label>
                                                                <input type="text" name="fname" value="<?php echo $row['name'].'.'.$row['ftype'];?>" disabled>
                                                            <label>New File Name:</label>
                                                                <input type="text" name="newfname" placeholder="New File Name"/>
                                                            <label>Uploader:</label>
                                                                <input type="text" name="uploader" value="<?php echo $row['uname'];?>" disabled>
                                                            <label>Folder Loc:</label>
                                                                <select name="dgroup" id="dgroup">
                                                                    <?php
                                                                        foreach ($dir as $dira){ ?>
                                                                            <option value="<?php echo $dira;?>"><?php echo $dira;?></option> <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                            <label>File Size:</label>
                                                                <input type="text" name="fsize" value="<?php echo $finformat.' KB';?>" disabled>
                                                            <label>Date of Approval:</label>
                                                                <input type="text" name="dateappr" value="<?php echo $row['dateappr'];?>" disabled>
                                                            <label>File Status:</label>
                                                                <select name="hidden" id="hidden">
                                                                    <?php 
                                                                        if($row['hidden'] == 0) {?>
                                                                            <option selected="selected" value="0">Displayed</option>
                                                                            <option value="1">Hidden</option> <?php
                                                                        } else {?>
                                                                            <option value="0">Displayed</option>
                                                                            <option selected="selected" value="1">Hidden</option> <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary" name="editfiles">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align:center">
                                    <b><?php echo $row["filID"]; ?></b>
                                </td>
                                <td>
                                    <b><?php echo $row['name'].'.'.$row["ftype"]; ?></b>
                                </td>
                                <td>
                                    <b>
                                        <?php echo $row["dirGroup"];?>
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        <?php echo $row["uname"]; ?>
                                    </b>
                                </td>
                                <td style="text-align:center">
                                    <b>
                                        <?php echo $row["dateup"]; ?>
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        <?php echo $finformat.' KB'; ?>
                                    </b>
                                </td>
                                <td style="text-align:center">
                                    <b>
                                        <?php echo $row["downloads"]; ?>
                                    </b>
                                </td>    
                                <td style="text-align:center">
                                    <b>
                                        <?php
                                            if ($row['hidden'] == 0)
                                                echo 'No';
                                            else 
                                                echo 'Yes';
                                        ?>
                                    </b>
                                </td>
                            </tr>
                        <?php
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

