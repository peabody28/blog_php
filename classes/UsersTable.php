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
    public function update($data)
    {

    }

    //delete
    public function delete($data)
    {

    }

    public function checkingForExistence($name)
    {
        return R::findOne("users", "name = ?", [$name]);
    }
}