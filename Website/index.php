<?php session_start();?>
<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<meta charset="UTF-8">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script>
            //red: #FC0025
            //green: #37BA53
            $(document).ready(function () {
                disable();
                $("input:not(input[type='submit'])").on("input", function () {
                    if(isNotEmpty()) $("#login").attr("type", "submit");
                    else disable();
                });

                $("#login").click(function () {
                    if ($(this).attr("type") === "button")
                        $("#errormsg").css({opacity: 1});
                });

            });

            function isNotEmpty() {
                var ret = true;
                $("input:not(input[type='submit'])").map(function () {
                    if ($(this).val().length === 0) ret = false;
                });
                return ret;
            }

            function disable() {
                $("#login").attr("type", "button");
            }
        </script>
        <style>
            body {
                font-family: Roboto;
                background: url("img/background.jpg") no-repeat fixed;
                background-size: cover;
                color: white;
            }

            .center {
                text-align: center;
            }

            .content {
                width: 600px;
                height: 500px;
                box-sizing: border-box;
                padding: 20px;
                margin: calc(50vh - 230px) auto 0 auto;
            }

            input {
                width: 100%;
                height: 25px;
                margin-bottom: 20px;
            }

            input[type='submit'], input[type='button'] {
                width: 150px;
                height: 50px;
                background-color: transparent;
                color: white;
                font-size: 12pt;
                transition: 0.3s;
                border: 2px solid white;
                cursor: pointer;
            }

            input[type='submit']:hover, input[type='button']:hover {
                background-color: #37BA53;
                border: none;
            }

            #errormsg {
                opacity: 0;
                text-align: center;
            }

            @font-face {
                font-family: Logo;
                src: url(res/Amethyst.ttf.woff);
            }
        </style>
	</head>
    <?php
        require_once "func.php";
    ?>
	<body>
        <div style="position: absolute; top: 40px; left: 30px; font-family: 'Logo'; font-size: 25pt">
            AbsenceManager
        </div>
		<div class="content">
            <h1 class="center">
                Login erforderlich
            </h1>
            <?php
                $school = test_input($_POST["school"]);
                $staff = find_account($school, test_input($_POST["username"]), test_input($_POST["password"]));
                if (!isset($_POST["school"], $_POST["username"], $_POST["password"])) {
            ?>
                    <p id="errormsg">Bitte füllen Sie alle Felder aus.</p>
                    <form method="post" action="index.php">
                        <label for="school">Schule</label>
                        <input type="text" id="school" name="school">

                        <label for="username">Benutzername</label>
                        <input type="text" id="username" name="username">

                        <label for="password">Passwort</label>
                        <input type="password" id="password" name="password">

                        <div class="center" id="button-container"><input type="button" id="login" value="Login"></div>
                    </form>
            <?php
                } else if ($staff != "") {
                    $status = array($staff)[0]["status"];
                    if ($status == "admin") {
                        $_SESSION["login-data"] = $staff;
                        $_SESSION["additional-data"] = get_all_teachers($school);
                        echo "<body onload='window.location.href = \"admin\"'></body>";
                    } else if ($status == "management") {
                        $_SESSION["login-data"] = $staff;
                        $_SESSION["additional-data"] = get_all($school);
                        echo "<body onload='window.location.href = \"management\"'></body>";
                    } else {
                        $_SESSION["login-data"] = $staff;
                        echo "<body onload='document.location.href = \"teacher\"'></body>";
                    }
                } else {
            ?>
                    <p style="color: #FC0025">
                        Ihr Account konnte entweder nicht gefunden werden, oder Ihr Passwort war falsch.
                    </p>
                    <form method="post" action="index.php">
                        <label for="school">Schule</label>
                        <input type="text" id="school" name="school">

                        <label for="username">Benutzername</label>
                        <input type="text" id="username" name="username">

                        <label for="password">Passwort</label>
                        <input type="password" id="password" name="password">
                        <div class="center" id="button-container"><input type="submit" id="login" value="Login"></div>
                    </form>
            <?php
                }
            ?>
        </div>
	</body>
</html>
