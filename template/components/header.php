<?php

class Header{
    public static function generate(){
        $inner = HeaderIcon::generate().HeaderTitle::generate().HeaderUserControl::generate();
        return HTML("div",["class"=>"header-wrapper"],$inner);
    }
}

class HeaderIcon{
    public static function generate(){
        return '<a href="/index.php" class="icon"><img src="/template/res/icon.png" width="50px"></a>';
    }
}

class HeaderTitle{
    public static function generate(){
        return '<div class="title">UEC Coders</div>';
    }
}

class HeaderUserControl{
    public static function generate(){
        @session_start();
        if(!isset($_SESSION['id'])){
            return HTML("table",["class"=>"user"],
                HTML("tr",
                    HTML("td",HTML("a",["href"=>"/users/login.php"],HTML("div",["class"=>"button"],'<i class="fas fa-sign-in-alt"></i>'))).
                    HTML("td",HTML("a",["href"=>"/users/signup.php"],HTML("div",["class"=>"button"],'<i class="fas fa-user-plus"></i>')))
                )
            );
        }
        return HTML("table",["class"=>"user"],
            HTML("tr",
                HTML("td",HTML("a",["href"=>"/editor.php"],HTML("div",["class"=>"button"],'<i class="fas fa-edit"></i>'))).
                HTML("td",HTML("a",["href"=>"/users/mypage.php"],HTML("div",["class"=>"button"],HTML("img",["src"=>"/users/{$_SESSION['id']}/icon.png"])))).
                HTML("td",HTML("a",["href"=>"/users/logout.php"],HTML("div",["class"=>"button"],'<i class="fas fa-sign-out-alt"></i>')))
            )
        );
    }
}