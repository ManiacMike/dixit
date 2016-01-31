$(function(){
  var joinPostData = {}
  $("#create_room_btn").click(function(){
    $.post("/roomApi/create",{name:$("#room_name").val(),password:$("#room_password").val()},function(data){
      if(data['status'] == "success"){
        $('#createModal').modal('hide');
        location.href = '/room/'+data['data'];
      }else{
        alert("create fail,"+data['message']);
      }
    },"json");
  });
  $( ".card-block a.btn" ).on( "click", function() {
    joinPostData = {
      "rid":$(this).attr("data-id")
    }
    $("#room_join_password").val("");
    if($(this).attr("data-pwd") == "1"){
      $('#roomPwdModal').modal('show');
      var rname = $(this).parents("div.card-block").find("span.room_name").html();
      $('#room_join_name').html(rname);
    }else{
      joinPost()
    }
  });

  $("#join_room_btn").click(function(){
    joinPostData["password"] = $("#room_join_password").val();
    joinPost()
  });
  function joinPost(){
    $.post("/roomApi/join",joinPostData,function(data){
      if(data['status'] == "success"){
        $('#roomPwdModal').modal('hide');
        location.href = '/room/'+data['data'];
      }else{
        alert("join fail,"+data['message']);
      }
    },"json");
  }
});
