<?php
require_once __DIR__."/Html.php";

class FriendBlock implements Html
{
    public function getHtml($friend)
    {
        return
            "
            <div class='container row friend_block' id='$friend->id'>
                <div class='friend_name col-sm-4'><a href='/friend-wall.php?id=$friend->id'>$friend->name</a></div>
                <div class='send_mess col-sm-4 '><a href='/messenger.php?id=$friend->id'>Написать</a></div>
                <div class='remove_friend col-sm-4' >
                    <form id='remove_friend' method='post' onsubmit='remove_friend(\"$friend->id\"); return false;'>
                        <input type='hidden' name='submit'>
                        <input type='hidden' name='code' value='remove_friend'>
                        <input type='hidden' name='id' value='$friend->id'>
                        <input id='delete_button' type='submit' value='Удалить' >
                    </form>
                </div>
            <br>
            <br>
            </div>  
            
            ";
    }
}