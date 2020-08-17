<?php
require_once __DIR__."/classes/Crypter.php";
session_start();

function auth()
{
    if(!$_SESSION["id"])
        if ($_COOKIE["id"])
        {
            $crypter = new Crypter("152");
            $_SESSION["id"] = $crypter->decrypt($_COOKIE["id"]);
        }
        else
            header("Location: /login.php");
}