<?php

define('__ROOT__', dirname(__FILE__));
require_once 'functions/basic.php';
require_once 'functions/auth.php';
require_once 'template/simple.tpl.php';
require_once 'template/components/pagecard.php';

$page = new simplePage();

$page->title = "UEC Coders";
$page->addStylesheet("pagecard.css");

$page->body ="";

$page->body.=
HTML("div",["class"=>"pagelist"],
    HTML("div",["class"=>"pagelist-title"],"すべての記事一覧").
    pagecard::generate("SELECT users.name,users.id,articles.id,title,date,pvs,comments,evals FROM articles INNER JOIN users ON users.id = articles.user INNER JOIN stats ON articles.id = stats.aid")
);

$page->show();