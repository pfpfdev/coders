<?php
require_once "./include.php";
require_once __ROOT__.'/template/form.tpl.php';
require_once __ROOT__.'/template/components/password.php';

require_unlogined_session();

$page = new formPage();

$page->title = "Signup";
$page->action = "signup.php";

$page->body = passwordForm::generate();

$name = new UserInput('POST','name');
$passwd = new UserInput('POST','passwd');

if($name->IsValid()&&$passwd->IsValid()){

    $name = $name->Sanitize(Sanitizer::CheckUserName());
    $passwd = $passwd->Sanitize(Sanitizer::CheckPassword());
    $id = hash("md5", $name);

    if($name===FALSE){
        $page->fatal("名前は[A-Za-z0-9\_]で構成してください");
    }
    if($passwd===FALSE){
        $page->fatal("パスワードは8文字以上の[A-Za-z0-9\_]で構成してください");
    }
    $output = SQL("SELECT name FROM users WHERE id = '$id'");
    if(count($output) > 0){
        $page->fatal("すでに存在するユーザーです");
    }

    $passwd_hash = hash("sha512",$name.$passwd);

    SQL("INSERT INTO users VALUES ('$name', '$passwd_hash', '$id')");

    mkdir(__USER__."/$id");
    chmod(__USER__."/$id",0777);
    system("/var/www/html/#bin/genicon $id > ".__USER__."/$id/gen.ppm");
    exec("/var/www/html/#bin/pnmtopng ".__USER__."/$id/gen.ppm 1>".__USER__."/$id/icon.png 2>&1",$output);
#    echo var_dump($output);
    chmod(__USER__."/$id/icon.png",0666);

    login($name,$id);
}

$page->show();
