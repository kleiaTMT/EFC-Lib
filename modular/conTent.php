<?php 
    include "filesLogic.php";
    @session_start();


    $newDirect = $_POST['newDirect'];
    $_SESSION['dirt'] = $newDirect;
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

            $seql = "SELECT * FROM files WHERE name='$inamewoext' and dirGroup='$dTPath'";
            $resi = mysqli_query($conn, $seql);

            while($row = mysqli_fetch_assoc($resi)) {  
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
    print listTableContents($_SESSION['dirt'])
?>