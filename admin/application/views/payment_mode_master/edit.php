        <style>
			#owner_type_name {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
			#is_active {
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Modify Payment Mode</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>payment_mode_master">Payment Mode List</a></li>
                            <li class="breadcrumb-item active">Modify Payment Mode</li>
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>payment_mode_master/update" method="post" enctype="multipart/form-data"><input type="hidden" value="<?php echo $result[0]->payment_mode_master_id; ?>" name="payment_mode_master_id" id="payment_mode_master_id" />
								    <div class="row">
									<div class="col-sm-4">
									  <div class="form-group mbr">
										<label for="exampleInputEmail1">Payment Mode Name</label>
										<input type="text" name="payment_mode_name" id="payment_mode_name" placeholder="Payment Mode Name" class="form-control" value="<?php echo $result[0]->payment_mode_name; ?>" />
										<label for="payment_mode_name" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('payment_mode_name','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="col-sm-4">
									  <div class="form-group mbr">
										<label for="exampleInputEmail1">Payment Mode Abbr</label>
										<input type="text" name="payment_mode_abbr" id="payment_mode_abbr" placeholder="Payment Mode Abbr" class="form-control" value="<?php echo $result[0]->payment_mode_abbr; ?>" />
										<label for="payment_mode_abbr" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('payment_mode_abbr','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
								  </div>
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