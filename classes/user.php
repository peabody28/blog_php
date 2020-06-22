<?php

class user
{
    public $name, $password;

    public function __construct($name=false, $password=false)
    {
        $this->name = strtolower($name);
        $this->password = $password;
    }

    public function add()
    {
        if(!$this->name or !$this->password)
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        $find_user = R::findOne('user', 'name = ?', [$this->name]);
        if ($find_user)
            return ["STATUS" => "ERROR", "ERROR" => "Пользователь существует"];
        $user = R::dispense('user');
        $user->name = $this->name;
        $user->password = $this->password;
        $id = R::store($user);
        return $id ? ["STATUS" => "OK"]:["STATUS" => "ERROR", "ERROR" => "Ошибка при добавлении в базу"];

    }

    public function delete()
    {
        $user = R::findOne('user', 'name = ?', [$this->name]);
        $id = R::trash($user);
        return $id ? ["STATUS"=>"OK"]:["STATUS"=>"ERROR"];
    }

    public function search()
    {
        if(!$this->name or !$this->password)
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        $find_user = R::findOne('user', 'name = ? AND password = ?', [$this->name, $this->password]);
        return $find_user ? ["STATUS" => "OK"]:["STATUS" => "ERROR", "ERROR" => "Пользователь не найден"];
    }

    public function rename()
    {
        if(!$this->name)
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        $new_name_user = R::findOne('user', 'name = ?', [$this->name]);

        if($new_name_user)
            return ["STATUS"=>"ERROR", "ERROR"=>"Пользователь существует"];
        unset($new_name_user);
        $new_name_user = R::findOne('user', 'name = ?', [$_SESSION["name"]]);
        $new_name_user->name = $this->name;
        R::store( $new_name_user );

        $change_users_friends = R::findAll("user", "friends LIKE ?", ["%,$_SESSION[name],%"]);
        foreach ($change_users_friends as $user) {
            $user->friends = str_replace(",$_SESSION[name],", ",$this->name,", $user->friends);
            R::store($user);
        }
        return ["STATUS"=>"OK", "NEW_NAME"=>$this->name];
    }

    public function add_friend()
    {
        if(!$this->name)
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        if($this->name == $_SESSION["name"])
            return ["STATUS" => "ERROR", "ERROR" => "Самого себя нельзя добавть в друзья"];

        $friend = R::findOne('user', 'name = ?', [$this->name]);
        if (!$friend)
            return ["STATUS" => "ERROR", "ERROR" => "Пользователя не существует"];

        $user = R::findOne('user', 'name = ?', [$_SESSION["name"]]);
        if(stristr(",".$this->name.",", $user->friends))
            return ["STATUS"=>"ERROR", "ERROR"=>"Уже в друзьях"];
        $user->friends = $user->friends.",".$this->name.",";
        R::store($user);
        return ["STATUS"=>"OK", "NEW_FR"=>$this->name];
    }

    public function remove_from_friends($name)
    {
        $user = R::findOne('user', 'name = ?', [$this->name]);
        $user->friends = str_replace(",$name,", "", $user->friends);
        $id = R::store($user);
        return $id ? ["STATUS"=>"OK"]:["STATUS"=>"ERROR"];
    }

    function get_wall()
    {
        R::selectDatabase( 'posts' );
        $posts = R::findAll( 'posts', 'author = ?', [$this->name] );
        R::selectDatabase( 'default' );

        $wall = "";
        if(!$posts)
            return ["STATUS"=>"OK", "TEXT"=>"Нет записей"];
        else
            foreach ($posts as $post)
                $wall .= "<div class='post'><div class='title'>$post->title <span style='opacity: 0.6'>@$post->author</span></div><div class='text'>$post->text</div></div><br>";
        return ["STATUS"=>"OK", "TEXT"=>$wall];
    }

    function clear_wall()
    {
        R::selectDatabase( 'posts' );
        $wall = R::findAll('posts', 'author = ?', [$_SESSION["name"]]);
        foreach ($wall as $item) {
            R::trash($item);
        }
    }
}