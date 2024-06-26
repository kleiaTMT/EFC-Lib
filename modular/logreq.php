<?php
    include "filesLogic.php";

    $uname = '';
    $emailAddr = '';
    $passw = '';
    $enc = "EFC";
    
    //PROCESSES REQUEST FOR LOGGING IN
    if(isset($_POST["login"])) {

        $emailAddr = $_POST['emailAddr'];
        $passw = $_POST['passw'];
        $passw_enc = sha1($passw.$enc);

        //query for checking if the input records are in the system
        $logQuery = "SELECT * FROM users WHERE emailAddr = ?";
        $lQ = $conn->prepare($logQuery);
        $lQ->bind_param("s", $emailAddr);
        $lQ->execute();
        $lQres = $lQ->get_result();

        if(mysqli_num_rows($lQres) > 0) {
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
                    $_SESSION['datecreate'] = $row['datecreate'];
                    $_SESSION['lastdate'] = $row['lastdate'];
                    header("Location: ../main.php");
                    exit();
                } else {
                    header("Location: ../index.php?error=Incorrect Email or Password");
                    exit();
                }
            }        
        } else { 
            header("Location: ../index.php?error=Incorrect Email or Password");
            exit();
        }
    }

    //PROCESS FOR ADDING AN ACCOUNT
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

    //PROCESS FOR LOGGING OUT
    if(isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header('Location: ../index.php');
    }

    //PROCESS FOR EDITING AN EXISTING ACCOUNT
    if(isset($_POST['edittrue'])) {
        $euid = $_POST['uid'];
        $name = $_POST['uname'];
        $newemail = $_POST['emailAddr'];
        $com = $_POST['company'];
        $atype = $_POST['usertype'];
        $newp = $_POST['newpass'];
        $cnewp = $_POST['cnewpass'];
        
        $euQuery = "UPDATE users SET uname = ?, emailAddr = ?, company = ?, usertype = ? WHERE uid = ?";
        $euStm = $conn->prepare($euQuery);
        $euStm->bind_param("sssii", $name, $newemail, $com, $atype, $euid);
        $euStm->execute();

        // CHECKS IF NEW PASSWORD INPUT FIELD IS EMPTY
        if(!empty($newp) && !empty($cnewp)) {
            if($newp === $cnewp){
                $pass_new = sha1($newp.$enc);
                $upnewpwQuery = "UPDATE users SET passw = ? WHERE uid = ?";
                $upnewRun = $conn->prepare($upnewpwQuery);
                $upnewRun->bind_param("si", $pass_new, $euid);
                $upnewRun->execute();
            }
        }
        echo "
            <div class='alert alert-success alert-dismissible fade show fixed-top' role='alert'>
                User updated successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
        ";
    }

    //PROCESS FOR CHANGING THE PASSWORD OF A REGULAR USER
    if(isset($_POST['changePass'])) {
        $uid = $_SESSION['uid'];
        $oldPW = $_POST['oldpw'];
        $newPW = $_POST['newpw'];
        $renewPW = $_POST['renewpw'];
        $oldPW_enc = sha1($oldPW.$enc);

        $chanQuery = "SELECT passw FROM users WHERE uid = ? and passw = ?";
        $chanStm = $conn->prepare($chanQuery);
        $chanStm->bind_param("is", $uid, $oldPW_enc);
        $chanStm->execute();
        $chanRes = $chanStm->get_result();

        if(mysqli_num_rows($chanRes) > 0) {
            if ($newPW === $renewPW) {
                $finalPW = sha1($newPW.$enc);
                $updaQuery = "UPDATE users SET passw = ? WHERE uid = ?";
                $updaStm = $conn->prepare($updaQuery);
                $updaStm->bind_param("si", $finalPW, $uid);
                $updaStm->execute();
                
                echo "
                    <div class='alert alert-success alert-dismissible fade show fixed-top' role='alert'>
                        Password updated successfully!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                ";
            } else {
                echo "
                    <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                        Passwords do not match.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                ";
            }
        } else {
            echo "
                <div class='alert alert-danger alert-dismissible fade show fixed-top' role='alert'>
                    Incorrect password!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
            ";
        }
    }