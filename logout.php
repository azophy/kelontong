<?php
require_once("./script/config.php");

session_start();
session_destroy();
header("location: ".$site_path."index.php");
?>
