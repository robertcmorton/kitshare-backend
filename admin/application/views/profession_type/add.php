        <style>
			#owner_type_name {
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Add Profession  Type</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>profession_type">Owner Profession List</a></li>
                            <li class="breadcrumb-item active">Add Profession Type</li>
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>profession_type/save" method="post" enctype="multipart/form-data">
								    <div class="col-sm-6">
									  <div class="form-group mbr">
										<label for="exampleInputEmail1">Profession Type :</label>
										<input type="text" name="profession_name" id="profession_name" placeholder="Profession Type" class="form-control" value="<?php echo set_value('profession_name'); ?>" />
										<label for="owner_type_name" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('profession_name','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="col-sm-6">
									  <div class="form-group mbr">
										<label for="exampleInputEmail1">Profession Description :</label>
										<textarea name="profession_desc" id="profession_desc" placeholder="Description"  rows="10" cols="80" class="form-control" required><?php echo set_value('profession_desc'); ?></textarea>
										<label for="owner_type_name" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('profession_desc','<span class="text-danger">','</span>'); ?> </div>
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