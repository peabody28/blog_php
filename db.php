<?php
require_once __DIR__."/vendor/autoload.php";


class_alias('\RedBeanPHP\R', '\R');

R::setup( 'mysql:host=localhost;dbname=blog',
    'admin', '1234' );
