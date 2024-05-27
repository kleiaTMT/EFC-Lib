<html>
    <style>
        .nav-link{
            text-wrap: nowrap;
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #ffffff;">
        <div class="container-fluid">
            <a class="navbar-brand" href="./main.php">
                <img id="uploads" src="assets/efc-corpo.png" alt="EFC Library Logo" class="logo" width="175">
            </a>
        </div>
        <div class="collapse navbar-collapse " id="navbarText">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#">Profile</a>
                </li>
                <?php
                    if($_SESSION['utype'] == '2'){
                        echo '<li class="nav-item">
                            <a class="nav-link" href="./admin.php">Admin Panel</a>
                        </li>'; 
                    }
                ?>
                <form action="./modular/logreq.php" class="d-flex" method="POST">
                    <li class="nav-item">
                        <button type="submit" class="nav-link active" aria-current="page" name="logout">Logout</s>
                    </li>
                </form>
            </ul>
        </div>
    </nav>
    <style>
        #navbarText {
            text-align: center;
            font-size: 20px;
            margin-right: 15px;
        }
        #navbarText li{
            margin-right: 5px;
            margin-left: 5px;
        }
    </style>
</html>