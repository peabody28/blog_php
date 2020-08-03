<?php


class UserTools
{
    public function signUp(User &$user)
    {
        if(!$user->name or !$user->password)
            return ["status"=>"ERROR", "error"=>"Заполните все поля"];

        $db = new UsersTable();
        if($db->search_pair($user))
            return ["status" => "ERROR", "error"=>"Пользователь существует"];

        if(!preg_match("/^[a-zA-Z0-9]+$/",$user->name))
            return ["status" => "ERROR", "error" => "Логин может состоять только из букв английского алфавита и цифр"];

        $user->id = $db->insert($user);
        return ($user->id)?["status"=>"OK", "id"=>$user->id]:["status"=>"ERROR", "error"=>"Ошибка при добавлении в базу"];

    }

    public function login(User &$user)
    {
        if(!$user->name or !$user->password)
            return ["status"=>"ERROR", "error"=>"Заполните все поля"];

        $db = new UsersTable();
        $find_user = $db->search($user);
        if($find_user)
        {
            $user->id = $find_user->id;
            return ["status" => "OK", "id"=>$user->id];
        }
        else
            return ["status"=>"ERROR", "error"=>"Неверное имя или пароль"];

    }

    public function delete(User &$obj)
    {
        $db = new UsersTable();
        $db->delete($obj);
        // вызываю класс сесии
        // вывожу ошибку
    }

    public function changePass(User &$obj)
    {
        $db = new UsersTable();
        $db->update($obj);
    }

    // ...
}
