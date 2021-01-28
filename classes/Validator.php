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
        if ($table->checkingForExistence($user))
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

    public function validPostData($post)
    {
        if (!$post->title or !$post->text)
            return ["status"=>"ERROR", "error"=>"Заполните все поля"];

        return ["status"=>"OK"];
    }

    public function validRenameData($user)
    {
        if (!$user->name)
            return ["status"=>"ERROR", "error"=>"Имя не введено"];

        if (!preg_match("/[a-zA-Z0-9_]+/", $user->name))
            return ["status"=>"ERROR", "error"=>"Имя содержит запрещенные символы"];

        $table = new UsersTable();
        if ($table->checkingForExistence($user))
            return ["status"=>"ERROR", "error"=>"Пользователь с таким именем существует"];

        return ["status"=>"OK"];
    }
}