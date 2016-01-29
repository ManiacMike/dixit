$(function(){
  $("#create_room_btn").click(function(){
    $.post("/roomApi/create",{name:$("#room_name").val(),password:$("#room_password").val()},function(data){
      if(data['status'] == "success"){
        $('#createModal').modal('hide');
        location.href = '/room/'+data['data'];
      }else{
        alert("create_fail");
      }
    },"json");
  });
});
