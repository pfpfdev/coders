<?php
require_once "./include.php";
require_once __ROOT__.'/template/simple.tpl.php';
require_once __ROOT__.'/template/userpage.tpl.php';

$id = new UserInput('GET','id');
if($id->IsValid()){

	$page = new userPage();

	$uid = $id->Sanitize(Sanitizer::CheckMD5());
	if($uid===FALSE){
		$page->fatal("Invalid data");
	}
	$output = SQL("SELECT name FROM users WHERE id = '$uid'");
	if(count($output) == 0){
		$page->fatal("Invalid data");
	}
	$name=$output[0][0];

	$page->title = "Users";
	$page->name = $name;
	$page->id = $uid;

	$page->show();
}else{

	$page = new simplePage();

	$page->title = "Users";
	$page->addStylesheet("usercard.css");

	$users=SQL("SELECT * FROM users");

	$page->body=HTML("ul",["class"=>"cards"],
		reduce($users,function($accum,$val,$i){
			return $accum.HTML("li",["class"=>"cards_item"],
				HTML("div",["class"=>"card"],
					HTML("img",["src"=>"{$val[2]}/icon.png"]).
					HTML("a",["href"=>"index.php?id=$val[2]"],"{$val[0]}")
			)
			);
		},"")
	);

	$page->show();
}

