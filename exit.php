<?php
require_once __DIR__."/classes/Session.php";

$data = $_POST;
if (isset($data["submit"]))
{
    $session = new Session();
    $session->userId = $_SESSION["id"];
    $session->userName = $_SESSION["name"];

    $session->delete();
    header("Location: /login.php");
}
else
    echo 'Что-то пошло не так';