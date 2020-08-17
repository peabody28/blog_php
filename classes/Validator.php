<?php
require_once __DIR__."/UsersTable.php";

class Validator
{
    public function validSignUpData($user)
    {
        if (!$user->name or !$user->password)
            return ["status"=>"ERROR", "error"=>"Имя или пароль не введены"];

        if (!preg_match("/[a-zA-Z0-9_]+/", $user->name))
            return ["status"=>"ERROR", "error"=>"Имя содержит запрещенные символы"];

        $table = new UsersTable();
        if ($table->checkingForExistence($user->name))
            return ["status"=>"ERROR", "error"=>"Пользователь с таким именем существует"];

        return ["status"=>"OK"];
    }

    public function validLogInData($user)
    {
        if (!$user->name or !$user->password)
            return ["status"=>"ERROR", "error"=>"Имя или пароль не введены"];

        if (!preg_match("/[a-zA-Z0-9_]+/", $user->name))
            return ["status"=>"ERROR", "error"=>"Имя содержит запрещенные символы"];

        return ["status"=>"OK"];
    }
}