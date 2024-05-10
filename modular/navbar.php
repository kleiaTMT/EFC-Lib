<html>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #ffffff;">
        <div class="container-fluid">
            <a class="navbar-brand" href="./main.php">
                <img id="uploads" src="assets/efc-corpo.png" alt="EFC Library Logo" class="logo" width="175">
            </a>
        </div>
        <!-- Split dropstart button -->
        <div class="btn-group dropstart">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Menu
            </button>
            <ul class="dropdown-menu">
                <!-- Dropdown menu links -->
                <li>
                    <button class="dropdown-item" type="button">Profile</button>
                </li>
                <?php
                    if($_SESSION['utype'] == '2')
                        echo '
                            <li>
                                <a class="dropdown-item" href="./admin.php">Admin Panel</a>
                            </li>
                        ';
                ?>
                <li><hr class="dropdown-divider"></li>
                <form action="./modular/logreq.php" method="POST">
                    <li>
                        <button class="dropdown-item" type="submit" value='logout' name='logout'>Logout</button>
                    </li>
                </form>
            </ul>
        </div>
    </nav>
</html>