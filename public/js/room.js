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

});
