<?php
require_once __DIR__."/Table.php";
require_once __DIR__."/../db.php";

class UsersTable implements Table
{
    //signUp
    public function create($data)
    {
        $new_user = R::dispense("users");
        $new_user->name = $data->name;
        $new_user->password = $data->password;
        $new_user->friends = serialize([]);
        return R::store($new_user);
    }

    //Login
    public function read($data)
    {
        return R::findOne("users", "name = ?", [$data->name]);
    }

    //Rename etc
    public function update($data, $column)
    {
        $user = R::findOne("users", "id = ?", [$data->id]);
        switch ($column)
        {
            case "name":
                $user->name = $data->name;
                break;
            case "password":
                $user->password = $data->password;
                break;
            case "friends":
                $user->friends = serialize($data->friends);
                break;
        }
        return R::store($user);
    }

    public function delete($data)
    {
        $user = R::findOne("users", "id = ?", [$data->id]);
        if ($user)
            return R::trash($user);
    }

    public function checkingForExistence($user)
    {
        return R::findOne("users", "name = ?", [$user->name]);
    }

    public function getUsersByFriend($data)
    {
        $str = serialize(strval($data->id));
        return R::findAll("users", "friends LIKE ?", ["%$str%"]);
    }
}