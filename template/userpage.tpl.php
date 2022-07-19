<?php
require_once 'simple.tpl.php';
require_once 'components/pagecard.php';

class userPage extends simplePage{
    public $id = "";
    public $name = "";
    function __construct(){
        $this->addStylesheet("user.css");
        $this->addStylesheet("pagecard.css");
    }
    function show(){
        exec(SQL."\"SELECT id FROM articles WHERE user='$this->id'\"",$postQuery);
        $posts=count($postQuery);
        exec(SQL."\"SELECT aid FROM comments WHERE uid='$this->id'\"",$commentQuery);
        $comments=count($commentQuery);
        exec(SQL."\"SELECT aid FROM evaluations WHERE uid='$this->id'\"",$evalQuery);
        $evals=count($evalQuery);

        $this->body = 
        HTML("div",["id"=>"user-info"],
            HTML("img",["src"=>"{$this->id}/icon.png"]).
            HTML("div",["id"=>"user-name"],"{$this->name}").
            HTML("table",
                HTML("thead",HTML("tr",
                    HTML("th","投稿").
                    HTML("th","コメント").
                    HTML("th","評価")
                )).
                HTML("tr",
                    HTML("td","$posts").
                    HTML("td","$comments").
                    HTML("td","$evals")
                )
            )
        );

        $this->body.= HTML("div",["class"=>"pagelist"],
            HTML("div",["class"=>"pagelist-title"],"投稿一覧").
            pagecard::generate("WITH user AS(SELECT * FROM users WHERE id = '$this->id')SELECT user.name,user.id,articles.id,title,date,pvs,comments,evals FROM user INNER JOIN articles ON user.id = articles.user INNER JOIN stats ON articles.id = stats.aid")
        );

        $this->body.= HTML("div",["class"=>"pagelist"],
            HTML("div",["class"=>"pagelist-title"],"コメントした記事一覧").
            pagecard::generate("WITH comment AS(SELECT DISTINCT aid FROM comments WHERE uid = '$this->id')SELECT users.name,users.id,articles.id,title,articles.date,pvs,comments,evals FROM comment INNER JOIN articles ON comment.aid = articles.id INNER JOIN users ON articles.user = users.id INNER JOIN stats ON articles.id = stats.aid")
        );

        $this->body.= HTML("div",["class"=>"pagelist"],
            HTML("div",["class"=>"pagelist-title"],"評価した記事一覧").
            pagecard::generate("WITH eval AS(SELECT DISTINCT aid FROM evaluations WHERE uid = '$this->id')SELECT DISTINCT users.name,users.id,articles.id,title,articles.date,pvs,comments,evals FROM eval INNER JOIN articles ON eval.aid = articles.id INNER JOIN users ON articles.user = users.id INNER JOIN stats ON articles.id = stats.aid")
        );
        
        parent::show();
    }
}