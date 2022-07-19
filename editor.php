<?php

define('__ROOT__', dirname(__FILE__));
require_once 'functions/basic.php';
require_once 'functions/auth.php';
require_once 'template/simple.tpl.php';

$page = new simplePage();

$page->title = "Editor";

$page->addStylesheet("/functions/markdown/css/editormd.min.css");
$page->addStylesheet("editor.css");

$page->addJavascript("https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js");
$page->addJavascript("/functions/markdown/editormd.min.js");
$page->addJavascript("/functions/markdown/languages/en.js");
$page->addJavascript("editor.js");

$page->body = 
HTML("table",["id"=>"controller"],
    HTML("tr",
        HTML("td",HTML("input",["type"=>"text","id"=>"title"])).
        HTML("td",HTML("div",["id"=>"save"],"保存"))
    )
).HTML("div",["id"=>"editor"],
    HTML("textarea",["style"=>"display:none"])
);

$page->show();
