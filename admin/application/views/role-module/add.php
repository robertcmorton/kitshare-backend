        <style>
			#mod_id {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Add Role Module</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>role_module">Role Module List</a></li>
                            <li class="breadcrumb-item active">Add Role Module</li>
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>role_module/save" method="post" enctype="multipart/form-data">
								    <div class="row">
										<div class="col-sm-6">
										  <div class="form-group mbr">
											<label for="exampleInputEmail1">Module:</label>
											
											<select name="mod_id" class="form-control" id="mod_id">
												<option value="">--Select Module--</option>
												<?php foreach($modules as $key=>$val){ ?>
												
														<option value="<?php echo $val->mod_id ; ?>"><?php echo $val->app_module_page ; ?></option>
												<?php } ?>
											</select>
											
											<label for="mod_id" class="error" style="color:#FF0000; display:none;"></label>
											<?php echo form_error('mod_id','<span class="text-danger">','</span>'); ?> </div>
										</div>
										<div class="clr"></div>
									<div class="clr"></div>
										<div class="col-sm-6">
										  <div class="form-group mbr">
											<label for="exampleInputEmail1">Role:</label>
											
											<select name="app_role_id" class="form-control" id="app_role_id" >
												<option value="">--Select Role--</option>
												<?php foreach($roles as $key=>$val){ ?>
												
														<option value="<?php echo $val->app_role_id ; ?>"><?php echo $val->app_role_name ; ?></option>
												<?php } ?>
											</select>
											
											<label for="app_role_id" class="error" style="color:#FF0000; display:none;"></label>
											<?php echo form_error('app_role_id','<span class="text-danger">','</span>'); ?> </div>
										</div>
										
										<div class="clr"></div>
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
            <!-- ============================================================== -->