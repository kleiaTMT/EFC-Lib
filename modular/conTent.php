<?php 
    include "filesLogic.php";
    @session_start();

    if(!empty($_POST['newDirect'])){
        $_SESSION['dirt'] = $_POST['newDirect'];
    }

    ?>
    <!-- Table headers with sortable headers -->
    <table class="overflow" id="myTable2">
        <!--<div class="url">
            <input class="urlDirect" type="text" id="country" name="country" value="<?php echo $_SESSION['dirt']; ?>" readonly><br>
        </div>-->
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
                global $conn;

                $scanf = scandir($dire);

                unset($scanf[array_search('.', $scanf, true)]);
                unset($scanf[array_search('..', $scanf, true)]);

                $dTP = explode("/", $dire);
                $dTPC = sizeof($dTP)-1;
                $dTPath = $dTP[$dTPC];
            
                foreach($scanf as $file){

                    $inamewoext = pathinfo($file, PATHINFO_FILENAME);
                    $seql = "SELECT * FROM files WHERE name=? and dirGroup=?";
                    $stmt = $conn->prepare($seql);
                    $stmt->bind_param("ss", $inamewoext, $dTPath);
                    $stmt->execute();
                    $resi = $stmt->get_result();

                    while ($row = $resi->fetch_assoc()) {  
                        echo "
                            <tr>
                                <td><b>".$row['name']."</b></td>
                                <td>".$row['dateup']."</td>
                                <td>".$row['ftype']."</td>
                                <td>".floor($row['size'] / 1000) . ' KB'."</td>
                                <td>".$row['downloads']."</td>
                                <td><a href='main.php?file_id=".$row['filID']."'>Download</a></td>
                            </tr>
                        ";
                    }
                }          
            }
            print listTableContents($_SESSION['dirt'])
        ?>
        </tbody>
    </table>