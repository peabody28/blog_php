<?php
require_once __DIR__."/Validator.php";
require_once __DIR__."/UsersTable.php";
require_once __DIR__."/Session.php";

class UserTools
{
    public function signUp($user, $check=null)
    {
        $validator = new Validator();
        $validation = $validator->validSignUpData($user);

        if($validation["status"]==="OK")
        {
            $table = new UsersTable();
            $user->id = intval($table->create($user));

            if ($user->id)
            {
                $user->existence = true;

                $session = new Session();
                $session->userId = $user->id;
                $session->userName = $user->name;
                $session->create();
                if ($check)
                    $session->createCookie();

                return ["status"=>"OK"];
            }
            else
                return ["status"=>"ERROR", "error"=>"Не удалось добавить в базу"];
        }
        else
            return $validation;
    }


    public function logIn($user, $check=null)
    {
        $validator = new Validator();
        $validation = $validator->validLogInData($user);

        if($validation["status"]==="OK")
        {
            $table = new UsersTable();
            $findUser = $table->read($user);
            if ($findUser)
            {
                if ($findUser->password == $user->password)
                {
                    $user->id = $findUser->id;
                    $user->existence = true;

                    $session = new Session();
                    $session->userId = $user->id;
                    $session->userName = $user->name;
                    $session->create();
                    if ($check)
                        $session->createCookie();

                    return ["status"=>"OK"];
                }
                else
                    return ["status"=>"ERROR","error"=>"Неверный пароль"];
            }
            else
                return ["status"=>"ERROR","error"=>"Пользователя с таким именем нет"];
        }
        else
            return $validation;
    }
}