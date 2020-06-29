<?php

class user
{
    public $name, $password;

    public function __construct($name=false, $password=false)
    {
        $this->name = strtolower(trim($name));
        $this->password = trim($password);
        R::selectDatabase( 'default' );
    }

    public function add()
    {
        if(!$this->name or !$this->password)
            return ["status" => "ERROR", "error" => "Заполните все поля"];

        if(!preg_match("/^[a-zA-Z0-9]+$/",$this->name))
            return ["status" => "ERROR",
                "error" => "Логин может состоять только из букв английского алфавита и цифр"];

        $find_user = R::findOne('user', 'name = ?', [$this->name]);
        if ($find_user)
            return ["status" => "ERROR", "error" => "Пользователь существует"];

        $user = R::dispense('user');
        $user->name = $this->name;
        $user->password = md5(md5($this->password));
        $user->friends = "";
        $user->notifications = "";
        $id = R::store($user);
        return $id ? ["status" => "OK"]:["status" => "ERROR", "error" => "Ошибка при добавлении в базу"];

    }

    public function delete()
    {
        $user = R::findOne('user', 'name = ?', [$this->name]);
        $id = R::trash($user);

        //отсылаю уведомления об удаленном друге
        $users = R::findAll("user", "friends LIKE ?", ["%,$this->name,%"]);
        foreach ($users as $user) {
            $count = substr_count($user->notifications,",2($this->name)");
            $count++;
            $user->notifications .= ",2($this->name)$count,";
            R::store($user);
        }
        R::selectDatabase( 'posts' );
        $wall = R::findAll('posts', 'author = ?', [$this->name]);
        foreach ($wall as $item)
            R::trash($item);

        R::selectDatabase( 'messages' );
        $messages = R::findAll("messages", "author = ? OR to_name = ?", [$_SESSION["name"], $_SESSION["name"]]);
        foreach ($messages as $mess)
            R::trash($mess);
        R::selectDatabase( 'default' );
        return $id ? ["status"=>"OK"]:["status"=>"ERROR"];
    }

    public function search()
    {
        if(!$this->name or !$this->password)
            return ["status" => "ERROR", "error" => "Заполните все поля"];

        $find_user = R::findOne('user', 'name = ? AND password = ?', [$this->name, md5(md5($this->password))]);
        return $find_user ? ["status" => "OK"]:["status" => "ERROR", "error" => "Неверное имя или пароль"];
    }

    public function rename($name)
    {
        $name = strtolower(trim($name));
        if(!$name)
            return ["status" => "ERROR", "error" => "Заполните все поля"];

        $new_name_user = R::findOne('user', 'name = ?', [$name]);
        if($new_name_user)
            return ["status"=>"ERROR", "error"=>"Пользователь существует"];

        if(!preg_match("/^[a-zA-Z0-9]+$/",$name))
            return ["status" => "ERROR",
                "error" => "Логин может состоять только из букв английского алфавита и цифр"];

        $new_name_user = R::findOne('user', 'name = ?', [$this->name]);
        $new_name_user->name = $name;
        R::store( $new_name_user );

        //меняю имя пользователя у друзей и добавляю уведомление
        $change_users_friends = R::findAll("user", "friends LIKE ?", ["%,$this->name,%"]);
        foreach ($change_users_friends as $user) {
            $count = substr_count($user->notifications,",1($this->name $name)");
            $count++;
            $user->notifications .= ",1($this->name $name)$count,";
            $user->friends = str_replace(",$this->name,", ",$name,", $user->friends);
            R::store($user);
        }

        // меняю данные сообщений
        R::selectDatabase("messages");
        $messages = R::findAll("messages", "author = ? OR to_name = ?", [$this->name, $this->name]);
        foreach ($messages as $mess)
        {
            if ($mess->author == $this->name)
                $mess->author = $name;
            else
                $mess->to_name = $name;
            R::store($mess);
        }
        R::selectDatabase("default");
        return ["status"=>"OK", "new_name"=>$name];
    }

    public function change_pass($pass)
    {
        $pass = trim($pass);
        if(!$pass)
            return ["status" => "ERROR", "error" => "Заполните все поля"];

        $user = R::findOne("user", "name = ?", [$this->name]);
        $user->password = md5(md5($pass));
        $id = R::store($user);
        return $id ? ["status"=>"OK"]:["status"=>"ERROR"];
    }

    public function add_friend($name)
    {
        $name = strtolower(trim($name));
        if(!$name)
            return ["status" => "ERROR", "error" => "Заполните все поля"];

        if($name == $this->name)
            return ["status" => "ERROR", "error" => "Самого себя нельзя добавть в друзья"];

        $friend = R::findOne('user', 'name = ?', [$name]);

        if (!$friend)
            return ["status" => "ERROR", "error" => "Пользователя не существует"];

        $user = R::findOne('user', 'name = ?', [$this->name]);

        if(preg_match("/,$name,/", $user->friends))
            return ["status"=>"ERROR", "error"=>"Уже в друзьях"];

        $user->friends = $user->friends.",$name,";
        R::store($user);

        $count = substr_count($user->notifications,",3($this->name)");
        $count++;
        $friend->notifications .= ",3($this->name)$count,";
        R::store($friend);
        return ["status"=>"OK", "new_fr_name"=>$name];
    }

