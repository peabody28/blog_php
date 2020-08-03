<?php
require_once "classes/crypter.php";
require_once "db.php";


function access()
{
    if (!isset($_SESSION["id"]))
        if (!isset($_COOKIE['id']))
            header("Location: /login.php");
        else {
            $crypter = new Crypter("152");
            $id = $crypter->decrypt($_COOKIE['id']);
            $find_user = R::findOne('user', 'id = ?', [$id]);
            if ($find_user)
            {
                $_SESSION["name"] = $find_user->name;
                $_SESSION["id"] = $find_user->id;
            }
            else
                header("Location: /login.php");
        }
}