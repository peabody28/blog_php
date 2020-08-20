<?php
require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/classes/User.php";
require_once __DIR__."/classes/UserTools.php";
require_once __DIR__."/classes/FriendBlock.php";
require_once __DIR__."/auth.php";
auth();

$data = $_POST;

if (isset($data["submit"]))
{
    switch ($data["code"])
    {
        case "add_friend":
            $user = new User($_SESSION["id"]);
            $friend = new User();
            $friend->name = strtolower(trim($data["name"]));

            $userTools = new UserTools();
            $response = $userTools->addToFriends($user, $friend);
            if ($response["status"]==="OK")
            {
                $friendBlock = new FriendBlock();
                echo json_encode(["status"=>"OK", "friend_block"=>$friendBlock->getHtml($friend)]);
            }
            else
                echo json_encode($response);
            break;

        case "remove_friend":
            $user = new User($_SESSION["id"]);
            $user->friends = $user->getFriendsList();
            $friend = new User($data["id"]);

            $userTools = new UserTools();
            $response = $userTools->removeFromFriends($user, $friend);
            if ($response["status"]==="OK")
                echo json_encode(["status"=>"OK", "id"=>$friend->id]);
            else
                echo json_encode($response);
            break;
    }
}
else
{

    $friendsBlocks = "";

    $user = new User($_SESSION["id"]);
    $friends = $user->getFriendsList();

    $friendBlock = new FriendBlock();
    foreach ($friends as $user)
    {
        $friend = new User();
        $friend->id = $user["id"];
        $friend->name = $user["name"];
        $friendsBlocks .= $friendBlock->getHtml($friend);
    }
    $content =
        "<form id='add_friend' method='POST'>
            <input type='hidden' name='submit'>
            <input type='hidden' name='code' value='add_friend'>
            <input type='text' name='name'>
            <input type='submit'>
        </form>
        <div id='error'></div>
        <br>
        <br>
        <br>
        <div id='friend_list'>$friendsBlocks</div>";

    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title'=>"friends", 'css'=>"/css/friends.css", "content"=>$content, "js"=>"/js/Friends.js"] );

}

