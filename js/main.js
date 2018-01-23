function Details_Modal_Button(id)
{
  var data={"id" : id};
  jQuery.ajax({
    url:'/Ecommerce/includes/Details_Modal.php',
    method: "post",
    data:data,
    success:function(data){
      jQuery('body').append(data);
      jQuery('#details-modal').modal('toggle');
    },
    error:function(){
      alert('Something Wrong !');
    }
  });
}
