<?php
    include "./modular/filesLogic.php";
    @session_start();

    $_SESSION['admain'] = 'ManUse';
    if($_SESSION['utype'] != 2){
        header("Location: ./main.php?error=ILLEGAL_ACCESS");
      }
?>

<!doctype HTML>
<html>
    <head>
        <title>Admin Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="./styless/styles.css">
        <link rel="stylesheet" href="./scripts.js">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </head>
    <style>
        .left-container{
            float: left;
            width: 4%;
            height: 83.5vh;
            position: relative;
            text-align: center;
            padding: 1.5vh;
        }
        .right-container{
            float: right;
            width: 96%;
            height: 83.5vh;
            position: relative;
            border-left: 4px solid #ef0000;
            overflow-y: scroll;
        }
        
    </style>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #ffffff;">
            <div class="container-fluid">
                <a class="navbar-brand">
                    <img id="uploads" src="assets/ECF.lib-logo.png" alt="EFC Library Logo" width="240" height="70">
                </a>
            </div>
        </nav>
        <div class="main-container">
            <div class="left-container bg-light">
                <a href=""><img src="./assets/dashboard.png" width="20vh" length="20vh" id="Dashboard" alt="Dashboard"></a>
                <a href=""><img src="./assets/users.png" width="20vh" length="20vh" id="ManUse" alt="Manage Users"></a>
                <a href=""><img src="./assets/files.png" width="20vh" length="20vh" id="ManFile" alt="Manage Files"></a>
                <a href=""><img src="./assets/approval.png" width="20vh" length="20vh" id="FfApprove" alt="Files for Approval"></a>
            </div>
            <div class="right-container prev-panel fixed-top" id="replaceThis">
               <?php 
                    include './modular/replaceThis.php';
                ?>
            </div>
        </div>
    </body>    
</html>
