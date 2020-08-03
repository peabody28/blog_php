<?php


class User
{
    public $name, $password, $id, $friends, $wall;

    public function __construct($id=-1)
    {
        if($id!==-1)
        {
            $user = R::findOne("users", "id = ?", [$id]);
            $this->id = $user->id;
            $this->name = $user->name;
            $this->password = $user->password;
            $this->friends = unserialize($user->friends);
            $user_wall = R::findAll("posts", "author_id = ?", [$id]);
            $this->wall = $user_wall;
        }
    }
}
