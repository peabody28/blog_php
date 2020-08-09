<?php
require_once "classes/User.php";
require_once "classes/UserTools.php";

$data = $_POST;

if (isset($data["delete"]))
{
    $user = new User($_SESSION["id"]);
    $tools = new UserTools();
    echo json_encode($tools->delete($user));
}
else
{
    echo "Вам нельзя на эту страницу";
}
