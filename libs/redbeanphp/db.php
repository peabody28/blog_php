<?php
require "rb.php";
R::setup( 'mysql:host=localhost;dbname=users',
    'root', '1234' );
R::addDatabase( 'posts', 'mysql:host=localhost;dbname=posts', 'root', '1234');