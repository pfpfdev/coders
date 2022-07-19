<?php

require_once '../functions/basic.php';
require_once '../functions/auth.php';

$aid = new UserInput('POST','id');
$title = new UserInput('POST','title');
$content = new UserInput('POST','content');

if($aid->IsValid()&&$title->IsValid()&&$content->IsValid()){
    require_logined_session();
    $user = $_SESSION['id'];

    $aid = $aid->Sanitize(Sanitizer::CheckArticleID());
    if($aid===FALSE){
        exit("ERR");
    }

    $title = $title->Sanitize(function ($text){return base64_encode(htmlspecialchars($text));});

    //ランダムなIDがかぶったらヤバそう
    $output = SQL("SELECT user FROM articles WHERE id = '$aid'");
    if(count($output) > 0){
        if($output[0][0]!=$user){
            exit("ERR");
        }
        //既存の記事の更新
        SQL("UPDATE articles SET title = '$title', date = datetime('now','localtime') WHERE id = '$aid' AND user = '$user'");
    }else{
        SQL("INSERT INTO articles VALUES ('$aid', '$user', '$title', datetime('now','localtime'))");
        SQL("INSERT INTO stats VALUES ('$aid', 0, 0, 0, 0)");
    }

    $path=__USER__."/".$user."/".$aid;
    file_put_contents($path,$content->Sanitize(Sanitizer::RawText()));
    chmod($path,0666);

    exit("OK");
}

$aid = new UserInput('GET','id');
$target = new UserInput('GET','target');

if(!($aid->IsValid()&&$target->IsValid())){
    exit("ERR");
}

$aid = $aid->Sanitize(Sanitizer::CheckArticleID());
$target = $target->Sanitize(Sanitizer::RawText());

$functions=[
    "markdown"=>function($id){
        if($id===FALSE){
            exit("# Markdown");
        }
        $output = SQL("SELECT user FROM articles WHERE id = '$id'");
        if(count($output) == 0){
            exit("# 404 Not found");
        }
        if(count($output)!=1){
            exit("# 404 Not found");
        }
        $user = $output[0][0];
        $content = file_get_contents(__USER__."/".$user."/".$id);
        if($content == FALSE){
            exit("# 404 Not found");
        }
        SQL("UPDATE stats SET pvs = pvs + 1 WHERE aid='$id'");
        echo $content;
    },"info"=>function($id){
        @session_start();
        $whoami=isset($_SESSION['name'])?$_SESSION['name']:"##";
        if($id===FALSE){
            exit('{"ERROR":true}');
        }
        $result=[];
		while(count($result)!=1 or count($result[0])!=6 ){
			$result = SQL("WITH article AS(SELECT * FROM articles WHERE id = '$id') SELECT title,name,date,pvs,comments,evals FROM article INNER JOIN stats ON stats.aid = article.id INNER JOIN users ON users.id = article.user");
		}
        $title=base64_decode($result[0][0]);
        $auther=$result[0][1];
        $date=$result[0][2];
        $pvs=$result[0][3];
        $comments=$result[0][4];
        $evals=$result[0][5];
        echo "{\"title\":\"$title\",\"auther\":\"$auther\",\"whoami\":\"$whoami\",\"date\":\"$date\",\"thumbs\":$evals,\"pvs\":$pvs,\"comments\":$comments}";
    }
];

if(!isset($functions[$target])){
    exit("ERR");
}
$functions[$target]($aid);