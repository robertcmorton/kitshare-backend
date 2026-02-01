




<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
  <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Add FAQ Category</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Faq">FAQ Cateegory List</a></li>
      <li class="breadcrumb-item active">Add FAQ Category</li>
    </ol>
  </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Add</h4>
      <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>Faq/save" method="post" enctype="multipart/form-data">
        
        <div class="row">
              
          <div class="form-group col-md-6">
            <label for="exampleInputarticle">Category Name </label>
            <input type="text" class="form-control" name="category_name" id="category_name"  value="<?php echo set_value('category_name'); ?>"  required placeholder="Category Name ">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('category_name','<span class="text-danger">','</span>'); ?>
          </div>
			    <div class=" col-md-6">
                  
              <label for="exampleInputarticle">Title</label>
              <input type="text" class="form-control" name="title" id="title" onchange= "GetPeralinks()" value="<?php echo set_value('title'); ?>" required  placeholder="Title">
              <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('title','<span class="text-danger">','</span>'); ?>
          </div>  

          
              <input type="hidden" class="form-control" name="permalink" id="permalink" value="<?php echo set_value('permalink'); ?>" required  placeholder="Title">
              
            
          
        </div>
        <div class="row">
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Image</label>
              <input type="file" class="form-control" name="image" id="image"  placeholder="Image">
                 
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('image','<span class="text-danger">','</span>'); ?> 
            </div>
			<div class="form-group col-md-6">
              <label for="exampleInputarticle">Order List </label>
              <input type="text" class="form-control" name="order_by" id="order_by"  placeholder="Order List">
                 
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('image','<span class="text-danger">','</span>'); ?> 
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Status</label>
              <select type="file" class="form-control" name="status" id="status"  placeholder="Status">
                <option value="Y">Active</option>
                <option value="N">Inactive</option>
               </select>  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('status','<span class="text-danger">','</span>'); ?> 
            </div>
          </div>  

        <div class="clr"></div>
        <div class="box-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
        <div class="clr"></div>
      </form>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- End Page Content -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->



<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
function GetPeralinks(){
  
  
//   var title =  $('#title').val();
   var regExpr = /[^a-zA-Z0-9-. ]/g;
                        var userText = document.getElementById('title').value;
                        var  str =  userText.replace(regExpr, "") ; 
                         str =  str.trim();
  
   var peralinks =  str.split(' ').join('-') ;
  // // alert(peralinks);
   $('#permalink').val(peralinks);
}
  var j = jQuery.noConflict(); 
    j(function() {
      j("#frm").validate({
        rules: {
      category_name: {
        required: true,
        
      },
      title: {
        required: true,
        
      },
      
           
        },
      messages: {
      category_name: {
        required: "Please provide a device type",
        
      },
      tilte: {
        required: "Please provide model",
        
      },
     
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
</script>   