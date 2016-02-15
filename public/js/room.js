$(function(){
  $("#quit-btn").click(function(){
      var q = confirm("确认退出么？");
      if(q == true){
        $.post("/roomApi/quit",{"rid":$(this).attr("data-id")},function(data){
          if(data['status'] == "success"){
            location.href = '/';
          }else{
            alert("leave fail,"+data['message']);
          }
        },"json");
      }
  });
  $("#my_cards").on("click","div",function(){
    var id = $(this).children("img").attr("data-id")
    selected_my_card = id
    console.log(selected_my_card);
  });
  $("#keyword_btn").click(function(){
    if(selected_my_card == 0){
      alert("请选择一个手牌")
    }else {
      var keyword = $("#keyword_input").val()
      if(keyword != ""){
        ws.send('{"type":"hostpick","card":'+selected_my_card+',"keyword":\"'+keyword+'\"}');
        $("#keyword_ui").fadeOut()
      }
    }
  });
  var status = false;
  var connected_ulist;
  ws.onopen = function() {
    status = true
  };
  ws.onclose = function() {
    status = true
  };
  ws.onerror = function(e) {
    status = false
  }
  ws.onmessage = function(event) {
    var msg = JSON.parse(event.data);
    if(msg.type == "user_connect"){
      updateUserList(msg.user_list)
    }else if(msg.type == "user_disconnect"){
      updateUserList(msg.user_list)
    }else if(msg.type == "start"){
      html = "";
      for(var i=0;i<msg.cards.length;i++){
        html += "<div class=\"col-md-2 my_card\"><img data-id=\""+msg.cards[i]+"\" src=\"/images/"+msg.cards[i]+".jpg\"/></div>";
      }
      $("#start_ui").hide();
      $("#my_cards").html(html)
      if(msg.host == uid){
        $("#keyword_ui").fadeIn()
      }
    }
  };
  $("#start_btn").click(function(){
    var c = confirm("确定开始游戏吗");
    if (c == true){
      ws.send('{"type":"start"}');
    }
  });
  function updateUserList(ulist){
    ulist = ulist.split(",")
    connected_ulist = ulist
    var htmlStr = "<div class=\"row\">"
    for (var i = 0; i < ulist.length; i++) {
      htmlStr+="<div class=\"col-md-2\">"+ulist[i]+"</div>"
    }
    htmlStr+="</div>"
    $("#user_list").html(htmlStr)
  }
});
