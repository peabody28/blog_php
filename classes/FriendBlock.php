<?php
require_once __DIR__."/Html.php";

class FriendBlock implements Html
{
    public function getHtml($user)
    {
        return
            "
            <div class='container row friend_block' id='$user->id'>
                <div class='friend_name col-sm-4'><a href='/friend-wall.php?id=$user->id'>$user->name</a></div>
                <div class='send_mess col-sm-4 '><a href='/messenger.php?id=$user->id'>Написать</a></div>
                <div class='remove_friend col-sm-4' >
                    <form id='remove_friend' method='post' onsubmit='remove_friend(\"$user->id\"); return false;'>
                        <input type='hidden' name='submit'>
                        <input type='hidden' name='code' value='remove_friend'>
                        <input type='hidden' name='id' value='$user->id'>
                        <input id='delete_button' type='submit' value='Удалить' >
                    </form>
                </div>
            <br>
            <br>
            </div>  
            
            ";
    }
}