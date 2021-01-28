<?php
require_once __DIR__."/auth.php";
auth();
header("Location: /main.php");
