<?php


class pagecard{
    //uname,uid,aid,title,date,pvs,comments,evalsで出力されるSQL
    public static function generate($SQLQuery){
        exec(SQL."\"$SQLQuery\"",$output);
        $pagedata = parseSQLResult($output);
        if(count($pagedata)==0){
            return HTML("div",["class"=>"pagelist-empty"],"まだこの一覧の対象はありません");
        }
        return reduce($pagedata,function($accum,$val,$i){
            $uname = $val[0];
            $uid = $val[1];
            $aid = $val[2];
            $title = $val[3];
            $date = $val[4];
            $pvs = $val[5];
            $comments = $val[6];
            $evals = $val[7];
            if(isset($_SESSION) and $uname = $_SESSION["name"]){
        	    return $accum.HTML("div",["class"=>"pagecard"],
   	                HTML("table",HTML("tr",
   	                    HTML("td",
   	                        HTML("img",["src"=>"/users/$uid/icon.png"])
   	                    ).
   	                    HTML("td",["class"=>"card-content"],
   	                    	HTML("div",
   	                    		HTML("a",["class"=>"card-title","href"=>"/viewer.php$aid"],
   	                  				base64_decode($title)	                        	
   	                    	   	).
   	                    	   	HTML("a",["class"=>"card-title","href"=>"/editor.php$aid"],
   	                 				' <i class="far fa-edit"></i>'	                        	
   	                    	   	)
   	                    	).
   	                        HTML("a",["class"=>"card-user","href"=>"/users/index.php?id=$uid"],$uname).
   	                        substr($date,0,10).
   	                        HTML("span",["class"=>"card-stat"],
   	                            HTML("span",["class"=>"stat-item"],'<i class="far fa-eye"></i>:'.$pvs).
   	                            HTML("span",["class"=>"stat-item"],'<i class="far fa-thumbs-up"></i>:'.$evals).
   	                            HTML("span",["class"=>"stat-item"],'<i class="far fa-comments"></i>:'.$comments)
   	                        )
   	                    )
   	                ))
   	            );
            }
            return $accum.HTML("div",["class"=>"pagecard"],
                HTML("table",HTML("tr",
                    HTML("td",
                        HTML("img",["src"=>"/users/$uid/icon.png"])
                    ).
                    HTML("td",["class"=>"card-content"],
                        HTML("a",["class"=>"card-title","href"=>"/viewer.php$aid"],HTML("div",base64_decode($title))).
                        HTML("a",["class"=>"card-user","href"=>"/users/index.php?id=$uid"],$uname).
                        substr($date,0,10).
                        HTML("span",["class"=>"card-stat"],
                            HTML("span",["class"=>"stat-item"],'<i class="far fa-eye"></i>:'.$pvs).
                            HTML("span",["class"=>"stat-item"],'<i class="far fa-thumbs-up"></i>:'.$evals).
                            HTML("span",["class"=>"stat-item"],'<i class="far fa-comments"></i>:'.$comments)
                        )
                    )
                ))
            );
        },"");
    }
}