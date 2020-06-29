<?php
require_once "classes/crypter.php";
require_once "db.php";


function access()
{
    if (!isset($_SESSION["name"]))
        if (!isset($_COOKIE['name']))
            header("Location: /login.php");
        else {
            $crypter = new Crypter("152");
            $nm = $crypter->decrypt($_COOKIE['name']);
            $find_user = R::findOne('user', 'name = ?', [$nm]);
            if ($find_user)
                $_SESSION["name"] = $nm;
            else
                header("Location: /login.php");
        }
}