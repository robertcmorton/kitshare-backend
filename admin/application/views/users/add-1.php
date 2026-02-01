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
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control form-control-line"  placeholder="Username" name="username" id="username"   value="<?php echo set_value('username'); ?>"><?php echo form_error('username','<span class="text-danger">','</span>'); ?> </div>
									<div class="clr"></div>
									<div class="col-sm-6">	
										<div class="form-group">
											<label>Password</label>
											<input type="password" placeholder="Password" name="password" id="password" class="form-control" value="<?php echo set_value('password'); ?>">
											<?php echo form_error('password','<span class="text-danger">','</span>'); ?> </div>
										</div>
									</div>
									<div class="col-sm-6">	
										<div class="form-group">
											<label>Confirm Password</label>
											<input type="password" name="cnfpwd" id="cnfpwd" class="form-control" value="<?php echo set_value('cnfpwd'); ?>"> 
										<?php echo form_error('cnfpwd','<span class="text-danger">','</span>'); ?></div>
									</div>
									<div class="clr"></div>
									<div class="col-sm-3">
                                        <div class="demo-radio-button">Add Privilege</div>
                                        <div class="switch">
                                        <label for="radio_36">Y</label>
										<input name="group5" type="radio" id="radio_36" class="with-gap radio-col-light-blue" />
										<label for="radio_35">N</label>
										<input name="group6" type="radio" id="radio_35" class="with-gap radio-col-light-blue" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Placeholder</label>
                                        <input type="text" class="form-control" placeholder="placeholder"> </div>
                                    <div class="form-group">
                                        <label>Text area</label>
                                        <textarea class="form-control" rows="5"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Input Select</label>
                                        <select class="form-control">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>File upload</label>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div> <span class="input-group-addon btn btn-default btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                                            <input type="hidden">
                                            <input type="file" name="..."> </span> <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Helping text</label>
                                        <input type="text" class="form-control form-control-line"> <span class="help-block text-muted"><small>A block of help text that breaks onto a new line and may extend beyond one line.</small></span> </div>
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