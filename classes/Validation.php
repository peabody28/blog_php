<?php
require_once __DIR__."/UsersTable.php";

class Validation
{
    public function validateSignUpData($user)
    {
        if(!$user->name or !$user->password)
            return ["status"=>"ERROR", "error"=>"Заполните все поля"];

        $db = new UsersTable();
        if($db->search_pair($user))
            return ["status" => "ERROR", "error"=>"Пользователь существует"];

        if(!preg_match("/^[a-zA-Z0-9]+$/",$user->name))
            return ["status" => "ERROR", "error" => "Логин может состоять только из букв английского алфавита и цифр"];

        return ["status" => "OK"];
    }

    public function validateLoginData($user)
    {
        if(!$user->name or !$user->password)
            return ["status"=>"ERROR", "error"=>"Заполните все поля"];
        return ["status"=>"OK"];
    }

}