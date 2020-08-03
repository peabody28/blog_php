<?php
session_start();
require_once "classes/crypter.php";
class Session
{
    public $user_id, $username;
    public function create()
    {
        $_SESSION["id"] = $this->user_id;
        $_SESSION["name"] = $this->username;
    }

    public function delete()
    {
        setcookie("id", "", time()-3600);
        session_destroy();
    }

    public function setCookie()
    {
        $crypter = new Crypter("152");
        $id = $crypter->encrypt($this->user_id);
        setcookie("id", $id, time()+3600*24*31);
    }
}
