<?php

class passwordForm{
    static function generate(){
        return 
        HTML("img",["src"=>"/users/template/icon.png","width"=>"100px"]).
        HTML("div",["id"=>"form-title"],"UEC Coders").
        HTML("table",
            HTML("tr",
                HTML("td","Name").
                HTML("td",HTML("input",["class"=>"text","type"=>"text","name"=>"name","required"=>"true"]))
            ).
            HTML("tr",
                HTML("td","Password").
                HTML("td",HTML("input",["class"=>"text","type"=>"password","name"=>"passwd","required"=>"true"]))
            ).
            HTML("tr",
                HTML("td",["colspan"=>2],
                    HTML("input",["id"=>"submit","type"=>"submit","value"=>"Go"]))
            )
        );
    }
}