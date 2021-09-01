<?php

    session_start();

    $_SESSION = array();

    if(ini_get("session.use_cookies")){
        setcookie(session_name(), '', time() - 42000);
    }

    session_destroy();

    header("Location: https://wt82.fei.stuba.sk/The_Robots_Of_Dawn/");
?>