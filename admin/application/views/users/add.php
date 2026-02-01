        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Basic Material inputs</h4>
                                <h6 class="card-subtitle">Just add <code>form-material</code> class to the form that's it.</h6>
                                <form class="form-material m-t-40">
<label for="exampleInputEmail1">Username</label>
									<input type="text" placeholder="Username" name="username" id="username"  class="form-control" value="<?php echo set_value('username'); ?>">
									<?php echo form_error('username','<span class="text-danger">','</span>'); ?> </div>
								</div>
								<div class="clr"></div>
							  </div>
							  <div class="row">
								<div class="col-sm-6">
								  <div class="form-group mbr">
									<label for="exampleInputEmail1">Password</label>
									<input type="password" placeholder="Password" name="password" id="password"   class="form-control" value="<?php echo set_value('password'); ?>">
									<?php echo form_error('password','<span class="text-danger">','</span>'); ?> </div>
								</div>
								<div class="col-sm-6">
								  <div class="form-group">
									<label for="exampleInputEmail1">Confirm Password</label>
									<input type="password" placeholder="Confirm Password" name="cnfpwd" id="cnfpwd"  class="form-control" value="<?php echo set_value('cnfpwd'); ?>">
									<?php echo form_error('cnfpwd','<span class="text-danger">','</span>'); ?> </div>
								</div>
								<div class="clr"></div>
							  </div>
							  <div class="form-group">
								<label for="exampleInputEmail1">Add Privilege:</label>
								<br />
								<div class="col-sm-3">
								  <div class="form-group">
									<label >Add</label>
									<br />
									<input type="radio" name="pre_add" id="optionsRadios4" value="Y" >
									Yes
									<input type="radio" name="pre_add" id="optionsRadios5" value="N">
									No </div>
								</div>
								<div class="col-sm-3">
								  <div class="form-group">
									<label for="exampleInputEmail1">Edit</label>
									<br />
									<input type="radio" name="pre_edit" id="optionsRadios4" value="Y" >
									Yes
									<input type="radio" name="pre_edit" id="optionsRadios5" value="N">
									No </div>
								</div>
								<div class="col-sm-3">
								  <div class="form-group">
									<label for="exampleInputEmail1">View</label>
									<br />
									<input type="radio" name="pre_view" id="optionsRadios4" value="Y" >
									Yes
									<input type="radio" name="pre_view" id="optionsRadios5" value="N">
									No </div>
								</div>
								<div class="col-sm-3">
								  <div class="form-group">
									<label for="exampleInputEmail1">Delete</label>
									<br />
									<input type="radio" name="pre_delete" id="optionsRadios4" value="Y" >
									Yes
									<input type="radio" name="pre_delete" id="optionsRadios5" value="N">
									No </div>
								</div>
							  </div>
							  <div class="box-footer">
								<button type="submit" class="btn btn-primary">Submit</button>
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