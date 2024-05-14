<?php
    include "filesLogic.php";

    $uname = '';
    $emailAddr = '';
    $passw = '';
    $enc = "EFC";
    
    
    if(isset($_POST["login"])) {

        $emailAddr = $_POST['emailAddr'];
        $passw = $_POST['passw'];
        $passw_enc = sha1($passw.$enc);

        //query for checking if the input records are in the system
        $logQuery = "SELECT * FROM users WHERE emailAddr=? and passw=?";
        $lQ = $conn->prepare($logQuery);
        $lQ->bind_param("ss", $emailAddr, $passw_enc);
        $lQ->execute();
        $lQres = $lQ->get_result();

        while ($row = $lQres->fetch_assoc()) {

            //checks if the input is inside the system
            if($row['emailAddr'] == $emailAddr && $row['passw'] == $passw_enc) {
                $_SESSION['emailAddr'] = $row['emailAddr'];
                $_SESSION['passw'] = $row['passw'];
                $_SESSION['uid'] = $row['uid'];
                $_SESSION['company'] = $row['company'];
                $_SESSION['uname'] = $row['uname'];
                $_SESSION['utype'] = $row['usertype'];
                $vuid = $_SESSION['uid'];
                $vdate = date("Y-m-d");

                //query for checking if the user already has a record of logging in today in the system
                $loQueCheck = 'SELECT * FROM visits WHERE uid=? and lastvisit=?';
                $lQCc = $conn->prepare($loQueCheck);
                $lQCc->bind_param('is', $vuid, $vdate);
                $lQCc->execute();
                $lQCcres = $lQCc->get_result();

                //if no record, proceed to record the latest date entry to "visits" table and "lastdate" element in users entry
                if ($lQCcres->num_rows == 0){
                    $loQue = 'INSERT INTO visits (uid, lastvisit) VALUES (?, ?)';
                    $lQuCc = $conn->prepare($loQue);
                    $lQuCc->bind_param('is', $vuid, $vdate);
                    $lQuCc->execute();

                    $uQue = 'UPDATE users SET lastdate = ? WHERE uid = ?';
                    $uQueC = $conn->prepare($uQue);
                    $uQueC->bind_param('si', $vdate, $vuid);
                    $uQueC->execute();
                }
                header("Location: ../main.php");
                exit();
                
            } else { 
                header("Location: ../index.php?error=Incorrect Email or Password");
                exit();
            }
        }
    }

    if(isset($_POST["signup"])) {
        $emailAddr = $_POST['emailAddr'];
        $uname = $_POST['uname'];
        $passw = $_POST['passw'];
        $comp = $_POST['company'];
        $passw_enc = sha1($passw.$enc);
        $cdate = date("Y-m-d");

        $regQueryCheck = "SELECT * FROM users WHERE emailAddr=?";
        $rQC = $conn->prepare($regQueryCheck);
        $rQC->bind_param("s", $emailAddr);
        $rQC->execute();
        $rQCres = $rQC->get_result();

        if ($rQCres->num_rows > 0) {
            echo "
                <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                    Email Address already exists in the database.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            ";
            echo $emailAddr;
        } else {
            $rQuery = "INSERT INTO users (uname, emailAddr, passw, company, datecreate, usertype) VALUES (?, ?, ?, ?, ?, 0)";
            $rQ = $conn->prepare($rQuery);
            $rQ->bind_param("sssss", $uname, $emailAddr, $passw_enc, $comp, $cdate);
            $rQ->execute();

            header("Location: ../admin.php");
            echo "
                <div class='alert alert-success alert-dismissible fade show fixed-top' role='alert'>
                    Account added successfully!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            ";
        }
    }

    if(isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header('Location: ../index.php');
    }