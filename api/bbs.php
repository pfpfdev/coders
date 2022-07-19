<?php

require_once '../functions/basic.php';
require_once '../functions/auth.php';

$id = new UserInput('POST','id');

if($id->IsValid()){
    require_logined_session();
    $uid = $_SESSION['id'];
    $aid = $id->Sanitize(Sanitizer::CheckArticleID());
    if($aid===FALSE){
        exit("ERR");
    }
    $date = new UserInput('POST','date');
    if($date->IsValid()){
        $date = $date->Sanitize(Sanitizer::CheckDatetime());
        if($date!==FALSE){
            SQL("DELETE FROM comments WHERE aid='$aid' AND uid='$uid' AND date=$date");
            SQL("UPDATE stats SET comments = comments - 1 WHERE aid='$aid'");
            exit();
        }
    }
    $content = new UserInput('POST','content');
    if($content->IsValid()){
        $content = $content->Sanitize(function($text){return base64_encode(htmlspecialchars($text));});
        SQL("INSERT INTO comments VALUES ('$aid','$uid', '$content', datetime('now','localtime'))");
        SQL("UPDATE stats SET comments = comments + 1 WHERE aid='$aid'");
        exit();
    }
    exit("ERR");
}

$aid = new UserInput('GET','id');

if(!$aid->IsValid()){
    exit("ERR");
}

$aid = $aid->Sanitize(Sanitizer::CheckArticleID());

if($aid===FALSE){
    exit("ERR");
}

$result = SQL("WITH comment AS(SELECT * FROM comments WHERE aid='$aid' ORDER BY date DESC) SELECT name,content,date FROM comment INNER JOIN users ON users.id=comment.uid");

$embed=function($v){return $v;};
echo "{\"comment\":[";
foreach($result as $i=>$val){
    if($i!==0)echo ",";
    echo "{\"user\":\"$val[0]\",\"content\":\"{$embed(base64_decode($val[1]))}\",\"date\":\"$val[2]\"}";
}
echo "]}";