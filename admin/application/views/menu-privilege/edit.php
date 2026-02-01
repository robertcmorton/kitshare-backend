        <style>
			#app_role_name {
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
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>menu_privilege">Role Privilege List</a></li>
                            <li class="breadcrumb-item active">Modify Role Privilege List</li>
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
                                <form class="form-material m-t-40" action="<?php $id = $this->uri->segment(3);echo base_url();?>menu_privilege/update" method="post" enctype="multipart/form-data">
                                    <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputEmail1">Role</label>
											<span style="color:#FF0000;">*</span>
											<?php // echo $result[0]->app_role_name; ?>
											<input type="hidden" name="app_role_id" id="app_role_id" value="<?php echo $id; ?>" />
											<select class="form-control" id="app_role_name"  disabled>
											  <option value="">--Select role--</option>
											  <?php if(!empty($role)) { foreach($role as $key=>$value){ ?>
											  <option value="<?php echo $value->app_role_id; ?>" <?php if($id==$value->app_role_id) echo "selected='selected'" ; ?>><?php echo $value->app_role_name; ?></option>
											  <?php } } ?>
											</select>
											<label for="app_role_id" class="error" style="color:red; display:none;"></label>
											<?php echo form_error('app_role_id','<span class="text-danger">','</span>'); ?> </div>
										</div>
										
										<div class="clr"></div>
									  </div>
										<div class="col-sm-12">
										  <div class="form-group mbr">
											<label for="exampleInputEmail1">Privilege Type</label>
											<span style="color:#FF0000;">*</span>
											<div class="col-md-12 row">
											<?php
											
											$i=1;foreach($privilege as $key=>$val){?>
											  
												<div class="col-md-2"><input type="checkbox" class="filled-in chk-col-blue" name="privilege_type[]" id="<?php echo "check_".$i; ?>" value="<?php echo $val->app_priv_id; ?>" <?php foreach($result as $priv){ if($val->app_priv_id==$priv->app_priv_id) echo "checked='checked'" ; }?> > <?php echo ucfirst($val->privilege_type); ?></div>
											  
											<?php $i++;}?>
											  </div>
											<label for="privilege_type[]" class="error" style="color:red; display:none;"></label>
											<?php echo form_error('privilege_type[]','<span class="text-danger">','</span>'); ?> </div>
										</div>
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
