<?php 
    include "./modular/filesLogic.php";



?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Profile Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="./styless/styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="scripts.js"></script>
    </head>
    <body class="profile-card">
        <?php include './modular/navbar.php'; ?>
        <div>
            <div class="prof-info">
                <table class="prof-table">
                    <tr>
                        <td>Name</td>
                        <td><?php echo $_SESSION['uname'];?></td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td><?php echo $_SESSION['emailAddr'];?></td>
                    </tr>
                    <tr>
                        <td>Company/Branch</td>
                        <td><?php echo $_SESSION['company'];?></td>
                    </tr>
                    <tr>
                        <td>Last Visit</td>
                        <td><?php echo $_SESSION['lastdate'];?></td>
                    </tr>
                    <tr>
                        <td>Date Created</td>
                        <td><?php echo $_SESSION['datecreate'];?></td>
                    </tr>
                </table>
            </div>
            <div class="prof-btns">
                <button type="button" class="btn btn-primary">Change Password</button>
            </div>
        </div>
    </body>
</html>