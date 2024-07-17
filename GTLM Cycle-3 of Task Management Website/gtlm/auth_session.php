<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if(!isset($_SESSION["email"])) {
        $_SESSION["warning"] = "Oops your Session Expire! Login again";
        header("Location: login.php");
        exit();
    }
?>