$.get("/api/article.php",
    {
        "id":location.hash,
        "target":"info"
    },
    function(json){
        json = json.replace("/","\\/")
        console.log(json)
        const info=JSON.parse(json)
        if(info.ERROR===true)return;
        $("#title").text(info.title)
        $("#date").text(info.date.substring(0,10))
        $("#auther").text(info.auther)
        $("#auther").attr("href","/users/index.php?id="+md5(info.auther))
        $("#good").text(info.thumbs)
        $("#pv").text(info.pvs)
        $("#comments").text(info.comments)
        if(info.auther===info.whoami){
            $("#go-edit").show()
            $("#go-edit").attr("href","/editor.php?id="+location.hash)
        }
    }
)
$.get("/api/article.php",
    {
        "id":location.hash,
        "target":"markdown"
    },
    function(content){
        var editor = editormd.markdownToHTML("view", {
            width: "100%",
            markdown: content,
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
$.get("/api/evaluation.php",
    {
        "id":location.hash,
    },
    function(ret){
        updateSurveyButton(ret)
    }
) 
$.get("/api/bbs.php",
    {
        "id":location.hash,
    },
    function(json){
        json = json.replace(/\//g,"\\/").replace(/\n|\r\n/g,"<br>")
        console.log(json)
        const data = JSON.parse(json)
        const html = Array.from(data.comment,buildBBSItem)
        $("#bbs-contents").html($(".bbs-list-template").html().replace("TEMPLATE",html.join("")))
    }
) 
$("#survey-submit").on("click",()=>{
    $.post("/api/evaluation.php",
        {
            "id":location.hash,
        },
        function(ret){
            console.log(ret);
            updateSurveyButton(ret)
            updateThumbCount(ret)
        }
    ) 
})
$("#bbs-submit").on("click",()=>{
    $.post("/api/bbs.php",
        {
            "id":location.hash,
            "content":$("#bbs-form textarea").val()
        },
        function(ret){
            console.log(ret)
            location.reload()
        }
    ) 
})
window.addEventListener("hashchange", ()=>{$('html,body').scrollTop(0);;location.reload()}, false);
function updateSurveyButton(strBool){
    if(strBool==="true"){
        $(".fa-thumbs-up").css("color","#02a695");
    }else if(strBool==="false"){
        $(".fa-thumbs-up").css("color","#888");
    }
}
function updateThumbCount(strBool){
    if(strBool==="true"){
        $("#good").text(parseInt($("#good").text())+1)
    }else if(strBool==="false"){
        $("#good").text(parseInt($("#good").text())-1)
    }
}
function buildBBSItem(comment){
    return $(".bbs-item-template").html().
        replace("USER",comment.user).
        replace("DATE",comment.date).
        replace("CONTENT",comment.content)
}