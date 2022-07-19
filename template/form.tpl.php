<?php
require_once 'simple.tpl.php';

class formPage extends simplePage{
    public $method = "POST";
    public $action = "";
    protected $stylesheet = ["basic.css","form.css"];
    function fatal($msg){
        $this->body.=HTML("div",["class"=>"error-msg"],$msg);
        $this->show();
        exit();
    }
    function show(){
        $embed = function($v){return $v;};
echo <<<__HTML_DOCUMENT__
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title> $this->title </title>
        {$embed($this->includeStylesheet())}
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-V6CBQPPQX1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-V6CBQPPQX1');
        </script>
    </head>
    <body>
        <form method="$this->method" action="$this->action">$this->body</div>
    </body>
    {$embed($this->includeJavascript())}
</html>
__HTML_DOCUMENT__;
    }
}