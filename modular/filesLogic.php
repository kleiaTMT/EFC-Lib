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
        $dateup = date("Y-m-d");

        $sqql = "SELECT * FROM files WHERE name='$fnamewoext' AND ftype='$extension' AND dirGroup='$journey'";
        $check = mysqli_query($conn, $sqql);

        // Condition for allowing specific types of files to be uploaded
        if (!in_array($extension, ['zip', 'pdf', 'docx', 'ppt', 'jpg', 'png', 'jpeg', 'xlsx'])) {
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
                $sql = "INSERT INTO files (name, ftype, dateup, size, downloads, dirGroup) VALUES ('$fnamewoext', '$extension', '$dateup', $size, 0, '$journey')";
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
        $sql = "SELECT * FROM files WHERE filID=$id";
        $result = mysqli_query($conn, $sql);

        $file = mysqli_fetch_assoc($result);
        $filepath = $_SESSION['dirt']. '/' . $file['name']. '.' . $file['ftype'];

        switch ($file['ftype']) 
        {
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
            header('Content-Length: ' . filesize($_SESSION['dirt']. '/' . $file['name']. '.' . $file['ftype']));
            readfile($_SESSION['dirt']. '/' . $file['name']. '.' . $file['ftype']);

            // Now update downloads count
            $newCount = $file['downloads'] + 1;
            $updateQuery = "UPDATE files SET downloads=$newCount WHERE filID=$id";
            mysqli_query($conn, $updateQuery);
            exit;
        }
    }
    // creating a directory
    if(isset($_POST["pname"])){
        $dirName = $_POST["pname"];

        // check if file exists
        if(file_exists($_SESSION['dirt'].'/'. $dirName)) {
            echo "
                <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                    File already exists.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            ";
        } 
        else { 
            // create folder
            mkdir("./uploads/" . $dirName); // Creates a folder in this directory named whatever value returned by pname input
        }
    }

    // Not yet working / will be improved upon
    /*class Scan {
        public function scanner($db, $dir) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if (!in_array($file, ['.', '..'])) {
                    $name = $dir . '/' . $file;
                    $type = pathinfo($name, PATHINFO_EXTENSION);
                    $fname = pathinfo($name, PATHINFO_FILENAME);
                    $types = filetype($name);
                    $size = (is_dir($name)) ? -1 : filesize($name);
                    $dateup = date("Y-m-d");
                    $db->query(
                        "INSERT INTO files ('path', 'size', 'dateup', 'ftype', 'dirGroup')
                        VALUES (?s, ?i, ?s, ?s, ?s)
                        ", $fname, $size, $dateup, $type, $dir
                    );

                    if ($types == 'dir') {
                        $this->scanner($db, $name);
                    }
                }
            }
        }
    }*/