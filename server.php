<?php
session_start();

require "libs/redbeanphp/db.php";
require_once("classes/user.php");
require_once("classes/post.php");

$data = $_REQUEST;

switch($data["code"])
{
    case "login":
        $user = new user($data["name"], $data["password"]);
        $resp = $user->search();
        if($resp["STATUS"]==="OK")
            $_SESSION['name'] = $user->name;
        if($data["check"])
            setcookie("name", $user->name, time()+3600*24*365);
        break;

    case "signup":
        $user = new user($data["name"], $data["password"]);
        $resp = $user->add();
        if($resp["STATUS"]==="OK")
            $_SESSION['name'] = $user->name;
        if($data["check"])
            setcookie("name", $user->name, time()+3600*24*365);
        break;

    case "delete":
        $user = new user();
        $user->delete();
        session_destroy();
        break;

    case "rename":
        $user = new user($data["name"]);
        $resp = $user->rename();
        if($resp["STATUS"]==="OK")
        {
            $post = new post($data["name"]);
            $post->change_author();
            $_SESSION['name'] = $user->name;
            if(isset($_COOKIE["name"]))
                setcookie("name", $user->name, time()+3600*24*365);
        }
        break;

    case "exit":
        unset($_SESSION["name"]);
        setcookie("name","",time()-3600);
        $resp = ["STATUS"=>"OK"];
        break;

    case "add_post":
        $post = new post($data["author"], $data["title"], $data["text"]);
        $resp = $post->add();
        break;

    case "add_friend":
        $user = new user($data["fr_name"]);
        $resp = $user->add_friend();
        break;

    case "get_wall":
        $post = new post($data["author"]);
        $resp = $post->get();
}

echo json_encode($resp);
