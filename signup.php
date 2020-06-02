<?php
require "classes/render_template.php";

$t = new render_template("templates/login.html",
    ["signup", "РЕГИСТРАЦИЯ", "signup", "Создать аккаунт", "/login.php", "Логин", "/js/in.js"]);
echo $t->render();
?>
