<?php
    require_once("../loginMethods/OAuth/google-api-php-client/vendor/autoload.php");
    include_once("log_login.php");
    include_once("db_connect.php");
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    $redirect_uri = 'https://wt82.fei.stuba.sk/The_Robots_Of_Dawn/php/login_oauth.php';

    $client = new Google_Client();
    $client->setAuthConfig($_SERVER['DOCUMENT_ROOT']."/configs/credentials.json");
    $client->setRedirectUri($redirect_uri);
    $client->addScope("email");
        
    $service = new Google_Service_Oauth2($client);
                
    if(isset($_GET['code'])){

        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);
        $_SESSION['upload_token'] = $token;

        // redirect back to home
        echo "<script type=\"text/javascript\"> 
                window.location='https://wt82.fei.stuba.sk/The_Robots_Of_Dawn/';
            </script>";
    }

    // set the access token as part of the client
    if(!empty($_SESSION['upload_token'])){

        $client->setAccessToken($_SESSION['upload_token']);

        if($client->isAccessTokenExpired()){
            unset($_SESSION['upload_token']);
        }
    }
    else{
        $authUrl = $client->createAuthUrl();
    }

    if($client->getAccessToken()){

        //Get user profile data from google
        $UserProfile = $service->userinfo->get();
        
        if(!empty($UserProfile)){

            $_SESSION["email"] = $UserProfile['email'];
            $_SESSION["loggedIn"] = true;

            logLogin($conn, $_SESSION["email"], "google");
        }
        else{
            echo "<h3 style='color:red'>Some problem occurred, please try again.</h3>";
        }   
    }
    else{

        $authUrl = $client->createAuthUrl();
        echo "<script type=\"text/javascript\"> 
                window.location='" . filter_var($authUrl, FILTER_SANITIZE_URL) ."';
            </script>";
    }
?>