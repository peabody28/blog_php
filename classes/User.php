<?php
require_once __DIR__."/../db.php";

class User
{
    public $id, $name, $password, $friends, $wall, $existence;

    public function __construct($id=null)
    {
        if($id)
        {
            $user = R::findOne("users", "id = ?", [intval($id)]);
            if ($user)
            {
                $this->existence = true;
                $this->id = intval($user->id);
                $this->name = $user->name;
                $this->password = $user->password;
            }
            else
                $this->existence = false;
        }
        else
            $this->existence = false;

    }

    public function getFriendsList()
    {
        if($this->existence)
        {
            if(isset($this->friends))
                return $this->friends;
            $user = R::findOne("users", "id = ?", [$this->id]);
            return unserialize($user->friends);
        }
        else
            return null;
    }

    public function getPosts()
    {
        if(isset($this->posts))
            return $this->posts;

        if($this->existence)
        {
            $wall = [];
            $posts = R::findAll("posts", "author_id = ?", [$this->id]);
            if ($posts)
                foreach ($posts as $post)
                    $wall[] = $post->id;
            return $wall;
        }
        return null;
    }
}