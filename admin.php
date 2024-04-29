<?php
    include "./modular/filesLogic.php";
    @session_start();

    $_SESSION['admain'] = 'Dashboard';
    
?>

<!doctype HTML>
<html>
    <head>
        <title>Admin Page</title>
        <link rel="stylesheet" href="">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #ffffff;">
            <div class="container-fluid">
                <a class="navbar-brand">
                    <img id="uploads" src="assets/ECF.lib-logo.png" alt="EFC Library Logo" width="240" height="70">
                </a>
            </div>
        </nav>
        <div class="main-container">
            <div class="left-container">
                <img src="" id="Dashboard" alt="Dashboard">
                <img src="" id="ManUse" alt="Manage Users">
                <img src="" id="ManFile" alt="Manage Files">
                <img src="" id="FfApprove" alt="Files for Approval">
            </div>
            <div class="right-container" id="replaceThis">
                <?php 
                    switch($_SESSION['admain']){
                        case 'Dashboard':
                            print('');
                            break;
                        case 'ManUse':
                            print('');
                            break;
                        case 'ManFile':
                            print('');
                            break;
                        case 'FfApprove':
                            print('');
                            break;
                    }
                ?>
            </div>
        </div>
    </body>    
</html>
