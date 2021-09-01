<?php
    include_once("db_connect.php");
    include_once("log_login.php");
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    $login = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $login = $_POST["formLogin"];
        $password = $_POST["formPass"];

        function ldapLogin($conn, $login, $password){

            $dn  = 'ou=People, DC=stuba, DC=sk';
            $ldaprdn  = "uid=$login, $dn";

            // connect to ldap server
            $ldapconn = ldap_connect("ldap.stuba.sk")
                        or die("Could not connect to Stuba LDAP server.");

            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

            if($ldapconn){
                
                // binding to ldap server
                // ldap_bind returns bool, @ suppresses warnings (invalid credentials etc.)
                // those warnings are displayed in more user friendly fashion in else statement
                $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $password); // @ = suppress warnings, show error message in else

                if($ldapbind){
                    
                    $search = ldap_search($ldapconn, $dn, "uid=".$login, ["mail"]);
                    $email = ldap_get_entries($ldapconn, $search)[0]["mail"][0]; // mail of first entry
                    
                    logLogin($conn, $email, "ldap");

                    $_SESSION["email"] = $email;
                    $_SESSION["loggedIn"] = true;
                    
                    echo "<script type=\"text/javascript\"> 
                            window.location='https://wt82.fei.stuba.sk/The_Robots_Of_Dawn/';
                          </script>";
                }
                else{
                    
                    echo "<div class='alert alert-danger' role='alert'>
                            ".ldap_error($ldapconn)."
                        </div>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with stuba account</title>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <?php include "../partials/header.php"; ?>
    <div class="formContainer">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="row">
                <h3 class="col heading">Stuba login</h3>
            </div>
            <div id="personalInfo">

                <div class="form-group row">
                    <label for="formLogin" class="col-sm-2 col-form-label">AIS login</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="formLogin" id="formLogin" value="<?php echo $login; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="formPass" class="col-sm-2 col-form-label">AIS password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="formPass" id="formPass">
                    </div>
                </div>
            </div>
            <?php

                if($_SERVER["REQUEST_METHOD"] == "POST"){

                    if($conn && $login && $password){
                        ldapLogin($conn, $login, $password);
                    }
                    else{
                        echo "<div class='alert alert-danger' role='alert'>
                                Please fill your credentials.
                            </div>";
                    }
                }
        ?>
            <div class='col-sm-12 text-center'>
                <button type="submit" class="btn btn-info">Login</button>
            </div>
        </form>
    </div>
    <?php include "../partials/footer.php"; ?>
</body>