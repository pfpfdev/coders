$(function() {
    $.get("/api/article.php",
        {
            "id":location.hash,
            "target":"info"
        },
        function(json){
            console.log(json)
            const data = JSON.parse(json)
            $("#title").val(data.title)
            if(data.auther!=data.whoami){
                alert("作者ではないので、保存不可能です");
            }
        }
    )
    $.get("/api/article.php",
        {
            "id":location.hash,
            "target":"markdown"
        },
        function(content){
            var editor = editormd("editor", {
                language: 'en',
                markdown:content,
                // width: "100%",
                 height: "calc(100%)-30px",
                //markdown: "xxxx",     // dynamic set Markdown text
                path : "/functions/markdown/lib/",  // Autoload modules mode, codemirror, marked... dependents libs path
                htmlDecode:"style,script,iframe,sub,sup|on*",
                emoji:true,
                taskLisk:true,
                tex:true,
                flowChart:true,
                sequenceDiagram:true,
                tocm:true
            });
        }
    )
});
$processing = false
$("#save").on('click', function(){
    if($processing === true){
        return
    }
    $processing = true
    $("#save").text("保存中")
    $("#save").css("opacity","0.5")
    if(location.hash.length!=20){
        var S="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"
        var N=19
        location.hash=Array.from(Array(N)).map(()=>S[Math.floor(Math.random()*S.length)]).join('')
    }
    $.post("/api/article.php",
        {   "id":location.hash,
            "title":$("#title").val(),
            "content":$("textarea").text()},
        function(dt){
            console.log(dt);
            if(dt==="OK"){
                $("#save").text("成功")
            }else{
                $("#save").text("失敗")
            }
            setTimeout(()=>{
                $processing = false
                $("#save").css("opacity","1")
                $("#save").text("保存")
            },10000)
        }
    )
})