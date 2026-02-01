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
                        <h3 class="text-themecolor m-b-0 m-t-0">Modify Bank</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>banks">Bank List</a></li>
                            <li class="breadcrumb-item active">Modify Bank</li>
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>banks/update" method="post" enctype="multipart/form-data">
									<input type="hidden" name="bank_id" id="bank_id" value="<?php echo $result[0]->bank_id; ?>"/>
								    <div class="row">
									  <div class="col-sm-6">
										<div class="form-group">
										<label for="exampleInputarticle">Bank Name</label>
										<input type="text" class="form-control" name="bank_name" id="bank_name" value="<?php echo $result[0]->bank_name; ?>" placeholder="Bank Name">
										<label for="bank_name" class="error" style="color:#FF0000; display:none;"></label> 
										<?php echo form_error('bank_name','<span class="text-danger">','</span>'); ?> 
									 </div>
									 </div>
									 <div class="col-sm-6">
										<div class="form-group">
										<label for="exampleInputarticle">Bank Head Office</label>
										<input type="text" class="form-control" name="bank_head_office" id="bank_head_office" value="<?php echo $result[0]->bank_head_office; ?>" placeholder="Bank Head Office">
										<label for="bank_head_office" class="error" style="color:#FF0000; display:none;"></label> 
										<?php echo form_error('bank_head_office','<span class="text-danger">','</span>'); ?> 
									 </div>
									 </div>
									</div>
									<div class="clr"></div>
									<div class="row">
									<div class="col-sm-6">
									  <div class="form-group">
										<label for="input-file-now">Bank Logo</label>
										<input type = "file" id="input-file-now" name = "bank_logo" size = "20"  accept='image/*' />
										<?php echo form_error('bank_logo','<span class="text-danger">','</span>'); ?> 
										</div>
										</div>
								  <div class="col-sm-6">
								  <div class="form-group">
									<label for="exampleInputImage">Status</label>
									<select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>"   onChange="getstatus(this.value);">
									  <option value="Y" <?php if($result[0]->is_active=='Y') echo "selected=selected" ; ?>>Active</option>
									  <option value="N" <?php if($result[0]->is_active=='N') echo "selected=selected" ; ?>>Inactive</option>
									</select>
									<?php echo form_error('status','<span class="text-danger">','</span>'); ?> 
									</div>
									</div>
									</div>
									<div class="clr"></div>
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