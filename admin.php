<?php
    include "./modular/filesLogic.php";
    @session_start();

    if(empty($_SESSION['admain'])) {
        $_SESSION['admain'] = 'Dashboard';
    }

    if($_SESSION['utype'] != "2"){
        header("Location: ./main.php?error=UNAUTHORIZED_ACCESS");
    }

?>

<!doctype HTML>
<html>
    <head>
        <title>Admin Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="./styless/styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="scripts.js"></script>
    </head>
    <style>
        .left-container{
            float: left;
            z-index: -1;
            width: 5%;
            height: 88vh;
            position: relative;
            text-align: center;
            padding: 1.5vh;
        }
        .right-container{
            z-index: -1;
            float: right;
            width: 95%;
            height: 88vh;
            position: relative;
            border-left: 4px solid #ef0000;
            overflow-y: auto;
            background-image: url("./assets/folders-opa5.png");
            background-position: center;
            background-size: 30vw;
            background-repeat: no-repeat;
        }
        .modal{
            z-index: 50000;
        }
        .navbar{
            z-index: 4;
        }
        .left-container img {
            width: 2vw;
            margin-top: 20%;
            margin-bottom: 30%;
        }
        .left-container img:hover {
            cursor: pointer;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#myLogos').on('click', 'img', function(event) {
                let seleID = $(this).attr('id');
                console.log(seleID);
                $('#replaceThis').load("./modular/replaceThis.php", {
                    newDis : seleID
                });
            });
        });
    </script>
    <body>
        <?php include './modular/navbar.php'; ?>
        <div class="main-container" id="myLogos">
            <div class="left-container bg-light" id="left-container">
                <img src="./assets/dashboard.png" id="Dashboard" alt="Dashboard">
                <img src="./assets/users.png" id="ManUse" alt="Manage Users">
                <img src="./assets/files.png" id="ManFile" alt="Manage Files">
                <img src="./assets/approval.png" id="FfApprove" alt="Files for Approval">
            </div>
            <div class="right-container prev-panel" id="replaceThis">
               <?php 
                    include './modular/replaceThis.php';
                ?>
            </div>
        </div>
    </body>    
</html>
