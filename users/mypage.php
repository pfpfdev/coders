<?php
require_once "./include.php";
require_once __ROOT__.'/template/userpage.tpl.php';
require_logined_session();

$page = new userPage();

$page->title = "My page";
$page->name = $_SESSION['name'];
$page->id = $_SESSION['id'];

$page->show();