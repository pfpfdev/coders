<?php

class Footer{
    public static function generate(){
        $inner = FooterSitemap::generate().FooterTitle::generate();
        return Footer::div($inner);
    }
    private static function div($html){
        return "<div class=footer-wrapper>$html</div>";
    }
}
class FooterTitle{
    public static function generate(){
        return '<div class=title><img src="/template/res/icon.png" width=50><br><b style="font-size: 170%;">UEC Coders</b><br><i>The Platform for Engineers.</i><br><br>All rights are reserved© Takato Ikezawa, 2020</div>';
    }
}
class FooterSitemap{
    public static function generate(){
        return 
        HTML("div",["class"=>"sitemap"],
            HTML("b","SiteMap").br().br().
            HTML("table",
                HTML("tr",
                    HTML("td",HTML("a",["href"=>"/index.php"],"トップページ")).
                    HTML("td",HTML("a",["href"=>"/all.php"],"全記事")).
                    HTML("td",HTML("a",["href"=>"/users/index.php"],"全ユーザー"))
                ).
                HTML("tr",
                HTML("td",HTML("a",["href"=>"/users/mypage.php"],"マイページ")).
                    HTML("td",HTML("a",["href"=>"/users/index.php?id=36ebe4f29f907b5e5eb543490d7fe3e4"],"作者")).
                    HTML("td",HTML("a",["href"=>"/viewer.php#AkksuCxojop8KliIY1j"],"説明書"))
                )
            )
        );
    }   
}