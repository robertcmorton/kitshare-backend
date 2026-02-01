<style>
#username {
  border: none;
  border-radius: 0px;
  padding-left: 0px;
  border-bottom: 1px solid #d9d9d9; }
#password {
  border: none;
  border-radius: 0px;
  padding-left: 0px;
  border-bottom: 1px solid #d9d9d9; }
#cnfpwd {
  border: none;
  border-radius: 0px;
  padding-left: 0px;
  border-bottom: 1px solid #d9d9d9; }
#email {
  border: none;
  border-radius: 0px;
  padding-left: 0px;
  border-bottom: 1px solid #d9d9d9; }
</style>
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
    <h3 class="text-themecolor m-b-0 m-t-0">Edit Page</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>pages">Pages</a></li>
      <li class="breadcrumb-item active">Edit</li>
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
      <form class="form-material m-t-40" action="<?php echo base_url();?>pages/update_page" method="post" id="myFrm" enctype="multipart/form-data">
        <div class="form-group col-md-10">
          <label for="exampleInputarticle">Page Name</label>
		  <input type="hidden" name="id" id="id"   class="form-control" value="<?php echo $page[0]->cms_page_id; ?>">
          <input type="text"  class="form-control"  name="page" id="page" value="<?php  echo ucfirst($page[0]->page); ?>" placeholder="Page Name">
          <?php echo form_error('page','<span class="text-danger">','</span>'); ?> </div>
        <div class="form-group col-md-10">
          <label for="exampleInputarticle">Page Code</label>
          <input type="text"  class="form-control"  name="page_code" id="page_code" value="<?php  echo ucfirst($page[0]->page_code); ?>" placeholder="Page Code">
          <?php echo form_error('page_code','<span class="text-danger">','</span>'); ?> </div>
        <div class="form-group col-md-10">
          <label for="exampleInputarticle">Page Content</label>
          <textarea type="text" class="form-control" id="content" name="content" rows="10" cols="80" placeholder="Enter Page Content Here"> <?php  echo ucfirst($page[0]->content); ?></textarea>
          <?php echo form_error('content','<span class="text-danger">','</span>'); ?> </div>
        <div class="box-footer">
          <button type="submit" class="btn btn-primary">Edit</button>
        </div>
      </form>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- End Page Content -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
<script>
  	function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
  </script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>

var j = jQuery.noConflict(); 
j(function() {
j("#block_reason_div").hide();
  j("#myFrm").validate({
	rules: {
		app_username: {
			required: true,
			remote: {
			url: "<?php echo base_url()?>app_users/username_check",
			type: "post"
			}
		 },
		app_user_first_name: {
			required: true,
		},
		app_user_last_name: {
			required: true,
		},
		owner_type_id: {
			required: true,
		},
		primary_mobile_number: {
			required: true,
		},
		primary_email_address: {
			required: true,
			email: true,
		},
		additional_email_address_1:{
			email: true,
		},
		additional_email_address_2:{
			email: true,
		},
		additional_email_address_3:{
			email: true,
		},
		app_password:{
			email: true,
		},
		app_password: {
			required: true,
			minlength: 6
		},
		conf_password: {
			required: true,
			minlength: 6,
			equalTo: "#app_password",
		},
		user_unique_id_number:{
			required: true,
			remote: {
			url: "<?php echo base_url()?>app_users/unique_id_check",
			type: "post"
			}
		},
		
	},

  messages: {
		app_username: {
			required: "Please provide Username",
			remote: "Username already in use!"
			
		},
		app_user_first_name: {
			required: "Please provide first name",
		},
		app_user_last_name: {
			required: "Please provide last name",
		},
		owner_type_id: {
			required: "Please provide owner type",
		},
		primary_mobile_number: {
			required: "Please provide mobile number",
		},
		primary_email_address: {
			required: "Please provide email address",
			
		},
		app_password: {
			required: "Please provide a password",
			minlength: "Minimum input 6 characters"
		},
		conf_password: {
			required: "Please provide confirm password",
			minlength: "Minimum input 6 characters",
			equalTo: "Should be same as password"
		},
		user_unique_id_number: {
			required: "Please provide UserId",
			remote: "UserId already in use!"
			
		},
		
		
	},
	submitHandler: function(form) {
		form.submit();
	}
});
});


function Block()
{
  
  	var block_status = j('#is_blocked').val();
	if(block_status=='Y')
	{
	    j('#block_reason').val('');
		j('#block_reason_div').show();
	}else{
		j('#block_reason_div').hide();
	}
 }

</script>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url();?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
var js = jQuery.noConflict();
  js(function () {
    CKEDITOR.replace('content');
    js(".textarea").wysihtml5();
  });
</script>
