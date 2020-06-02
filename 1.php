<?php
session_start();
$str = "%,$_SESSION[name],%";
echo $str;