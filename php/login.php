<?php
    include_once("db_connect.php");
    include_once("log_login.php");
    require_once("loginMethods/2FA/PHPGangsta/GoogleAuthenticator.php");

    $nickname = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $nickname = $_POST["formNickname"];
        $password = $_POST["formPass"];
        $gaToken = $_POST["formToken"];

        function login($conn, $nickname, $password, $gaToken){
            try{
                
                $sqlVldt = "SELECT u.password, u.secret, u.email FROM t_user u WHERE nickname = ?";
            
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
                $stmntVldt = $conn->prepare($sqlVldt);
                $stmntVldt->execute([$nickname]);
                $resultVldt = $stmntVldt->fetch(PDO::FETCH_ASSOC);
                
                $secret = $resultVldt["secret"];
                
                $ga = new PHPGangsta_GoogleAuthenticator();
                
                $tokenResult = $ga->verifyCode($secret, $gaToken);

                if($tokenResult && password_verify($password, $resultVldt["password"])){
                    $_SESSION["nickname"] = $nickname;
                    $_SESSION["email"] = $resultVldt["email"];
                    $_SESSION["loggedIn"] = true;

                    logLogin($conn, $resultVldt["email"], "basic");
                    
                    // not using header, there is warning: headers already sent by...
                    echo "<script type=\"text/javascript\"> 
                            window.location='https://wt82.fei.stuba.sk/The_Robots_Of_Dawn/';
                          </script>";
                }
                else{
                    echo "<div class='alert alert-danger' role='alert'>
                            Wrong credentials.
                        </div>";
                }
            }
            catch(PDOException $e){
               
                echo "<div class='alert alert-danger' role='alert'>
                            Sorry, there was an error.
                      </div>";
            }
        }
    }
?>

<div class="formContainer">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="row">
            <h3 class="col heading">Login</h3>
        </div>
        <div id="personalInfo">

            <div class="form-group row">
                <label for="formNickname" class="col-sm-3 col-form-label">Nickname</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="formNickname" id="formNickname" value="<?php echo $nickname; ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="formPass" class="col-sm-3 col-form-label">Password</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="formPass" id="formPass">
                </div>
            </div>

            <div class="form-group row">
                <label for="formToken" class="col-sm-3 col-form-label">Google Auth token</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="formToken" id="formToken">
                </div>
            </div>
        </div>
        <?php

            if($_SERVER["REQUEST_METHOD"] == "POST"){

                if($conn && $nickname && $password && $gaToken){
                    login($conn, $nickname, $password, $gaToken);
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
            <span>or </span>
            <a href='php/registration.php' class="registerLink">Register</a>
        </div>
    </form>
</div>

<div class="modal" id="successModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    Login successfull. You will be redirected in 3 seconds.
                </div>
            </div>
        </div>
    </div>
</div>