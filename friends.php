<?php
require_once "classes/User.php";
require_once "classes/UserTools.php";
require_once "vendor/autoload.php";
require_once "in.php";
access();


$data = $_POST;

if (isset($data["submit"]))
{
    switch ($data["code"])
    {
        case "add_friend":
            $user = new User();
            $user->name = strtolower(trim($data["fr_name"]));

            $tools = new UserTools();
            $resp = $tools->addToFriends($user);

            if ($resp["status"]==="OK")
            {
                $fr_block = "<div class='friend col-sm-5'>
                                <div class='fr_div'><a href='/friend.php?name=$user->name'>$user->name</a></div>
                                <div class='fr_div'><a href='/messenger.php?interlocutor=$user->name'>send mess</a></div>
                                <div class='fr_div'>
                                    <form method='POST'>
                                        <input type='hidden' name='fr_name' value=\"$user->name\">
                                        <input type='hidden' name='code' value='remove_from_friends'>
                                        <input type='hidden' name='submit'>
                                        <div class='del_fr' type='submit'>удалить</div>
                                    </form>
                                </div>
                                <br>
                                <br>
                            </div>";

                echo json_encode(["status"=>"OK", "fr_block"=>$fr_block]);
            }
            else
                echo json_encode($resp);
            break;

        case "remove_from_friends":
            $user = new User();
            $user->name = strtolower(trim($data["fr_name"]));

            $tools = new UserTools();
            $resp = $tools->removeFromFriends($user);

    }
}
else
{
    $content = "
                <form id='add_f' method='POST'>
                    <input type='text' name='fr_name'>
                    <input type='hidden' name='code' value='add_friend'>
                    <input type='hidden' name='submit'>
                    <button type='submit'>add</button>
                </form>
                <div id='mess'></div>
                <br>
                <br>
                <div id='wall'>";

    $user = new User($_SESSION["id"]);

    $fr_list = [];

    foreach ($user->friends as $friend_id) {
        $friend = new User($friend_id);
        if ($friend)
            $fr_list[]=$friend->name;
    }

    foreach ($fr_list as $fr)
        $content .= "<div class='friend col-sm-5'>
                    <div class='fr_div'><a href='/friend.php?name=$fr'>$fr</a></div>
                    <div class='fr_div'><a href='/messenger.php?interlocutor=$fr'>send mess</a></div>
                    <div class='fr_div'>
                        <form method='POST'>
                            <input type='hidden' name='fr_name' value=\"$fr\">
                            <input type='hidden' name='code' value='remove_from_friends'>
                            <input type='hidden' name='submit'>
                            <div class='del_fr' type='submit'>удалить</div>
                        </form>
                    </div>
                    <br>
                    <br>
                </div>";
    $content .= "</div>";

    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title'=>"main", 'css'=>"/css/friends.css",
            'content'=>$content, "js"=>"/js/friends.js"] );
}

