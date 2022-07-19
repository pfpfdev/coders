<?php

define('__ROOT__', dirname(__FILE__));
require_once 'functions/basic.php';
require_once 'functions/auth.php';
require_once 'template/simple.tpl.php';

$page = new simplePage();

$page->title = "UEC Coders";

$page->addStylesheet("/functions/markdown/css/editormd.min.css");
$page->addStylesheet("viewer.css");

$page->addJavascript("https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js");
$page->addJavascript("/functions/markdown/lib/marked.min.js");
$page->addJavascript("/functions/markdown/lib/prettify.min.js");
$page->addJavascript("/functions/markdown/lib/raphael.min.js");
$page->addJavascript("/functions/markdown/lib/underscore.min.js");
$page->addJavascript("/functions/markdown/lib/flowchart.min.js");
$page->addJavascript("/functions/markdown/lib/jquery.flowchart.min.js");
$page->addJavascript("/functions/markdown/lib/sequence-diagram.min.js");
$page->addJavascript("/functions/markdown/editormd.min.js");
$page->addJavascript("https://cdn.jsdelivr.net/npm/js-md5@0.7.3/src/md5.min.js");
$page->addJavascript("viewer.js");

$page->body=
HTML("div",["id"=>"info"],
    HTML("div",["class"=>"title-wrap"],
        HTML("span",["id"=>"title"],"Now loading...").
        HTML("a",["id"=>"go-edit","hidden"=>"true"],'<i class="far fa-pencil"></i>')
    ).
    HTML("table",
        HTML("tr",
            HTML("td",["class"=>"icon"],'<i class="far fa-eye"></i>').
            HTML("td",["class"=>"icon"],'<i class="far fa-thumbs-up"></i>').
            HTML("td",["class"=>"icon"],'<i class="far fa-comments"></i>').
            HTML("td",["id"=>"date"],"2020 00/00")
        ).
        HTML("tr",
            HTML("td",["id"=>"pv"],'0').
            HTML("td",["id"=>"good"],'0').
            HTML("td",["id"=>"comments"],'0').
            HTML("td","Auther: ".HTML("a",["id"=>"auther"],"XXXX"))
        )
    )
).
HTML("div",["id"=>"view"],HTML("textarea",["style"=>"display:none"])).
call_user_func(function(){
    @session_start();
    if(isset($_SESSION['id'])){
        return HTML("div",["id"=>"survey"],
            HTML("div",["id"=>"survey-submit"],'<i class="far fa-thumbs-up"></i>').
            HTML("div",["id"=>"survey-explain"],
                "良い記事は上のボタンを押して評価できます"
            )
        );
    }
    return HTML("div",["id"=>"survey"],
        HTML("div",["id"=>"survey-submit"],'<i class="far fa-thumbs-up"></i>').
        HTML("div",["id"=>"survey-explain"],
            "評価はログイン後に可能になります"
        )
    );
}).
HTML("div",["id"=>"bbs"],
    HTML("div",["id"=>"bbs-title"],"Comments").
    HTML("div",["id"=>"bbs-contents"],
        HTML("div",["id"=>"bbs-empty"],"コメントはありません")
    ).
    HTML("div",["hidden"=>"true","class"=>"bbs-list-template"],
        HTML("ul",["class"=>"bbs-list"],"TEMPLATE")
    ).
    HTML("div",["hidden"=>"true","class"=>"bbs-item-template"],
        HTML("li",["class"=>"bbs-item"],
            HTML("span",["class"=>"bbs-user"],"ID:USER").HTML("span",["class"=>"bbs-date"],"DATE").
            HTML("div",["class"=>"bbs-content"],"CONTENT")
        )
    ).
    call_user_func(function(){
        @session_start();
        if(isset($_SESSION['id'])){
            return HTML("form",["id"=>"bbs-form"],
                HTML("textarea",["name"=>"content","placeholder"=>"NewComment"]).
                HTML("div",["id"=>"bbs-submit"],"送信")
            );
        }
        return HTML("div",["class"=>"bbs-noform"],"コメントはログイン後に可能になります");
    })
);


$page->show();