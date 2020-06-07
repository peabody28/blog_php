<?php
session_start();


$str = "z vfrbcv 'k flhtc - onmmanmn@gmail.com";
preg_match("/(\S+)@([a-z0-9.]+)/is", $str, $m);
var_dump($m);

