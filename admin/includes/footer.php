<!--Footer-->
<div id="ContainerDiv" style="position:fixed;">
    <div id="InnerContainer">
        <div id="TheBelowDiv">
          <footer class="text-center" style="margin-top:135px;font-size:15px;background-color:green;color:white;background-attachment:fixed;" id="footer"> Copyright 2018-19 Developed By-Shubham Sunny</footer>
        </div>
    </div>
</div>

</body>

<script type="text/javascript">

// setTimeout(function () {
//   if(window.location.hash != '#r') {
//       window.location.hash = 'r';
//       window.location.reload(1);
//   }
// }, 1500);


function update_sizes()
{
  var sizeString='';
  for(var i=1;i<=12;i++)
  {
    if(jQuery('#size'+i).val()!=''){
      sizeString+=jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+'.';
    }
  }
  jQuery('#sizes').val(sizeString);
}



function get_child_option(selected) {
  if(typeof selected ==='undefined')
  {
    selected='';
  }
  var parent_ID=jQuery('#parent').val();
  jQuery.ajax({
    url:'/Ecommerce/admin/parser/child_categories.php',
    type:'POST',
    data: {parent_ID : parent_ID, selected:selected},
    success:function(data)
    {
      jQuery('#child').html(data);
    },
    error:function(){alert('Something Wrong')},
  });
}
  jQuery('select[name="parent"]').change(function(){
    get_child_option();
  });
</script>
</html>
