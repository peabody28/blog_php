<?php

class user
{
    public $name, $password;

    function __construct($name=false, $password=false)
    {
        $this->name = strtolower($name);
        $this->password = $password;
    }

    function add()
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

    function delete()
    {
        $user = R::findOne('user', 'name = ?', [$_SESSION["name"]]);
        R::trash($user);
    }

    function search()
    {
        if(!$this->name or !$this->password)
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        $find_user = R::findOne('user', 'name = ? AND password = ?', [$this->name, $this->password]);
        return $find_user ? ["STATUS" => "OK"]:["STATUS" => "ERROR", "ERROR" => "Пользователь не найден"];
    }

    function rename()
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

    function add_friend()
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

    function get_wall()
    {
        R::selectDatabase( 'posts' );
        $posts = R::findAll( 'posts', 'author = ?', [$this->name] );
        R::selectDatabase( 'default' );

        $wall = "";
        if(!$posts)
            $wall = "Нет записей";
        else
            foreach ($posts as $post)
                $wall .= "<div class='post'>
                        <div class='title'>$post->title <span style='opacity: 0.6'>@$post->author</span></div>
                        <div class='text'>$post->text</div>
                    </div><br>";
        return $wall;
    }
}