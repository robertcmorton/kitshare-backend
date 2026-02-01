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
								    <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputEmail1">Category</label>
											<select name="gear_category_id_1" id="gear_category_id_1" class="form-control" onChange="findnext(this.value,1,<?php echo $cnt;?>)">
											  <option value="">--Select Category--</option>
											  <?php foreach($gear_categories as $v){?>
											  <option value="<?php echo $v->gear_category_id; ?>" <?php if($v->gear_category_id==$result[0]->gear_category_id) echo "selected='selected'";?>><?php echo $v->gear_category_name; ?></option>
											  <?php } ?>
											</select>
											<div id="test1"></div>
											<label for="gear_category_id" class="error" style="color:#FF0000;display:none;"></label>
											<?php echo form_error('gear_category_id','<span class="text-danger">','</span>'); ?> </div>
										</div>
									  </div>
									  
									  <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputarticle">Category Name </label>
											<input type="text" class="form-control" name="gear_category_name" id="gear_category_name" value="<?php echo set_value('gear_category_name'); ?>" placeholder="Specification">
											<label for="gear_category_name" class="error" style="color:#FF0000; display:none;"></label>
											<?php echo form_error('gear_category_name','<span class="text-danger">','</span>'); ?> </div>
										</div>
									  </div>
									  <div class="row">
										<div class="col-sm-6">
										  <div class="form-group">
											<label for="exampleInputImage">Status</label>
											<select class="form-control select2" name="is_active" id="is_active" style="width:100%;" value="<?php echo set_value('is_active'); ?>"   onChange="getstatus(this.value);">
											  <option value="Y">Yes</option>
											  <option value="N">No</option>
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
window.onload=initial(<?php echo $result[0]->gear_category_id; ?>);
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