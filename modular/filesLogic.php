<?php
    @session_start();
// WARNING SO FAR MGA FIXED VALUES YUNG DIRECTORIES DITO SO LAHAT SA UPLOADS PA MUNA NALALAGAY

// connect to the database
    $conn = mysqli_connect('localhost', 'root', '', 'file-management');

    $sql = "SELECT * FROM files";
    $result = mysqli_query($conn, $sql);

    $files = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // Uploads files
    if (isset($_POST['save'])) { // if save button on the form is clicked
        // name of the uploaded file
        $filename = $_FILES['myfile']['name'];

        // destination of the file on the server
        $journey = $_SESSION['dirt'];
        $destination = $journey. '/' . $filename;

        // get the file extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $fnamewoext = pathinfo($filename, PATHINFO_FILENAME);
        // the physical file on a temporary uploads directory on the server
        $file = $_FILES['myfile']['tmp_name'];
        $size = $_FILES['myfile']['size'];
        $uploader = $_SESSION['uname'];
        $dateup = date("Y-m-d");

        $sqql = "SELECT * FROM files WHERE name='$fnamewoext' AND ftype='$extension'";
        $check = mysqli_query($conn, $sqql);

        // Condition for allowing specific types of files to be uploaded
        if (!in_array($extension, ['zip', 'pdf', 'docx', 'ppt', 'jpg', 'png', 'jpeg', 'xlsx', 'txt'])) {
            echo "
                <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                    You file extension must be .zip, .pdf, .docx, .ppt, .jpg/jpeg, .png, or .xlsx.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            ";
        // file shouldn't be larger than 50Megabytes
        } elseif ($_FILES['myfile']['size'] > 50000000) { 
            echo "
                <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                    File too large!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            ";
        // Condition for checking if the file already exists in the database.
        } elseif (mysqli_num_rows($check) > 0) {
            echo "
                <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                    File already exists in the database.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            ";
        } else {
            // move the uploaded (temporary) file to the specified destination
            if (move_uploaded_file($file, $destination)) {
                if ($_SESSION['utype'] > 0) {
                    $sql = "INSERT INTO files (name, ftype, uname, dateup, size, downloads, dirGroup, state) VALUES ('$fnamewoext', '$extension', '$uploader', '$dateup', $size, 0, '$journey', 1)";
                } else {
                    $sql = "INSERT INTO files (name, ftype, uname, dateup, size, downloads, dirGroup, state) VALUES ('$fnamewoext', '$extension', '$uploader', '$dateup', $size, 0, '$journey', 0)";
                }

                if (mysqli_query($conn, $sql)) {
                    echo "
                        <div class='alert alert-success alert-dismissible fade show fixed-top' role='alert'>
                            File uploaded successfully!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    ";
                }
            } else {
                echo "
                    <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                        Failed to upload file.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                ";
            }
        }
    }

    // Downloads files
    if (isset($_GET['file_id'])) {
        $id = $_GET['file_id'];

        // fetch file to download from database
        $sql = "SELECT * FROM files WHERE filID=?";
        $stat = $conn->prepare($sql);
        $stat->bind_param("i", $id);
        $stat->execute();
        $result = $stat->get_result();
        $file = $result->fetch_assoc();
        
        if($_SESSION['dirt'] != "uploads"){
            $filepath = '.\uploads\\'.$_SESSION['dirt']. '\\' . $file['name']. '.' . $file['ftype'];
        } else { 
            $filepath = '.\\'.$_SESSION['dirt']. '\\' . $file['name']. '.' . $file['ftype'];
        }

        switch ($file['ftype']) {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            default:    $ctype="application/force-download";
        }

        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: $ctype');
            header('Content-Disposition: attachment; filename=' . basename($filepath));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);

            // Now update downloads count
            $newCount = $file['downloads'] + 1;
           
            $updateQuery = "UPDATE files SET downloads=? WHERE filID=?";
            $upQ = $conn->prepare($updateQuery);
            $upQ->bind_param("ii", $newCount, $id);
            $upQ->execute();

            exit;
        }
    }
    // creating a directory
    if(isset($_POST["pname"])){
        $dirName = $_POST["pname"];

        $nope = "/";
        $nope2 = "\\";

        // check if file exists
        if(file_exists($_SESSION['dirt'] . '/' . $dirName)) {
            echo "
                <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                    File already exists.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            ";
        } 
        elseif(strpos($dirName, $nope) !== FALSE) {
            echo "
                <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                    Invalid folder name format!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            ";
        }
        elseif(strpos($dirName, $nope2) !== FALSE) {
            echo "
                <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                    Invalid folder name format!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            ";
        }
        else { 
            // create folder
            mkdir($_SESSION['dirt'] . '/' . $dirName); // Creates a folder in this directory named whatever value returned by pname input
        }
    }

    if(isset($_POST['approve'])) {
        $filID = $_POST['approve'];
        $dateappr = date("Y-m-d");

        $apprQuery = "UPDATE files SET dateappr = ?, state = 1 WHERE filID = ?";
        $apprStmt = $conn->prepare($apprQuery);
        $apprStmt->bind_param('si', $dateappr, $filID);
        $apprStmt->execute();
        echo "
            <div class='alert alert-success alert-dismissible fade show fixed-top' role='alert'>
                File approved!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
        ";

    }

    if(isset($_POST['decline'])) {
        $filID = $_POST['decline'];

        $selQuery = "SELECT name, ftype from files WHERE filID = ?";
        $selStmt = $conn->prepare($selQuery);
        $selStmt->bind_param('i', $filID);
        $selStmt->execute();
        $selRes = $selStmt->get_result();


        $decQuery = "DELETE FROM files WHERE filID = ?";
        $decStmt = $conn->prepare($decQuery);
        $decStmt->bind_param('i', $filID);
        $decStmt->execute();

        $direct = $_SESSION['dirt'];
        while($tow = $selRes->fetch_assoc()){
            $name = $tow['name'].'.'.$tow['ftype'];

            $delPath = '../'.$direct.'/'.$name;

            unlink($delPath);
            header("Location: ../admin.php");
        }
    }

    if(isset($_POST['editfiles'])){
        $filID = $_POST['fileID'];
        $newdGroup = $_POST['dgroup'];
        $newstat = $_POST['hidden'];
        $base = $_SERVER['DOCUMENT_ROOT'];

        if(!empty($_POST['newfname'])){
            $newfname = $_POST['newfname'];
            
            $edQuery = "SELECT name FROM files WHERE name = ?";
            $edstm = $conn->prepare($edQuery);
            $edstm->bind_param("s", $newfname);
            $edstm->execute();
            $edres = $edstm->get_result();

            $pdQuery = "SELECT name, dirGroup, ftype FROM files WHERE filID = ?";
            $pdstm = $conn->prepare($pdQuery);
            $pdstm->bind_param("i", $filID);
            $pdstm->execute();
            $pdres = $pdstm->get_result();
            
            // CHECKS IF FILE NAME ALREADY EXISTS
            if (mysqli_num_rows($edres) > 0){
                echo "
                    <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                        File name already exists.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                ";
                header("Location: ../admin.php?error=File already exists");
            } else {
                while($set = $pdres->fetch_assoc()) {
                    // CHECKS IF THE FILE WAS MOVED
                    if($set['dirGroup'] != $newdGroup) {
                        if($newdGroup == "uploads"){
                            echo 1;
                            rename($base."\\"."uploads\\".$set['dirGroup']."\\".$set['name'].'.'.$set['ftype'], $base."\\".$newdGroup."\\".$newfname.'.'.$set['ftype']);
                        } elseif ($set['dirGroup'] == "uploads"){
                            echo 2;
                            rename($base."\\".$set['dirGroup']."\\".$set['name'].'.'.$set['ftype'], $base."\\"."uploads\\".$newdGroup."\\".$newfname.'.'.$set['ftype']);
                        } else {
                            echo 3;
                            rename($base."\\"."uploads\\".$set['dirGroup']."\\".$set['name'].'.'.$set['ftype'], $base."\\"."uploads\\".$newdGroup."\\".$newfname.'.'.$set['ftype']);
                        }
                    } else {
                        if($set['dirGroup'] == "uploads") {
                            echo 4;
                            rename($base."\\".$set['dirGroup']."\\".$set['name'].'.'.$set['ftype'], $base."\\".$set['dirGroup']."\\".$newfname.'.'.$set['ftype']);
                        } else {
                            echo 5;
                            rename($base."\\"."uploads\\".$set['dirGroup']."\\".$set['name'].'.'.$set['ftype'], $base."\\"."uploads\\".$set['dirGroup']."\\".$newfname.'.'.$set['ftype']);
                        }
                    }
                }
                echo 6;
                $upQuery = "UPDATE files SET name = ?, dirGroup = ?, hidden = ? WHERE filID = ?";
                $upStm = $conn->prepare($upQuery);
                $upStm->bind_param("ssii", $newfname, $newdGroup, $newstat, $filID);
            }
        } else {
            $mQuery = "SELECT dirGroup, name, ftype FROM files WHERE filID = ?";
            $mstm = $conn->prepare($mQuery);
            $mstm->bind_param("i", $filID);
            $mstm->execute();
            $mres = $mstm->get_result();
            while($oops = $mres->fetch_assoc()) {
                if($oops['dirGroup'] != $newdGroup) {
                    if($newdGroup == "uploads"){
                        rename($base."\\"."uploads\\".$oops['dirGroup']."\\".$oops['name'].'.'.$oops['ftype'], $base."\\".$newdGroup."\\".$oops['name'].'.'.$oops['ftype']);
                    } elseif ($oops['dirGroup'] == "uploads"){
                        rename($base."\\".$oops['dirGroup']."\\".$oops['name'].'.'.$oops['ftype'], $base."\\"."uploads\\".$newdGroup."\\".$oops['name'].'.'.$oops['ftype']);
                    } else {
                        rename($base."\\"."uploads\\".$oops['dirGroup']."\\".$oops['name'].'.'.$oops['ftype'], $base."\\"."uploads\\".$newdGroup."\\".$oops['name'].'.'.$oops['ftype']);
                    }
                } else {
                    if($oops['dirGroup'] == "uploads") {
                        rename($base."\\".$oops['dirGroup']."\\".$oops['name'].'.'.$oops['ftype'], $base."\\".$oops['dirGroup']."\\".$oops['name'].'.'.$oops['ftype']);
                    } else {
                        rename($base."\\"."uploads\\".$oops['dirGroup']."\\".$oops['name'].'.'.$oops['ftype'], $base."\\"."uploads\\".$oops['dirGroup']."\\".$oops['name'].'.'.$oops['ftype']);
                    }
                }
            }


            $upQuery = "UPDATE files SET dirGroup = ?, hidden = ? WHERE filID = ?";
            $upStm = $conn->prepare($upQuery);
            $upStm->bind_param("sii", $newdGroup, $newstat, $filID);
        }
        $upStm->execute();
        echo "
            <div class='alert alert-success alert-dismissible fade show fixed-top' role='alert'>
                File updated successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
        ";
    }