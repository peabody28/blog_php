<?php
require_once __DIR__."/User.php";
require_once __DIR__."/UsersTable.php";
require_once __DIR__."/Session.php";
require_once __DIR__."/PostTools.php";
require_once __DIR__."/PostsTable.php";
require_once __DIR__."/Validation.php";


class UserTools
{
    public function signUp(User &$user, $check=false)
    {
        $valid = new Validation();
        $resp = $valid->validateSignUpData($user);

        if($resp["status"]==="OK")
        {
            $db = new UsersTable();
            $user->id = $db->insert($user);

            if($user->id)
            {
                $session = new Session();
                $session->user_id = $user->id;
                $session->username = $user->name;
                if($check)
                    $session->setCookie();
                $session->create();
            }
            return ($user->id)?["status"=>"OK", "id"=>$user->id]:["status"=>"ERROR", "error"=>"Ошибка при добавлении в базу"];
        }
        else
            return $resp;
    }

    public function login(User &$user, $check=false)
    {

        $valid = new Validation();
        $resp = $valid->validateLoginData($user);

        if ($resp["status"]==="OK")
        {
            $db = new UsersTable();
            $find_user = $db->search($user);
            if($find_user)
            {
                $user->id = $find_user->id;
                $session = new Session();
                $session->user_id = $user->id;
                $session->username = $user->name;
                if ($check)
                    $session->setCookie();
                $session->create();
                return ["status" => "OK", "id"=>$user->id];
            }
            else
                return ["status"=>"ERROR", "error"=>"Неверное имя или пароль"];
        }
        else
            return $resp;

    }

    public function delete(User &$obj)
    {
        $db = new UsersTable();
        $resp = $db->delete($obj);

        if ($resp["status"]==="OK")
        {
            // session remove
            $session = new Session();
            $session->user_id = $obj->id;
            $session->username = $obj->name;
            $session->delete();

            //del posts
            if($obj->wall)
            {
                $postsTable = new PostsTable();
                $posts = $postsTable->getPostsByAuthor($obj);

                $postTools = new PostTools();
                foreach ($posts as $post)
                    $postTools->deletePost($post);
            }

            return ["status"=>"OK"];
            // отсылаю уведомления
        }
        else
        {
            return false;
            // вывожу ошибку
        }


    }

    public function changePass(User &$obj)
    {
        $db = new UsersTable();
        $db->update($obj);
    }

    public function addToFriends(User &$obj)
    {
        if (!$obj->name)
            return ["status"=>"ERROR", "error"=>"Введите имя!"];

        $friend = R::findOne("users", "name = ?", [$obj->name]);
        if ($friend)
        {
            $user = new User($_SESSION["id"]);

            if (in_array( $friend->id , $user->friends))
                return ["status"=>"ERROR", "error"=>"Пользователь уже у вас в друзьях"];

            $user->friends[] = (int)$friend->id;

            $usersTable = new UsersTable();
            $usersTable->update($user);
            return $usersTable->update($user)?["status"=>'OK']:["status"=>"ERROR", "error"=>'Не удалось добавить в друзья'];
        }
        else
            return ["status"=>'ERROR', "error"=>"Пользователя не существует"];
    }

    public function removeFromFriends(User &$obj)
    {
        if (!$obj->name)
            return ["status"=>"ERROR", "error"=>"Введите имя!"];

        $friend = R::findOne("users", "name = ?", [$obj->name]);
        if ($friend)
        {
            $user = new User($_SESSION["id"]);

            $user->friends = array_replace($friend->id, null);

            $usersTable = new UsersTable();
            return $usersTable->update($user)?["status"=>'OK']:["status"=>"ERROR", "error"=>'Не удалось добавить в друзья'];
        }
        else
            return ["status"=>'ERROR', "error"=>"Пользователя не существует"];
    }

    // ...
}
