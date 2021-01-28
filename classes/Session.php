<?php
session_start();
require_once __DIR__."/Crypter.php";

class Session
{
    public $userId, $userName;

    public function create()
    {
        $_SESSION["id"] = $this->userId;
        $_SESSION["name"] = $this->userName;
    }

    public function createCookie()
    {
        $crypter = new Crypter("152");
        setcookie("id", $crypter->encrypt($this->userId), time()+3600*24*31);
    }

    public function delete()
    {
        session_destroy();
        setcookie("id", "", time()-1);
    }

    public function update()
    {
        $_SESSION["name"] = $this->userName;
    }

}