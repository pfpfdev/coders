<?php

require_once 'components/header.php';
require_once 'components/footer.php';

/**
 * ヘッダーとフッターのみを構築する最も単純なテンプレート
 */
class simplePage{
    public $title = "TITLE";
    public $body ="BODY";
    public $defaultCSS = "/css/";
    public $defaultJS = "/js/";
    protected $stylesheet = ["basic.css","icon.css","body.css","header.css","footer.css"];
    protected $javascript = [];
    function addStylesheet($sheet){
        array_push($this->stylesheet,$sheet);
    }
    protected function includeStylesheet(){
        return reduce($this->stylesheet,function($accum,$val,$i){
            if(strpos($val,"/")===FALSE){
                return $accum."<link rel=\"stylesheet\" type=\"text/css\" href=\"$this->defaultCSS{$val}\">";
            }
            return $accum."<link rel=\"stylesheet\" type=\"text/css\" href=\"{$val}\">";
        },"");
    }
    function addJavascript($script){
        array_push($this->javascript,$script);
    }
    protected function includeJavascript(){
        return reduce($this->javascript,function($accum,$val,$i){
            if(strpos($val,"/")===FALSE){
                return $accum.HTML("script",["src"=>"$this->defaultJS{$val}"]);
            }
            return $accum.HTML("script",["src"=>"{$val}"]);
        },"");
    }
    protected function includeHeader(){
        return Header::generate();
    }
    protected function includeFooter(){
        return Footer::generate();
    }
    function fatal($msg){
        $this->body = HTML("div",["class"=>"error-msg"],$msg);
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
        {$embed($this->includeHeader())}
        <div id="contents">$this->body</div>
        {$embed($this->includeFooter())}
    </body>
    {$embed($this->includeJavascript())}
</html>
__HTML_DOCUMENT__;
    }
}