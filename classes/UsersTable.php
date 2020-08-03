<?php


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
        R::findOne("users", "id = ?", [$obj->id]);
    }

    public function delete(User &$obj)
    {
        $user = R::findOne("users", "id = ?", [$obj->id]);
        if($user)
        {
            // вызываю класс сесии
            // отсылаю уведомления
            // удаляю посты
            R::trash($user);
            return true;
        }
        else
        {
            pass();
            return false;
            // вывожу ошибку
        }
    }


}
