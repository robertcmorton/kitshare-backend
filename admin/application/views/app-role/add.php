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
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_role">Role List</a></li>
                            <li class="breadcrumb-item active">Add Role</li>
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>app_role/save" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Role</label>
                                        <input type="text" class="form-control form-control-line" name="app_role_name" id="app_role_name" placeholder="Role" value="<?php echo set_value('app_role_name'); ?>"> <label for="app_role_name" class="error" style="color:#FF0000; display:none;"></label><?php echo form_error('app_role_name','<span class="text-danger">','</span>'); ?></div>
									<div class="clr"></div>
									<div class="form-group m-t-20">
                                        <label>Description</label>
                                        <textarea cols="5" rows="5" name="app_role_desc" id="app_role_desc" placeholder="Description" class="form-control" value="" ><?php echo set_value('app_role_desc'); ?></textarea><label for="app_role_desc" class="error" style="color:#FF0000; display:none;"></label><?php echo form_error('app_role_desc','<span class="text-danger">','</span>'); ?>
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