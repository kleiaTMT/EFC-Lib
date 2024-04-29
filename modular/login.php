<?php 
    require_once "filesLogic.php";
    @session_start();
?>
<!doctype html>
<html>
    <head>
        <title>EFC Library Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="../styless/styles.css">
        <script></script>
    </head>
    <body>
        <form>
            <div id="loginPage">
                <form action="logreq.php" method="POST" id="loginCard">
                    <img src="../assets/efc-short.png">
                    <label for="emailAddr">
                        Email Address
                    </label>
                        <input type="email" name="emailAddr">
                    <label for="passw">
                        Password
                    </label>
                        <input type="password" name="passw">
                    <button type="submit" name="login">
                        Login
                    </button>
                    <div id="supprt">
                        <p>
                            Request for 
                            <a href="https://it-helpdesk-ecofoods.on.spiceworks.com/portal">
                                support?
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </form>
    </body>
</html>

