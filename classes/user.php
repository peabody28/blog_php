<?php

class user
{
    public $name, $password;

    public function __construct($name=false, $password=false)
    {
        $this->name = strtolower($name);
        $this->password = $password;
        R::selectDatabase( 'default' );
    }

    public function add()
    {
        if(!$this->name or !$this->password)
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        if(!preg_match("/^[a-zA-Z0-9]+$/",$this->name))
            return ["STATUS" => "ERROR",
                "ERROR" => "Логин может состоять только из букв английского алфавита и цифр"];

        $find_user = R::findOne('user', 'name = ?', [$this->name]);
        if ($find_user)
            return ["STATUS" => "ERROR", "ERROR" => "Пользователь существует"];

        $user = R::dispense('user');
        $user->name = $this->name;
        $user->password = md5(md5(trim($this->password)));
        $user->friends = "";
        $user->notifications = "";
        $id = R::store($user);
        return $id ? ["STATUS" => "OK"]:["STATUS" => "ERROR", "ERROR" => "Ошибка при добавлении в базу"];

    }

    public function delete()
    {
        $user = R::findOne('user', 'name = ?', [$this->name]);
        $id = R::trash($user);

        //отсылаю уведомления об удаленном друге
        $users = R::findAll("user", "friends LIKE ?", ["%,$this->name,%"]);
        foreach ($users as $user) {
            $user->notifications .= ",2($this->name),";
            R::store($user);
        }
        return $id ? ["STATUS"=>"OK"]:["STATUS"=>"ERROR"];
    }

    public function search()
    {
        if(!$this->name or !$this->password)
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        $find_user = R::findOne('user', 'name = ? AND password = ?', [$this->name, md5(md5(trim($this->password)))]);
        return $find_user ? ["STATUS" => "OK"]:["STATUS" => "ERROR", "ERROR" => "Неверное имя или пароль"];
    }

    public function rename($name)
    {
        if(!$name)
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        $new_name_user = R::findOne('user', 'name = ?', [$name]);
        if($new_name_user)
            return ["STATUS"=>"ERROR", "ERROR"=>"Пользователь существует"];
        unset($new_name_user);

        $new_name_user = R::findOne('user', 'name = ?', [$this->name]);
        $new_name_user->name = $name;
        R::store( $new_name_user );

        //меняю имя пользователя у друзей и добавляю уведомление
        $change_users_friends = R::findAll("user", "friends LIKE ?", ["%,$this->name,%"]);
        foreach ($change_users_friends as $user) {
            $user->friends = str_replace(",$this->name,", ",$name,", $user->friends);
            $user->notifications .= ",1($this->name $name),";
            R::store($user);
        }
        return ["STATUS"=>"OK", "NEW_NAME"=>$name];
    }


    public function change_pass($pass)
    {
        $user = R::findOne("user", "name = ?", [$this->name]);
        $user->password = md5(md5(trim($pass)));
        $id = R::store($user);
        return $id ? ["STATUS"=>"OK"]:["STATUS"=>"ERROR"];
    }
    public function add_friend($name)
    {
        if(!$name)
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        if($name == $this->name)
            return ["STATUS" => "ERROR", "ERROR" => "Самого себя нельзя добавть в друзья"];

        $friend = R::findOne('user', 'name = ?', [$name]);

        if (!$friend)
            return ["STATUS" => "ERROR", "ERROR" => "Пользователя не существует"];

        $user = R::findOne('user', 'name = ?', [$this->name]);

        if(stristr(",".$name.",", $user->friends))
            return ["STATUS"=>"ERROR", "ERROR"=>"Уже в друзьях"];

        $user->friends = $user->friends.",".$name.",";
        R::store($user);

        $friend->notifications .= ",3($this->name),";
        R::store($friend);

        return ["STATUS"=>"OK", "NEW_FR"=>$name];
    }

    public function remove_from_friends($name)
    {
        $user = R::findOne('user', 'name = ?', [$this->name]);
        $user->friends = str_replace(",$name,", "", $user->friends);
        $id = R::store($user);

        $deleted_friend = R::findOne('user', 'name = ?', [$name]);
        if ($deleted_friend)
        {
            $deleted_friend->notifications .= ",4($this->name),";
            R::store($deleted_friend);
        }
        return $id ? ["STATUS"=>"OK"]:["STATUS"=>"ERROR"];
    }

