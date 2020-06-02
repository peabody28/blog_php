<?php
require "classes/render_template.php";

$t = new render_template("templates/login.html",
    ["login", "АВТОРИЗАЦИЯ", "login", "ВХОД", "/signup.php", "Регистрация", "/js/in.js"]);

echo $t->render();
?>
