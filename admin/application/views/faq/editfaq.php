<script src="https://cdn.ckeditor.com/4.5.1/standard/ckeditor.js"></script>




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
    <h3 class="text-themecolor m-b-0 m-t-0">Edit FAQ</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Faq/Faqlist">FAQ  List</a></li>
      <li class="breadcrumb-item active">Edit FAQ </li>
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
      <h4 class="card-title">Edit</h4>
      <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>Faq/updateFaq" method="post" enctype="multipart/form-data">
         <input type="hidden" class="form-control" name="faq_id" id="faq_id" value="<?php echo $result->faq_id; ?>">
       
        <div class="row">
              
          <div class="form-group col-md-6">
            <label for="exampleInputarticle">Category  </label>
            <select type="text" class="form-control" name="category_id" id="category_id" value="<?php echo set_value('category_id'); ?>"  required placeholder="Category">
            <option>--Select Category</option>
            <?php if(!empty($faq_category)){
                      foreach ($faq_category as $value) {
            ?>
            <option value="<?php echo $value->id; ?>" <?php if($value->id == $result->category_id){echo "selected";} ?> ><?php echo $value->category_name ; ?></option>
            <?php           }

            } ?>
            </select>  
            <label for="category_id" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('category_id','<span class="text-danger">','</span>'); ?>
          </div>
          <div class=" col-md-6">
                  
              <label for="exampleInputarticle">Title</label>
              <input type="text" class="form-control" name="title" id="title" onchange= "GetPeralinks()" value="<?php echo $result->title; ?>" required  placeholder="Title">
              <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('title','<span class="text-danger">','</span>'); ?>
          </div>  
         <input type="hidden" class="form-control" name="permalink" id="permalink" value="<?php echo $result->permalink?>"   placeholder="Permalink">
          <div class=" col-md-12">
              <label for="exampleInputarticle">FAQ Question</label>
              <input type="text" class="form-control" name="faq_question" id="faq_question" value="<?php echo $result->faq_question; ?>" required  placeholder="Faq Question">
              <label for="faq_question" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('faq_question','<span class="text-danger">','</span>'); ?>
          </div>     
          
        </div>
      </br>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
              <label for="gear_desc_1">FAQ Answer</label>
			   <textarea name="gear_desc_1" id="editor1" rows="10" cols="80"><?php echo $result->description;?></textarea>
              <!--<textarea class="textarea_editor form-control" rows="10" name="gear_desc_1" id="gear_desc_1" value="<?php echo set_value('gear_desc_1'); ?>"><?php echo $result->description;?></textarea>
              --><?php echo form_error('gear_desc_1','<span class="text-danger">','</span>'); ?>
              <label for="gear_desc_1" class="error" style="color:#FF0000; display:none;"></label>
                </div>
              </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Writer  Name</label>
              <input type="text" class="form-control" name="writer_name" id="writer_name" value="<?php echo $result->writer_name; ?>" placeholder="Writer  Name">
                 
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('writer_name','<span class="text-danger">','</span>'); ?> 
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Image</label>
              <input type="file" class="form-control" name="image" id="image"  placeholder="Image">
                 
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('image','<span class="text-danger">','</span>'); ?> 
            </div>
				<div class="form-group col-md-6">
              <label for="exampleInputarticle">Order List </label>
              <input type="text" class="form-control" name="order_by" id="order_by" value="<?php echo  $result->order_by ;?>"  placeholder="Order List">
                 
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('image','<span class="text-danger">','</span>'); ?> 
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Status</label>
              <select type="file" class="form-control" name="status" id="status"  placeholder="Status">
                <option value="Y" <?php if('Y'== $result->status){echo "selected";} ?> >Active</option>
                <option value="N"  <?php if('N' == $result->status){echo "selected";} ?> >Inactive</option>
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


<script >
// instance, using default configuration.
CKEDITOR.editorConfig = function (config) {
    config.language = 'es';
    config.uiColor = '#F7B42C';
    config.height = 300;
    config.toolbarCanCollapse = true;

};
CKEDITOR.replace('editor1');
</script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
  function GetPeralinks(){			 

      var regExpr = /[^a-zA-Z0-9-. ]/g;
      var userText = document.getElementById('title').value;
      var  str =  userText.replace(regExpr, "") ; 
       str =  str.trim();
      var peralinks =  str.split(' ').join('-') ;
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