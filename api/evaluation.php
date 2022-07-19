<?php

require_once '../functions/basic.php';
require_once '../functions/auth.php';

@session_start();

if(!isset($_SESSION['id'])){
    exit();
}

$id = new UserInput('GET','id');

if($id->IsValid()){
    $uid = $_SESSION['id'];
    $aid = $id->Sanitize(Sanitizer::CheckArticleID());
    if($aid===FALSE){
        exit("ERR");
    }
    $output = SQL("SELECT * FROM evaluations WHERE aid = '$aid' AND uid='$uid'");
    if(count($output) > 0){
        exit("true");
    }
    exit("false");
}

$id = new UserInput('POST','id');

if(!$id->IsValid()){
    exit("ERR");
}

$uid = $_SESSION['id'];
$aid = $id->Sanitize(Sanitizer::CheckArticleID());
if($aid===FALSE){
    exit("ERR");
}

$output = SQL("SELECT * FROM evaluations WHERE aid = '$aid' AND uid='$uid'");

if(count($output) > 0){
    SQL("DELETE FROM evaluations WHERE aid = '$aid' AND uid='$uid'");
    SQL("UPDATE stats SET evals = evals - 1 WHERE aid='$aid'");
    exit("false");
}
SQL("INSERT INTO evaluations VALUES ('$aid', '$uid')");
SQL("UPDATE stats SET evals = evals + 1 WHERE aid='$aid'");
exit("true");