    public function get_wall($name)
    {
        if(!$name)
            return ["STATUS"=>"ERROR", "ERROR"=>"Не задан автор постов"];

        R::selectDatabase( 'posts' );
        $posts = R::findAll( 'posts', 'author = ?', [$name] );
        R::selectDatabase( 'default' );

        $wall = "";
        if(!$posts)
            return ["STATUS"=>"OK", "TEXT"=>"<span id='no_posts'>Нет записей</span>"];
        else
            {
                foreach ($posts as $post)
                {
                    $f = "<form method='POST' id='del_p'>
                            <input type='hidden' name='code' value='delete_post'>
                            <input type='hidden' name='id' value=\"$post->id\">
                            <button type='submit' onclick='del_post_block(\"$post->id\"); return false;'>delete</button>
                        </form>";
                    $wall .= $name==$_SESSION["name"]
                        ? "<div>
                            <div class='post' id=\"$post->id\">
                            <div class='title'>$post->title <span style='opacity: 0.6'>@$post->author</span></div>
                            <div class='text'>$post->text</div>
                            $f
                            </div>
                            <br>
                        </div>" : "<div>
                            <div class='post' id=\"$post->id\">
                            <div class='title'>$post->title <span style='opacity: 0.6'>@$post->author</span></div>
                            <div class='text'>$post->text</div>
                            </div>
                            <br>
                        </div>";
                }
            }
        return ["STATUS"=>"OK", "TEXT"=>$wall];
    }

    public function clear_wall()
    {
        R::selectDatabase( 'posts' );
        $wall = R::findAll('posts', 'author = ?', [$_SESSION["name"]]);
        foreach ($wall as $item) {
            R::trash($item);
        }
        R::selectDatabase( 'default' );
    }
    public function get_notif()
    {
        $user = R::findOne('user', 'name = ?', [$this->name]);
        preg_match_all('/,([0-9])[(](.+?)[)],/', $user->notifications, $m);
        $content = "";
        foreach ($m[0] as $notif) {
            preg_match('/,([0-9])[(](.+?)[)],/', $notif, $notif_data);
            $code = $notif_data[1];
            $argv = explode(" ",$notif_data[2]);
            switch ($code)
            {
                case "1":
                    $content .= "
                        <div class='pl-3'>
                            <div class='notif row col-sm-6 pr-0'>
                                <span><strong>$argv[0]</strong> меняет имя на <strong>$argv[1]</strong></span>
                                <div class='delete_notif' id=\"$notif\" onclick='del_notif(\"$notif\")'>x</div>
                            </div>
                            <br>
                        </div>";
                    break;
                case "2":
                    $content .= "
                        <div class='pl-3' >
                            <div class='notif row col-sm-6 pr-0'>
                                <span><strong>$argv[0]</strong> удалил аккаунт</span>
                                <div class='delete_notif' id=\"$notif\" onclick='del_notif(\"$notif\")'>x</div>
                            </div>
                            <br>
                        </div>";
                    break;
                case "3":
                    $content .= "
                        <div class='pl-3'>
                            <div class='notif row col-sm-6 pr-0'>
                                <span><strong>$argv[0]</strong> добавил вас в друзья</span>
                                <div class='delete_notif' id=\"$notif\" onclick='del_notif(\"$notif\")'>x</div>
                            </div>
                            <br>
                        </div>";
                    break;
                case "4":
                    $content .= "
                        <div class='pl-3'>
                            <div class='notif row col-sm-6 pr-0'>
                                <span><strong>$argv[0]</strong> удалил вас из друзей</span>
                                <div class='delete_notif' id=\"$notif\" onclick='del_notif(\"$notif\")'>x</div>
                            </div>
                            <br>
                        </div>";
                    break;
            }
        }
        return ["STATUS"=>"OK", "TEXT"=>$content, "count"=>count($m[0])];
    }

    public function delete_notif($text)
    {
        $user = R::findOne('user', 'name = ?', [$this->name]);
        $user->notifications = str_replace($text, "", $user->notifications);
        R::store($user);
        return ["STATUS"=>"OK"];
    }
}