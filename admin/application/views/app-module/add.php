        <style>
			#app_module_page {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
			#app_module_desc {
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Add Module</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_module">Module List</a></li>
                            <li class="breadcrumb-item active">Add Module</li>
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>app_module/save" method="post" enctype="multipart/form-data">
                                    <div class="row">
										<div class="col-sm-12">
										  <div class="form-group mbr">
											<label for="exampleInputEmail1">Module:</label>
											<input type="text" name="app_module_page" id="app_module_page" placeholder="Module" class="form-control" value="<?php echo set_value('app_module_page'); ?>" />
											<label for="app_module_page" class="error" style="color:#FF0000; display:none;"></label>
											<?php echo form_error('app_module_page','<span class="text-danger">','</span>'); ?> </div>
										</div>
										<div class="clr"></div>
										</div>
									<div class="clr"></div>
									<div class="row">
										<div class="col-sm-12">
										  <div class="form-group mbr">
											<label for="exampleInputEmail1">Description:</label>
											<textarea rows=5 name="app_module_desc" id="app_module_desc" placeholder="Description" class="form-control" value="" ><?php echo set_value('app_module_desc'); ?></textarea>
											<label for="app_module_desc" class="error" style="color:#FF0000; display:none;"></label>
											<?php echo form_error('app_module_desc','<span class="text-danger">','</span>'); ?> </div>
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