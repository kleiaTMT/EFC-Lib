<?php 
    require_once "./modular/filesLogic.php";
    @session_start();

    $_SESSION['emailAddr'] = '';
    $_SESSION['passw'] = '';
    $_SESSION['uid'] = '';
    $_SESSION['uname'] = '';
    $_SESSION['utype'] = '';
    
    if($_SESSION['uid'] != '') {
        header("Location: ./main.php");
    }

?>
<!doctype html>
<html>
    <head>
        <title>EFC Library Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <style>
            #loginPage {
                background-color: #E9E2E2;
            }
            #loginCard {
                background-color: white;
                text-align: center;
                border-radius: 20px;
                box-shadow: 3px 4px #888888;
                position: absolute;
                top: 50%;
                left: 50%;
                width: 55vh;
                height: 60vh;
                transform: translate(-50%, -50%);
                padding: 10px;
            }
            input {
                margin: 1vh;
                align-items: center;
                border-bottom: 0px;
                box-shadow: inset 0 0 2px #000;
                width: 40vh;
            }
            #supprt{
                text-align: left;
                margin-left: 5vh;
                margin-top: 2vh;
            }
            button{
                background-color: #94C7DD;
                border-radius: 30px;
                border: 0px;
                color: white;
                width: 40vh;
                margin-top: 2vh;
            }
        </style>
    </head>
    <body id="loginPage">
        <div id="loginCard">
            <form action="./modular/logreq.php" method="POST">
                <img src="./assets/efc-short.png" width="209" height="150">
                <input type="email" id="emailAddr" name="emailAddr" placeholder="Email Address...">
                <input type="password" id="passw" name="passw" placeholder="Password..."> <br/>
                <button type="submit" name="login">
                    LOGIN
                </button>
                <div id="supprt">
                    <p>
                        Request for 
                        <a href="https://it-helpdesk-ecofoods.on.spiceworks.com/portal">
                            support
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </body>
</html>

