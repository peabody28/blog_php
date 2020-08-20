<?php
require_once __DIR__."/classes/Crypter.php";
require_once __DIR__."/classes/User.php";
session_start();

function auth()
{
    if(!$_SESSION["id"])
        if ($_COOKIE["id"])
        {
            $crypter = new Crypter("152");
            $_SESSION["id"] = $crypter->decrypt($_COOKIE["id"]);
            $user = new User($_SESSION["id"]);
            $_SESSION["name"] = $user->name;
        }
        else
            header("Location: /login.php");
        
    if (!$_SESSION["name"])
    {
        $user = new User($_SESSION["id"]);
        $_SESSION["name"] = $user->name;
    }
}