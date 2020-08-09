<?php
require_once __DIR__."/../db.php";


class UsersTable
{
    public function insert(User &$user)
    {
        $obj = R::dispense("users");
        $obj->name = $user->name;
        $obj->password = $user->password;
        $obj->friends = ($user->friends)?serialize($user->friends):"";
        return R::store($obj);
    }

    public function search(User &$obj)
    {
        return R::findOne("users", "name = ? AND password = ?", [$obj->name, $obj->password]);
    }

    public function search_pair(User &$obj)
    {
        return R::findOne("users", "name = ?", [$obj->name]);
    }

    public function update(User &$obj)
    {
        $user = R::findOne("users", "id = ?", [$obj->id]);
        $user->name = $obj->name;
        $user->password = $obj->password;
        $user->friends = serialize($obj->friends);
        return R::store($user);
    }

    public function delete(User &$obj)
    {
        $user = R::findOne("users", "id = ?", [$obj->id]);
        if($user)
            return (R::trash($user))?["status"=>"OK"]:["status"=>"ERROR", "error"=>"Не удалось удалить пользователя"];
        else
            return ["status"=>"ERROR", "error"=>"Пользователя не существует"];
    }
}