    public function remove_from_friends($name)
    {
        $name = strtolower(trim($name));
        $user = R::findOne('user', 'name = ?', [$this->name]);
        $user->friends = str_replace(",$name,", "", $user->friends);
        $id = R::store($user);

        $deleted_friend = R::findOne('user', 'name = ?', [$name]);
        if ($deleted_friend)
        {
            $count = substr_count($deleted_friend->notifications,",4($this->name)");
            $count++;
            $deleted_friend->notifications .= ",4($this->name)$count,";
            R::store($deleted_friend);
        }
        return $id ? ["status"=>"OK"]:["status"=>"ERROR"];
    }

    public function get_wall($author)
    {

        $author = strtolower(trim($author));
        if(!$author)
            return ["status"=>"ERROR", "error"=>"Не задан автор постов"];

        $author_acc = R::findOne("user", "name = ?", [$author]);
        if(!$author_acc)
            return ["status"=>"ERROR", "error"=>"Пользователь удален"];

        $user = R::findOne( 'user', 'name = ?', [$this->name] );
        $fr = $user->friends;

        if(strpos($fr, $author)===false and $author!==$this->name)
            return ["status"=>"ERROR", "error"=>"Пользователя нет у вас в друзьях"];

        R::selectDatabase( 'posts' );
        $posts = R::findAll( 'posts', 'author = ?', [$author] );
        R::selectDatabase( 'default' );

        if(!$posts)
            return ["status"=>"OK", "wall"=>"<span id='no_posts'>Нет записей</span>"];

        else
            {
                $wall = "";
                foreach ($posts as $post)
                {
                    $f = "<form method='POST' id='del_p'>
                            <input type='hidden' name='code' value='delete_post'>
                            <input type='hidden' name='id' value=\"$post->id\">
                            <button type='submit' onclick='del_post_block(\"$post->id\"); return false;'>delete</button>
                        </form>";
                    $wall .= $this->name==$author
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
                return ["status"=>"OK", "wall"=>$wall];
            }
    }

    public function get_notif()
    {
        $user = R::findOne('user', 'name = ?', [$this->name]);
        preg_match_all('/,([0-9])[(](.+?)[)]([0-9]+)?,/', $user->notifications, $m);
        $content = "";
        foreach ($m[0] as $notif) {
            preg_match('/,([0-9])[(](.+?)[)]([0-9]+)?,/', $notif, $notif_data);
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
        return ["status"=>"OK", "text"=>$content, "count"=>count($m[0])];
    }

    public function delete_notif($text)
    {
        $user = R::findOne('user', 'name = ?', [$this->name]);
        $user->notifications = str_replace($text, "", $user->notifications);
        R::store($user);
        return ["status"=>"OK"];
    }

    public function add_message($message_data)
    {
        if (!$message_data["text"])
            return  ["status"=>"ERROR", "error"=>"Введите сообщение"];

        R::selectDatabase("messages");
        $message = R::dispense('messages');
        $message->author = $this->name;
        $message->interlocutor = $message_data["interlocutor"];
        $message->text = $message_data["text"];
        $message->time = date("H:i");
        R::store($message);
        R::selectDatabase("default");
        $mess_block = "<span><strong>$message->author</strong>:&nbsp;&nbsp;$message->text</span><span id='time'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$message->time</span><br>";

        return  ["status"=>"OK", "message"=>$mess_block];
    }

    public function get_messages($interlocutor)
    {

        $interlocutor = strtolower(trim($interlocutor));
        $user = R::findOne( 'user', 'name = ?', [$this->name] );
        $fr = $user->friends;

        $friend = R::findOne("user", "name = ?", [$interlocutor]);
        if(!$friend)
            return ["status"=>"ERROR", "error"=>"Пользователя не существует"];

        if(strpos($fr, $interlocutor)===false)
            return ["status"=>"ERROR", "error"=>"Пользователя нет у вас в друзьях"];

        R::selectDatabase("messages");
        $messages = R::findAll("messages",
            "(author = ? AND interlocutor = ?) OR (author = ? AND interlocutor = ?)",
            [$this->name, $interlocutor, $interlocutor, $this->name]);
        $mess_list = "";
        foreach ($messages as $mess)
            $mess_list .= "<span><strong>$mess->author</strong>:&nbsp;&nbsp;$mess->text</span><span id='time'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$mess->time</span><br>";
        R::selectDatabase("default");

        return ["status"=>"OK", "messages"=>$mess_list];
    }


}