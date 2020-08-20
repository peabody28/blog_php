<?php
require_once __DIR__."/Validator.php";
require_once __DIR__."/UsersTable.php";
require_once __DIR__."/Session.php";

class UserTools
{
    public function signUp($user, $check = null)
    {
        $validator = new Validator();
        $validation = $validator->validSignUpData($user);

        if ($validation["status"] === "OK") {
            $table = new UsersTable();
            $user->id = intval($table->create($user));

            if ($user->id) {

                $session = new Session();
                $session->userId = $user->id;
                $session->userName = $user->name;
                $session->create();
                if ($check)
                    $session->createCookie();

                return ["status" => "OK"];

            } else
                return ["status" => "ERROR", "error" => "Не удалось добавить в базу"];
        } else
            return $validation;
    }


    public function logIn($user, $check = null)
    {
        $validator = new Validator();
        $validation = $validator->validLogInData($user);

        if ($validation["status"] === "OK") {
            $table = new UsersTable();
            $findUser = $table->read($user);
            if ($findUser) {
                if ($findUser->password == $user->password) {
                    $user->id = $findUser->id;

                    $session = new Session();
                    $session->userId = $user->id;
                    $session->userName = $user->name;
                    $session->create();
                    if ($check)
                        $session->createCookie();

                    return ["status" => "OK"];
                } else
                    return ["status" => "ERROR", "error" => "Неверный пароль"];
            } else
                return ["status" => "ERROR", "error" => "Пользователя с таким именем нет"];
        } else
            return $validation;
    }

    public function deleteAccount($user)
    {
        if (!$user->id)
            return ["status"=>"ERROR", "error"=>"Пользователя не существует"];

        $table = new UsersTable();
        if ($table->delete($user))
        {
            $session = new Session();
            $session->userId = $user->id;
            $session->userName = $user->name;
            $session->delete();

            return ["status"=>"OK"];
        }
        else
            return ["status"=>"ERROR", "error"=>"Не удалось удалить пользователя"];
    }

    public function addToFriends($user, $friend)
    {

        $table = new UsersTable();
        $fr = $table->checkingForExistence($friend->name);
        if ($fr) {
            $friend->id = $fr->id;

            if ($friend->name != $user->name) {
                $friendsList = $user->getFriendsList();
                if (!in_array($friend->id, array_column($friendsList, "id"))) {
                    $friendsList[] = ["id" => $friend->id, "name" => $friend->name];
                    $user->friends = $friendsList;
                    $table->update($user, "friends");
                    return ["status" => "OK"];
                } else
                    return ["status" => "ERROR", "error" => "Пользователь уже у вас в друзьях"];
            } else
                return ["status" => "ERROR", "error" => "Вы не можете добавить в друзья себя"];
        } else
            return ["status" => "ERROR", "error" => "Пользователя не существует"];
    }

    public function removeFromFriends($user, $friend)
    {
        unset($user->friends[array_search($friend->id, array_column($user->friends, "id"))]);
        $user->friends = array_values($user->friends);
        $table = new UsersTable();
        $table->update($user, "friends");
        return ["status"=>"OK"];
    }
}