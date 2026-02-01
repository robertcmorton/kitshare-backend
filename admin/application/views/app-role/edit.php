        <style>
			#app_role_name {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
			#app_role_desc {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
			#active {
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Modify</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_role">Role List</a></li>
                            <li class="breadcrumb-item active">Modify Role List</li>
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
                                <h4 class="card-title">Modify</h4>
                                <form class="form-material m-t-40" action="<?php echo base_url();?>app_role/update" method="post" enctype="multipart/form-data">
                                    <div class="row">
									  <div class="col-sm-4">
										<div class="form-group mbr">
										  <label for="exampleInputEmail1">Role:</label>
										  <input type="hidden" name="app_role_id" value="<?php echo $result[0]->app_role_id; ?>"  />
										  <input type="text" name="app_role_name" id="app_role_name" placeholder="Role" class="form-control" value="<?php echo $result[0]->app_role_name; ?>"  />
										  <label for="app_role_name" class="error" style="color:#FF0000; display:none;"></label>
										  <?php echo form_error('app_role_name','<span class="text-danger">','</span>'); ?> </div>
									  </div>
									</div>
									<div class="clr"></div>
									<div class="row">
									  <div class="col-sm-4">
										<div class="form-group mbr">
										  <label for="exampleInputEmail1">Description:</label>
										  <textarea cols="5" name="app_role_desc" id="app_role_desc" placeholder="Description" class="form-control" value="<?php echo $result[0]->app_role_desc; ?>" ><?php echo $result[0]->app_role_desc; ?></textarea>
										  <label for="app_role_desc" class="error" style="color:#FF0000; display:none;"></label>
										  <label for="password" class="error" style="color:#FF0000; display:none;"></label>
										</div>
									  </div>
									  <div class="clr"></div>
									</div>
									<div class="row">
									  <div class="col-sm-4">
										<div class="form-group">
										  <label for="exampleInputEmail1">Active:</label>
										  <select class="form-control select2" name="active" id="active" style="width:100%;" >
											<option value="Y"<?php if ($result[0]->active == 'Y') echo "selected='selected'"; ?>>Active</option>
											<option value="N"<?php if ($result[0]->active == 'N') echo "selected='selected'"; ?>>Inactive</option>
										  </select>
										</div>
									  </div>
									  <div class="clr"></div>
									</div>
									<div class="box-footer">
										<button type="submit" class="btn btn-success">Edit</button>
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
            <!-- ============================================================== -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
	var j = jQuery.noConflict(); 
    j(function() {
      j("#myFrm").validate({
        rules: {
				app_role_name: {
					required: true,
					
				},
				app_role_desc: {
					required: true,
					
				}
           
        },
      messages: {
				app_role_name: {
					required: "Please provide a role",
					
				},
				app_role_desc: {
					required: "Please provide a description",
					
				} 
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
    
  </script>
