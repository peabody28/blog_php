<?php
session_start();

require_once "db.php";
require_once("classes/user.php");
require_once("classes/post.php");
require_once ("classes/crypter.php");

$data = $_POST;

if(!$data)
    exit("no, no, no");

switch($data["code"])
{
    case "login":
        $user = new user($data["name"], $data["password"]);
        $resp = $user->search();
        if($resp["STATUS"]==="OK")
            $_SESSION['name'] = $user->name;
        if(isset($data["check"]))
        {
            $crypter = new Crypter("152");
            $code = $crypter->encrypt($user->name);
            setcookie("name", $code, time()+3600*24*365);
        }

        break;

    case "signup":
        $user = new user($data["name"], $data["password"]);
        $resp = $user->add();

        if($resp["STATUS"]==="OK")
            $_SESSION['name'] = $user->name;
        if(isset($data["check"]))
        {
            $crypter = new Crypter("152");
            $code = $crypter->encrypt($user->name);
            setcookie("name", $code, time()+3600*24*365);
        }
        break;

    case "delete":
        $user = new user($_SESSION["name"]);
        $resp = $user->delete();
        if($resp["STATUS"]==="OK")
            $user->clear_wall();
        session_destroy();
        setcookie("name", "", time()-3600);
        break;

    case "rename":
        $user = new user($_SESSION["name"]);
        $resp = $user->rename($data["name"]);
        if($resp["STATUS"]==="OK")
        {
            $post = new post($_SESSION["name"]);
            $post->change_author($data["name"]);
            $_SESSION['name'] = $data["name"];
            if(isset($_COOKIE["name"]))
            {
                $crypter = new Crypter("152");
                $code = $crypter->encrypt($user->name);
                setcookie("name", $code, time()+3600*24*365);
            }

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

    case "delete_post":
        $post = new post($_SESSION["name"]);
        $resp = $post->delete_post($data["id"]);
        break;

    case "get_wall":
        $user = new user($_SESSION["name"]);
        $resp = $user->get_wall($data["name"]);
        break;

    case "add_friend":
        $user = new user($_SESSION["name"]);
        $resp = $user->add_friend($data["fr_name"]);
        break;

    case "remove_from_friends":
        $user = new user($_SESSION["name"]);
        $resp = $user->remove_from_friends($data["name"]);
        break;
    case "get_notif":
        $user = new user($_SESSION["name"]);
        $resp = $user->get_notif();
        break;
    case "del_notif":
        $user = new user($_SESSION["name"]);
        $resp = $user->delete_notif($data["text"]);

}

echo json_encode($resp);
