<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/php/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER["DOCUMENT_ROOT"]);
$dotenv->load();