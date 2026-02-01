        <style>
			#gear_category_id {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
			#gear_category_name {
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Modify Gear Category</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>gear_categories">Gear Categories Details List</a></li>
                            <li class="breadcrumb-item active">Modify Gear Category</li>
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>gear_categories/update" method="post" enctype="multipart/form-data">
								    <input type="hidden" id="sub_cat" name="sub_cat" /><input type="hidden" name="gear_category_id" id="gear_category_id" value="<?php echo $result[0]->gear_category_id; ?>">
									<input type="hidden" name="url" id="url" value="<?php echo $refer; ?>">
								    <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputEmail1">Category</label>
											<select name="gear_category_id_1" id="gear_category_id_1" class="form-control" onChange="getSubcategories(this.value)">
											  <option value="">--Select Category--</option>
											  <?php foreach($gear_categories as $v){?>
											  <option value="<?php echo $v->gear_category_id; ?>" <?php if($v->gear_category_id==$result[0]->gear_sub_category_id) echo "selected='selected'";?>><?php echo $v->gear_category_name; ?></option>
											  <?php } ?>
											</select>
											<label for="gear_category_id" class="error" style="color:#FF0000;display:none;"></label>
											<?php echo form_error('gear_category_id','<span class="text-danger">','</span>'); ?> </div>
										</div>
									  </div>
									  
									  <div class="row">
										<div class="col-sm-6">
										  <div class="form-group" id="divSubCat">
											<label for="exampleInputarticle">Category Name </label>
											<input type="text" class="form-control" name="gear_category_name" id="gear_category_name" value="<?php echo $result[0]->gear_category_name; ?>" placeholder="Specification">
											<label for="gear_category_name" class="error" style="color:#FF0000; display:none;"></label>
										  </div>
										</div>
									  </div>
									  <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputarticle">Security Deposit</label>
											<select class="form-control select2" name="security_deposit" id="security_deposit" style="width:100%;"  >
											  <option value="Y" <?php if($result[0]->security_deposit =="Y") echo "selected='selected'";?>>Yes</option>
											  <option value="N" <?php if($result[0]->security_deposit =="N") echo "selected='selected'";?>>No</option>
											</select>
											</div>
										</div>
									  </div>
									  <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputarticle">Average Value</label>
											<input type="text" class="form-control" name="average_value" id="average_value" value="<?php echo $result[0]->average_value; ?>" placeholder="Average Value" >
											</div>
										</div>
									  </div>
									  <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputImage">Status</label>
											<select class="form-control select2" name="is_active" id="is_active" style="width:100%;" value="<?php echo set_value('is_active'); ?>"   onChange="getstatus(this.value);">
											  <option value="Y" <?php if($result[0]->is_active =="Y") echo "selected='selected'";?> >Yes</option>
											  <option value="N" <?php if($result[0]->is_active =="N") echo "selected='selected'";?> >No</option>
											</select>
											<?php echo form_error('is_active','<span class="text-danger">','</span>'); ?> </div>
										</div>
									  </div>
									<div class="clr"></div>
									<div class="box-footer">
										<button type="submit" class="btn btn-success">Edit</button>
									</div>
									<div class="clr"></div>
                                </form>
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
<script type="text/javascript">
function getSubcategories(param){
	
	$.ajax({
		type:"post",
		url:"<?php echo base_url(); ?>gear_categories/get_subcategory",
		data:{'gear_category_id':param},
		success:function(response){
			
			$("#divSubCat").html(response);
			
		}
      });
}
</script>