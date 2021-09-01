<?php
    date_default_timezone_set('Europe/Bratislava');
    
    function logLogin($conn, $email, $loginType){

        try{
        
            $sql = "INSERT INTO t_login_log(email, login_time, login_type)
                            VALUES(?, ?, ?)";

            
            $timestamp = new DateTime();
            $timestamp = $timestamp->format('Y-m-d H:i:s');

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
            $conn->prepare($sql)->execute([$email, $timestamp, $loginType]);
        }
        catch(PDOException $e){

            echo "<div class='alert alert-danger' role='alert'>
                        Sorry, there was an error.
                    </div>";
        }

        return;
    }
?>