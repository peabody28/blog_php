<?php
require_once __DIR__."/../db.php";

class User
{
    public $id, $name, $password, $friends, $wall;

    public function __construct($id=null)
    {
        if($id)
        {
            $user = R::findOne("users", "id = ?", [intval($id)]);
            if ($user)
            {
                $this->id = intval($user->id);
                $this->name = $user->name;
                $this->password = $user->password;
            }
        }
    }

    public function getFriendsList()
    {
        if($this->id)
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

        if($this->id)
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