<?php //print_r($gear_categories);?>        <style>
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Modify Gear Percentage Rate</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>gear_categories">Gear Percentage Rate Details </a></li>
                            <li class="breadcrumb-item active">Modify Percentage Rate </li>
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>gear_percentage/update" method="post" enctype="multipart/form-data">
								    <input type="hidden" name="id" id="id" value="<?php echo $result->id; ?>">
								    <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputEmail1">Category</label>
											<select name="category_id" id="category_id" class="form-control" onChange="findnext(this.value,1,<?php echo $cnt;?>)">
											  <option value="">--Select Category--</option>
											  <?php foreach($gear_categories as $v){?>
											  <option value="<?php echo $v->gear_category_id; ?>" <?php if($v->gear_category_id==$result->category_id) echo "selected='selected'";?>><?php echo $v->gear_category_name; ?></option>
											  <?php } ?>
											</select>
											<div id="test1"></div>
											<label for="gear_category_id" class="error" style="color:#FF0000;display:none;"></label>
											<?php // echo form_error('gear_category_id','<span class="text-danger">','</span>'); ?> </div>
										</div>
									 </div>
									 <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputEmail1">Sub Category</label>
											<select name="gear_category_id_1" id="gear_category_id_1" class="form-control" onChange="findnext(this.value,1,<?php echo $cnt;?>)">
											  <option value="">--Select SubCategory--</option>
											  <?php foreach($gear_categories as $v){?>
											  <option value="<?php echo $v->gear_category_id; ?>" <?php if($v->gear_category_id==$result->sub_category_id) echo "selected='selected'";?>><?php echo $v->gear_category_name; ?></option>
											  <?php } ?>
											</select>
											<div id="test1"></div>
											<label for="gear_category_id" class="error" style="color:#FF0000;display:none;"></label>
											<?php // echo form_error('gear_category_id','<span class="text-danger">','</span>'); ?> </div>
										</div>
									 </div>
									 <div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for="exampleInputarticle">Brand  Name </label>
												<input type="text" class="form-control" name="brand" id="brand" value="<?php echo $result->brand; ?>" placeholder="Brand">
												<label for="gear_category_name" class="error" style="color:#FF0000; display:none;"></label>
											</div>
										</div>
									 </div>
									 <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputarticle">Lowest Limit</label>
											<input type="text" class="form-control" name="lowest_limit" id="lowest_limit" value="<?php echo $result->lowest_limit; ?>" placeholder="Lowest Limit">
											
											 </div>
									  </div>
									  </div>
									  
									  <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputarticle">Upper Limit</label>
											<input type="text" class="form-control" name="upper_limit" id="upper_limit" value="<?php echo $result->upper_limit; ?>" placeholder="Upper Limit">
											<label for="gear_category_name" class="error" style="color:#FF0000; display:none;"></label>
											<?php // echo form_error('gear_category_name','<span class="text-danger">','</span>'); ?> </div>
									  </div>
									  </div>
									  <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputarticle">Average </label>
											<input type="text" class="form-control" name="average" id="average" value="<?php echo $result->average; ?>" placeholder="Average ">
											</div>
									  </div>
									  </div>

									  <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputImage">Status</label>
											<select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php //echo set_value('is_active'); ?>"   onChange="getstatus(this.value);">
											  <option value="YES" <?php if ($result->status =='YES') {echo "selected";}?> >Yes</option>
											  <option value="NO" <?php if ($result->status =='NO') {echo "selected";}?>>No</option>
											</select>
											<?php //echo form_error('is_active','<span class="text-danger">','</span>'); ?> </div>
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
function initial(x){
	$.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>gear_categories/ajax_edit_init",
            data:{data:x},
            success:function(response){
                //alert(y);
				
				$("#test1").append(response);
            }
        });	
}
window.onload=initial(<?php //echo $result[0]->gear_category_id; ?>);
function findnext(x,y,z){
	$.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>gear_categories/ajax_edit",
            data:{data:x,count:y},
            success:function(response){
                //alert(y);
				var c=y+1;
				//alert(a);
				while(c<z){$("#category_"+c).remove();c++};
				$("#test1").append(response);
				$("#sub_cat").val(y);
            }
        });
}
</script>