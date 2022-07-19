<?php
require_once "./include.php";
require_once __ROOT__.'/template/form.tpl.php';
require_once __ROOT__.'/template/components/password.php';

require_unlogined_session();

$page = new formPage();

$page->title = "Signup";
$page->action = "login.php";

$page->body = passwordForm::generate();

// <!-- 本当ならHTTPS出ないので、ダイジェスト認証が必要
// またCSRFを防ぐためにトークン管理が必要
// 認証に関係する部分をここだけで管理すれば後々変更することも容易なので余力があったら実装する -->

$name = new UserInput('POST','name');
$passwd = new UserInput('POST','passwd');

if($name->IsValid()&&$passwd->IsValid()){

    $name = $name->Sanitize(Sanitizer::CheckUserName());
    $passwd = $passwd->Sanitize(Sanitizer::CheckPassword());

    if(($name!==FALSE)&&($passwd!==FALSE)){

        $passwd_hash = hash("sha512",$name.$passwd);

        $id = hash("md5", $name);
    
        $output = SQL("SELECT name FROM users WHERE name = '$name' AND passwd = '$passwd_hash'");

        if(count($output) > 0){
            login($name,$id);
        }

    }

    $page->fatal("ログインに失敗しました");
}

$page->show();