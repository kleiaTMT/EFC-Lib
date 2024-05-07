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

        $logQuery = "SELECT * FROM users WHERE emailAddr=? and passw=?";
        $lQ = $conn->prepare($logQuery);
        $lQ->bind_param("ss", $emailAddr, $passw_enc);
        $lQ->execute();
        $lQres = $lQ->get_result();

        while ($row = $lQres->fetch_assoc()) {
            if($row['emailAddr'] == $emailAddr && $row['passw'] == $passw_enc) {
                $_SESSION['emailAddr'] = $row['emailAddr'];
                $_SESSION['passw'] = $row['passw'];
                $_SESSION['uid'] = $row['uid'];
                $_SESSION['uname'] = $row['uname'];
                $_SESSION['utype'] = $row['usertype'];
                header("Location: ../main.php");
                exit();
                
            } else { 
                header("Location: ./login.php?error=Incorrect Email or Password");
                exit();
            }
        }
    }

    if(isset($_POST["signup"])) {
        $emailAddr = $_POST['emailAddr'];
        $passw = $_POST['passw'];
        $passw_enc = sha1($passw.$enc);

        $regQueryCheck = "SELECT count(*) FROM users WHERE emailAddr=?";
        $rQC = $conn->prepare($regQueryCheck);
        $rQC->bind_param("", $emailAddr);
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
        } else {
            $rQuery = "INSERT INTO users (uname, emailAddr, passw, utype) VALUES ?, ?, ?, 0";
            $rQ = $conn->prepare($rQuery);
            $rQ->bind_param("sss", $uname, $emailAddr, $passw_enc);
            $rQ->execute();

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
        header('Location: ./login.php');
    }