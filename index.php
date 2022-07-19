<?php

define('__ROOT__', dirname(__FILE__));
require_once 'functions/basic.php';
require_once 'functions/auth.php';
require_once 'template/simple.tpl.php';
require_once 'template/components/pagecard.php';

class ImpressivePage extends simplePage{
    protected $stylesheet = ["basic.css","icon.css","body.css","impressive.css","footer.css"];
    protected function includeHeader(){
        return 
        HTML("div",["class"=>"header-wrapper"],
            HeaderIcon::generate().HeaderTitle::generate().HeaderUserControl::generate().
            HTML("div",["class"=>"explain"],
                HTML("img",["src"=>"/template/res/icon.png"]).br().
                HTML("b","UEC Coders").br().
                HTML("small",HTML("i","Platform for Engineers")).br().br().
                "Lets Share Your Experiences!"
            )
        );
    }
}
$page = new ImpressivePage();

$page->title = "UEC Coders";
$page->addStylesheet("pagecard.css");

$page->body =
HTML("h1",HTML("center","UEC Codersへようこそ")).
HTML("p",HTML("center","UEC Codersは技術者の集合知を形成するためのプラットフォームです。")).
HTML("p",["class"=>"intro"],
    "訪問者は記事の閲覧、作成、評価、コメントができます。".
    "詳しい説明は".HTML("a",["href"=>"/viewer.php#AkksuCxojop8KliIY1j"],"説明書")."を参照ください。"
).
HTML("p",["class"=>"intro"],
    "このシステムは昨年のAESの授業で作成した静的Webサイトを実際に動作させてみたくて作成しています。".
    "昨年はsol上でDOMでどうにか実装していた機能がPHPで実際に動作するようになりました。sol.edu.cc.uec.ac.jpでアクセス可能なので、ソースも含めて比較すると面白いかもしれません。"
).
HTML("p",["class"=>"intro"],
    "このシステムはjQueryやMarkdownエディタなどのJSライブラリを多少利用していますが、サーバーサイドはすべてフルスクラッチです。".
    "このシステムには技術系の記事が必要なので、そのへんもすべて記事として公開しました。参考になるといいなと思います。"   
).
HTML("p",["class"=>"intro"],
    "メタい話はこれくらいにして、是非このシステムをいろいろと試してみてください。"
);

$page->body.=
HTML("div",["class"=>"pagelist"],
    HTML("div",["class"=>"pagelist-title"],"ランキング").
    pagecard::generate("WITH stat AS(SELECT DISTINCT * FROM stats ORDER BY point DESC LIMIT 5) SELECT users.name,users.id,articles.id,title,date,pvs,comments,evals FROM stat INNER JOIN articles ON stat.aid = articles.id INNER JOIN users ON articles.user = users.id")
);

$page->body.=
HTML("div",["class"=>"pagelist"],
    HTML("div",["class"=>"pagelist-title"],"最新の記事").
    pagecard::generate("WITH article AS(SELECT DISTINCT id,title,date,user FROM articles ORDER BY date DESC LIMIT 5) SELECT users.name,users.id,article.id,title,date,pvs,comments,evals FROM article INNER JOIN users ON users.id = article.user INNER JOIN stats ON article.id = stats.aid")
);

$page->body.=
HTML("div",["class"=>"pagelist"],
    HTML("div",["class"=>"pagelist-title"],"最も閲覧されている記事").
    pagecard::generate("WITH stat AS(SELECT DISTINCT * FROM stats ORDER BY pvs DESC LIMIT 5) SELECT users.name,users.id,articles.id,title,date,pvs,comments,evals FROM stat INNER JOIN articles ON stat.aid = articles.id INNER JOIN users ON articles.user = users.id")
);

$page->body.=
HTML("div",["class"=>"pagelist"],
    HTML("div",["class"=>"pagelist-title"],"最も評価されている記事").
    pagecard::generate("WITH stat AS(SELECT DISTINCT * FROM stats ORDER BY evals DESC LIMIT 5) SELECT users.name,users.id,articles.id,title,date,pvs,comments,evals FROM stat INNER JOIN articles ON stat.aid = articles.id INNER JOIN users ON articles.user = users.id")
);

$page->body.=
HTML("div",["class"=>"pagelist"],
    HTML("div",["class"=>"pagelist-title"],"最も議論されている記事").
    pagecard::generate("WITH stat AS(SELECT DISTINCT * FROM stats ORDER BY comments DESC LIMIT 5) SELECT users.name,users.id,articles.id,title,date,pvs,comments,evals FROM stat INNER JOIN articles ON stat.aid = articles.id INNER JOIN users ON articles.user = users.id")
);

$page->show();
