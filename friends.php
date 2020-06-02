<?php
session_start();
if (!isset($_SESSION["name"]))
    if (!isset($_COOKIE['name']))
        header("Location: http://127.0.0.2/login.php");
    else
        $_SESSION["name"]=$_COOKIE['name'];

require "libs/redbeanphp/db.php";
require "classes/render_template.php";

$content = "
<form method='post'>
    <input type='text' name='fr_name'>
    <input type='hidden' name='code' value='add_friend'>
    <button type='submit'>add</button>
</form>
<div id='mess'></div>
<br>
<br>
";

$db = R::findOne('user', 'name = ?', [$_SESSION["name"]]);
preg_match_all('/,(.+?),/',$db->friends, $m);
$fr_list = $m[1];

foreach ($fr_list as $fr)
    $content .= "<div class='friend'>
                    <button class='fr' type='button'>$fr</button>
                </div>
                <br>";
$content .= "<div id='wall'></div>";

$t = new render_template("templates/main.html", ["main", "/css/friends.css", $content, "/js/friends.js"]);
echo $t->render();

