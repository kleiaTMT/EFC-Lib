<?php
    include"filesLogic.php";

    $uname = $_POST['uname'];
    $emailAddr = $_POST['emailAddr'];
    $passw = $_POST['passw'];
    $enc = "EFC";
    $passw_enc = sha1($passw.$enc);
    
    if(isset($_POST["login"])) {
        $logQuery = "SELECT count(*) FROM users WHERE emailAddr=? and passw=?";
        $lQ = $conn->prepare($logQuery);
        $lQ->bind_param("ss", $emailAddr, $passw_enc);
        $lQ->execute();
        $lQres = $lq->get_result();

        while ($row = $resi->fetch_assoc()) {
            if($row['emailAddr'] == $emailAddr && $row['passw'] == $passw_enc) {
                $_SESSION['emailAddr'] = $row['emailAddr'];
                $_SESSION['passw'] = $row['passw'];
                $_SESSION['usrID'] = $row['uid'];
                $_SESSION['uname'] = $row['uname'];
                $_SESSION['utype'] = $row['utype']; 
                header("Location: ../main.php");
                echo "
                    <div class='alert alert-success alert-dismissible fade show fixed-top' role='alert'>
                        Logged in successfully!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                ";
                exit();
                
            } else { 
                header("Location: ../main.php?error=Incorrect Email and password");
            }
        }
    }

    if(isset($_POST["signup"])) {
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