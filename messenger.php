<?php
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/UsersTable.php";
require_once __DIR__ . "/classes/PostBlock.php";
require_once __DIR__ . "/classes/Message.php";
require_once __DIR__ . "/classes/MessagesTable.php";
require_once __DIR__ . "/classes/MessageBlock.php";
require_once __DIR__ . "/classes/MessageTools.php";
require_once __DIR__ . "/classes/Chat.php";
require_once __DIR__ . "/auth.php";
auth();


if (isset($_POST["submit"]))
{
    $data = $_POST;

    switch ($data["code"])
    {
        case "add_message":
            $message = new Message();
            $message->author = $data["author"];
            $message->target = $data["target"];
            $message->text = trim($data["message_text"]);

            $messageTools = new MessageTools();
            $response = $messageTools->send($message);

            if ($response["status"]==="OK")
            {
                $messageBlock = new MessageBlock();
                $message->born_time = $response["message"]->born_time;
                echo json_encode(["status"=>"OK", "message_block"=>$messageBlock->getHtml($message)]);
            }
            else
                echo json_encode($response);
            break;

        case "get_chat_by_name":
            $user = new User();
            $user->name = strtolower(trim($data["name"]));

            $usersTable = new UsersTable();
            $target = $usersTable->read($user);

            echo json_encode($target?["status"=>"OK", "id"=>$target->id]:["status"=>"ERROR", "error"=>"Пользователя не существует"]);
    }

}
else
{
    $data = $_GET;

    $error = "";
    $messages = "";
    $addMessageForm = "";

    if (isset($data["id"]))
    {
        $target = new User($data["id"]);
        if (isset($target->id))
        {
            $author = new User($_SESSION["id"]);
            if (in_array($target->id, array_column($author->getFriendsList(), "id")))
            {
                $chat = new Chat();
                $chat->author = $author->id;
                $chat->target = $target->id;

                $messageTools = new MessageTools();
                $messages = $messageTools->getChat($chat);

                $addMessageForm = "<form id='add_message' method='POST'>
                                        <input type='text' name='message_text'>
                                        <input type='hidden' name='target' value='$target->id'>
                                        <input type='hidden' name='author' value='$author->id'>
                                        <input type='hidden' name='code' value='add_message'>
                                        <input type='hidden' name='submit' value='true'>
                                        <input type='submit' value='send'>
                                        <div class='error'></div>
                                    </form>";
            }
            elseif ($target->id == $author->id)
                $error = "Вы не можете писать себе";
            else
                $error = "Пользователя нет у вас в друзьях";
        }
        else
            $error = "Пользователя не существует";

    }

    $content = "<form id='get_chat_by_name' method='POST'>
                    <strong>Укажите имя друга</strong><br>
                    <input type='text' name='name'>
                    <input type='hidden' name='submit'>
                    <input type='hidden' name='code' value='get_chat_by_name'>
                    <input type='submit'>
                    <div class='error'>$error</div>
                </form>
                <br>
                <br>
                <div id='chat'><div id='messages' class='container'>$messages</div></div><br><br>
                <div id='add_mess_form_block'>$addMessageForm</div>";

    $loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title' => "messenger", 'css' => "/css/messenger.css", "content" => $content, "js" => "/js/Messenger.js"]);

}
