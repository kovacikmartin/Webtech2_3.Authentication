<?php
    include_once("db_connect.php");
    include_once("log_login.php");
    require_once("../loginMethods/2FA/PHPGangsta/GoogleAuthenticator.php");

    $name = "";
    $surname = "";
    $email = "";
    $nickname = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $name = $_POST["formName"];
        $surname = $_POST["formSurname"];
        $email = $_POST["formEmail"];
        $nickname = $_POST["formNickname"];
        $password = $_POST["formPass"];

        $hashedPass = password_hash($password, PASSWORD_DEFAULT);

        function showErrorModal($error){

            echo "<div class='modal' id='errorModal' tabindex='-1' role='dialog' data-backdrop='static' data-keyboard='false'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title'>Error</h5>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body'>
                                <div class='alert alert-danger' role='alert'>
                                    ".$error." already exists.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";

            echo "<script type=\"text/javascript\"> 
                    $('#errorModal').modal('show');
                  </script>";

        }

        function showSuccessModal($ga, $secret){

            $qrCodeUrl = $ga->getQRCodeGoogleUrl("Webtech 2 - Zadanie 3", $secret);

            echo "<div class='modal' id='successModal' tabindex='-1' role='dialog' data-backdrop='static' data-keyboard='false'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title'>Success</h5>
                            </div>
                            <div class='modal-body'>
                                <div class='alert alert-success' role='alert'>
                                    <p>
                                        Account successfully created.<br>
                                        Please scan this QR code using Google Authenticator app.<br>
                                        Note: rendering the code may take a second.<br>
                                    </p>
                                </div>
                                <img src=".$qrCodeUrl." alt='Google Authenticator QR code'>
                            </div>
                            <div class='modal-footer'>
                                <a href='http://wt82.fei.stuba.sk/The_Robots_Of_Dawn/' class='btn btn-info'>I scanned the code</a>
                            </div>
                        </div>
                    </div>
                  </div>";
            
            echo "<script type=\"text/javascript\"> 
                    $('#successModal').modal('show');
                  </script>";
        }

        function register($conn, $name, $surname, $email, $nickname, $hashedPass){
            try{
                
                $ga = new PHPGangsta_GoogleAuthenticator();
 
                $secret = $ga->createSecret();

                $sql = "INSERT INTO t_user(name, surname, email, nickname, password, secret)
                                VALUES(?, ?, ?, ?, ?, ?)";
            
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
                $conn->prepare($sql)->execute([$name, $surname, $email, $nickname, $hashedPass, $secret]);

                showSuccessModal($ga, $secret);
            }
            catch(PDOException $e){
                
                if($e->getCode() === '23000'){
                    if(str_contains($e->getMessage(), "nickname")){
                        showErrorModal("Nickname");
                    }
                    else{
                        showErrorModal("Email");
                    }
                }
                else{
                    
                    echo "<div class='alert alert-danger' role='alert'>
                                Sorry, there was an error.
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
    <title>Registration</title>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <?php include "../partials/header.php"; ?>
    <div class="formContainer">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return formVal();">
            <div class="row">
                <h3 class="col heading">Registration</h3>
            </div>
            <div id="personalInfo">

                <div class="form-group row">
                    <label for="formName" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="formName" id="formName" value="<?php echo $name; ?>" onblur="nameWarn();">
                        <span class="warnMsg" id="formNameWarn"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="formSurname" class="col-sm-2 col-form-label">Surname</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="formSurname" id="formSurname" value="<?php echo $surname; ?>" onblur="surnameWarn();">
                        <span class="warnMsg" id="formSurnameWarn"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="formEmail" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="formEmail" id="formEmail" value="<?php echo $email; ?>" onblur="emailWarn();">
                        <span class="warnMsg" id="formEmailWarn"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="formNickname" class="col-sm-2 col-form-label">Nickname</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="formNickname" id="formNickname" value="<?php echo $nickname; ?>" onblur="nicknameWarn();">
                        <span class="warnMsg" id="formNicknameWarn"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="formPass" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="formPass" id="formPass" onblur="passWarn();">
                        <span class="warnMsg" id="formPassWarn"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="formPassRpt" class="col-sm-2 col-form-label">Repeat password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="formPassRpt" id="formPassRpt" onblur="passRptWarn();">
                        <span class="warnMsg" id="formPassRptWarn"></span>
                    </div>
                </div>
                
            </div>
            <div class='col-sm-12 text-center'>
                <button type="submit" class="btn btn-info">Register</button>
            </div>
        </form>
    </div>
    

    <?php 
        if($conn && $name && $surname && $email && $nickname && $hashedPass){
            register($conn, $name, $surname, $email, $nickname, $hashedPass);
        }
    ?>
    <script src="../js/script.js"></script>
    <?php include "../partials/footer.php"; ?>
</body>
</html>