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

  var status = false;
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
      my_cards = msg.cards
      $("#start_ui").hide();
      $("#user_list").hide();
      $("#host_name").html(msg.host)
      $("#score_board_ui").show()
      updateScoreBoard()
      var html = getCardsHtml(msg.cards)
      $("#my_cards").html(html)
      if(msg.host == uid){
        showHostOption()
      }
      host = msg.host
    }else if(msg.type == "hostpick"){
      $("#keyword_show_ui").fadeIn();
      $("#keyword_show_ui").find("h2").html(msg.keyword)
      if(host != uid){
        $("#my_cards").children().click(function(){
          var id = $(this).children("img").attr("data-id")
          selected_my_card = id
          var c = confirm("确定选择这张牌吗")
          if (c == true){
            $(this).fadeOut()
            removeCard(selected_my_card)
            var html = getCardsHtml(my_cards)
            $("#my_cards").html(html)
            ws.send('{"type":"guestpick","card":'+selected_my_card+'}')
          }
        });
      }
    }else if(msg.type == "showcards"){
      var html = getCardsHtml(msg.cards)
      $("#table_cards").html(html)
      if(host != uid){
      $("#table_cards").on("click","div",function(){
        var id = $(this).children("img").attr("data-id")
        if(selected_my_card != id){
          selected_guess_card = id
          var c = confirm("确定这张牌是正确的牌吗")
          if (c == true){
            ws.send('{"type":"guess","card":'+selected_guess_card+'}')
          }
        }
      });
      }
    }else if(msg.type == "result"){
      if(msg.gameover == true){
        alert("gameover")
      }else{
        updateScoreBoard(msg.score)
        $("#round").html(msg.round)
        $("#host_name").html(msg.host)
        my_cards.push(msg.fillcard)
        var html = getCardsHtml(my_cards)
        $("#my_cards").html(html)
        $("#table_cards").html("");
        $("#keyword_show_ui").hide();
        host = msg.host
        if(msg.host == uid){
          showHostOption()
        }
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

  function updateScoreBoard(scores){
    var html = "",score;
    for(var i=0;i<connected_ulist.length;i++){
      var uid = connected_ulist[i]
      if(typeof(scores) == "undefined"){
        score = 0
      }else{
        score = scores[uid]
      }
      html += "<div class=\"col-md-2\">"+uid+"("+score+")</div>"
    }
    $("#score_board_content").html(html)
  }

  function getCardsHtml(cards){
    var html = "";
    for(var i=0;i<cards.length;i++){
      html += "<div class=\"col-md-2\"><img data-id=\""+cards[i]+"\" src=\"/images/"+cards[i]+".jpg\"/></div>";
    }
    return html
  }

  function removeCard(card){
    var newArray = new Array()
    for (var i = 0; i < my_cards.length; i++) {
      if(my_cards[i] != card){
        newArray.push(my_cards[i])
      }
    }
    my_cards = newArray
  }

  function showHostOption(){
    $("#keyword_ui").fadeIn()
    $("#keyword_btn").click(function(){
      if(selected_answer_card == 0){
        alert("请选择一个手牌")
      }else {
        var keyword = $("#keyword_input").val()
        if(keyword != ""){
          ws.send('{"type":"hostpick","card":'+selected_answer_card+',"keyword":\"'+keyword+'\"}');
          $("#keyword_ui").fadeOut()
          removeCard(selected_answer_card)
          var html = getCardsHtml(my_cards)
          $("#my_cards").html(html)
          $("#keyword_input").val("")
        }
      }
    });
    $("#my_cards").children().click(function(){
      var id = $(this).children("img").attr("data-id")
      selected_answer_card = id
      console.log(selected_answer_card);
    });
  }
});
