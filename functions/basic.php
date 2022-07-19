<?php

define('__USER__', '/var/www/html/users');
define('__SQL__','/var/www/html/#bin/sqlite');
define('__DB__','/var/www/html/#db/ueccoder.db');
define('__WEBROOT__','/');
define('__WEBUSER__',__WEBROOT__.'users/');
define('SQL',__SQL__.' '.__DB__.' ');

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function debug($html){
    return "<pre><code>$html</code></pre>";
}

function HTML($tag,$arg1="",$arg2=""){
    if(is_array($arg1)){
        $str = "<$tag";
        foreach ($arg1 as $key => $value) {
            $str=$str." $key=$value";
        }
        return "$str>$arg2</$tag>";
    }
    return "<$tag>$arg1</$tag>";
}

function br(){return '<br>';}

function reduce($array,$func,$init){
    $accum=$init;
    foreach($array as $key=>$value){
        $accum=$func($accum,$value,$key);
    }
    return $accum;
}

function parseSQLResult($result){
    $table = array();
    foreach($result as $r)array_push($table,explode("|",$r));
    return $table;
}

function SQL($Query){
    exec(SQL."\"$Query\"",$output);
    return parseSQLResult($output);
}

class UserInput{
    private $var;
    private $method;
    private $key;
    function __construct($method,$key){
        $this->var = ['POST'=>$_POST,'GET'=>$_GET];
        $this->method = $method;
        $this->key = $key;
    }
    function IsValid(){
        return isset($this->var[$this->method])&&isset($this->var[$this->method][$this->key]);
    }
    function Sanitize($func){
        return $func($this->var[$this->method][$this->key]);
    }
}

class Sanitizer{
    public static function CheckMD5(){
        return function($text){
            if(preg_match('/^[a-f0-9]{32}$/i', $text)){
                return $text;
            }
            return FALSE;
        };
    }
    public static function CheckUserName(){
        return function($text){
            if(preg_match('/^(?!.*\W).*$/', $text)){
                return $text;
            }
            return FALSE;
        };
    }
    public static function CheckPassword(){
        return function($text){
            if(preg_match('/^(?!.*\W).{8,}$/', $text)){
                return $text;
            }
            return FALSE;
        };
    }
    public static function CheckArticleID(){
        return function($text){
            if(preg_match('/^#[0-9A-z]{19}$/', $text)){
                return $text;
            }
            return FALSE;
        };
    }
    public static function RawText(){
        return function($text){
            return $text;
        };
    }
    public static function CheckDatetime(){
        return function($text){
            if(preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $text)){
                return $text;
            }
            return FALSE;
        };
    }
}