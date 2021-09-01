<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <title>Authentication</title>
</head>
<body>
    <?php 
        include_once("partials/header.php");

        if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true){
            echo "<div class='col-sm-12 text-center welcomeUser'>";
                if(isset($_SESSION["nickname"])){
                    echo "<span><h3 class='heading'>Welcome</h3> " . $_SESSION["nickname"] . "</span>";
                }
                else{
                    echo "<span><h3 class='heading'>Welcome</h3> " . $_SESSION["email"] . "</span>";
                }
            echo "</div>";
            echo "<div class='col-sm-12 text-center buttons'>";
                echo "<a href='php/login_stats.php'>Login stats</a><br>";
                echo "<a href='php/logout.php' class='btn btn-info'>Logout</a>";
            echo "</div>";
        }
        else{
            include_once("php/login.php");
            echo "<div class='col-sm-12 text-center otherLogins'>";
                echo "<span>You can also use these for login</span>";
                echo "<div>";
                    echo "<a href='php/login_oauth.php' class='btn btn-info'>Google account</a>";
                    echo "<a href='php/login_ldap.php' class='btn btn-info'>Stuba account</a>";
                echo "</div>";
            echo "</div>";
        }
    ?>

    <?php include_once("partials/footer.php"); ?>
</body>
</html>