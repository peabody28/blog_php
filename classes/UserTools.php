<?php
require_once __DIR__."/Validator.php";
require_once __DIR__."/UsersTable.php";
require_once __DIR__."/Session.php";
require_once __DIR__."/Notification.php";
require_once __DIR__."/NotificationTools.php";
require_once __DIR__."/FriendBlock.php";

class UserTools
{
    public function signUp($user, $check = null)
    {
        $validator = new Validator();
        $validation = $validator->validSignUpData($user);

        if ($validation["status"] === "OK")
        {
            $table = new UsersTable();
            $user->id = $table->create($user);
            if ($user->id)
            {
                $session = new Session();
                $session->userId = $user->id;
                $session->userName = $user->name;
                $session->create();
                if ($check)
                    $session->createCookie();
                return ["status" => "OK"];
            }
            else
                return ["status" => "ERROR", "error" => "Не удалось добавить в базу"];
        } else
            return $validation;
    }


    public function logIn($user, $check = null)
    {
        $validator = new Validator();
        $validation = $validator->validLogInData($user);

        if ($validation["status"] === "OK")
        {
            $table = new UsersTable();
            $findUser = $table->read($user);
            if ($findUser)
            {
                if ($findUser->password == $user->password)
                {
                    $user->id = $findUser->id;

                    $session = new Session();
                    $session->userId = $user->id;
                    $session->userName = $user->name;
                    $session->create();
                    if ($check)
                        $session->createCookie();
                    return ["status" => "OK"];
                }
                else
                    return ["status" => "ERROR", "error" => "Неверный пароль"];
            }
            else
                return ["status" => "ERROR", "error" => "Пользователя с таким именем нет"];
        }
        else
            return $validation;
    }

    public function deleteAccount($user)
    {
        if ($user->id)
        {
            $table = new UsersTable();
            $friends = array_column($user->getFriendsList(), "id");
            if ($table->delete($user))
            {
                $session = new Session();
                $session->userId = $user->id;
                $session->userName = $user->name;
                $session->delete();

                $notification = new Notification();
                $notification->text = "$user->name удалил аккаунт";

                $notifTools = new NotificationTools();
                foreach ($friends as $friend)
                {
                    $notification->target = $friend;
                    $notifTools->send($notification);
                }

                return ["status"=>"OK"];
            }
            else
                return ["status"=>"ERROR", "error"=>"Не удалось удалить пользователя"];
        }
        else
            return ["status"=>"ERROR", "error"=>"Пользователя не существует"];

    }

    public function addToFriends($user, $friend)
    {

        $table = new UsersTable();
        $fr = $table->checkingForExistence($friend);
        if ($fr)
        {
            $friend->id = $fr->id;

            if ($friend->name != $user->name)
            {
                $friendsList = $user->getFriendsList();
                if (!in_array($friend->id, $friendsList))
                {
                    $friendsList[] = $friend->id;
                    $user->friends = $friendsList;
                    $table->update($user, "friends");

                    $notification = new Notification();
                    $notification->target = $friend->id;
                    $notification->text = "$user->name добавил вас в друзья";

                    $notifTools = new NotificationTools();
                    $notifTools->send($notification);

                    $friendBlock = new FriendBlock();
                    return ["status"=>"OK", "friend_block"=>$friendBlock->getHtml($friend)];
                }
                else
                    return ["status" => "ERROR", "error" => "Пользователь уже у вас в друзьях"];
            }
            else
                return ["status" => "ERROR", "error" => "Вы не можете добавить в друзья себя"];
        }
        else
            return ["status" => "ERROR", "error" => "Пользователя не существует"];
    }

    public function removeFromFriends($user, $friend)
    {
        unset($user->friends[array_search($friend->id, $user->friends)]);

        $user->friends = array_values($user->friends); // array_values переформирует индексы

        $table = new UsersTable();
        $table->update($user, "friends");

        $notification = new Notification();
        $notification->target = $friend->id;
        $notification->text = "$user->name удалил вас из друзей";

        $notifTools = new NotificationTools();
        $notifTools->send($notification);

        return ["status"=>"OK", "id"=>$friend->id];
    }

    public function rename($user)
    {
        $validator = new Validator();
        $validation = $validator->validRenameData($user);

        if ($validation["status"]==="OK")
        {
            $table = new UsersTable();
            if($table->update($user, "name"))
            {
                $lastName = $_SESSION["name"];

                $session = new Session();
                $session->userName = $user->name;
                $session->update();

                $friends = $table->getUsersByFriend($user);

                $notification = new Notification();
                $notification->text = "$lastName изменил имя на $user->name";

                $notifTools = new NotificationTools();
                foreach ($friends as $friend)
                {
                    $notification->target = $friend->id;
                    $notifTools->send($notification);
                }

                return ["status"=>"OK"];
            }
        }
    }
}