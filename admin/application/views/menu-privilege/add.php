        <style>
			#app_role_id {
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Add</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>menu_privilege">Role Privilege List</a></li>
                            <li class="breadcrumb-item active">Add Role Privilege</li>
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>menu_privilege/save" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
										<label>Role</label>
										<span style="color:#FF0000;">*</span>
										<select class="form-control" name="app_role_id" id="app_role_id">
										  <option value="">--Select role--</option>
										  <?php if(!empty($role)) { foreach($role as $key=>$value){ ?>
										  <option value="<?php echo $value->app_role_id; ?>"><?php echo $value->app_role_name; ?></option>
										  <?php } } ?>
										</select>
										<label for="app_role_id" class="error" style="color:red; display:none;"></label>
										<?php echo form_error('app_role_id','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="clr"></div>
									<div class="form-group">
										<label>Privilege Type</label>
										<span style="color:#FF0000;">*</span>
										<div class="col-md-12 row">
											<div class="col-md-2"><input type="checkbox" class="filled-in chk-col-blue" name="privilege_type[]" id="check_1" value="1" />
											Add</div>
											<div class="col-md-2"><input type="checkbox" class="filled-in chk-col-blue" name="privilege_type[]" id="check_2" value="2" />
											Edit</div>
											<div class="col-md-2"><input type="checkbox" class="filled-in chk-col-blue" name="privilege_type[]" id="check_3" value="3" />
											View</div>
											<div class="col-md-2"><input type="checkbox" class="filled-in chk-col-blue" name="privilege_type[]" id="check_4" value="4" />
											Delete</div>
										</div>
										<label for="privilege_type[]" class="error" style="color:red; display:none;"></label>
										<?php echo form_error('privilege_type[]','<span class="text-danger">','</span>'); ?> </div>
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