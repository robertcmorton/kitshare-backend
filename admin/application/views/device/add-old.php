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
    <h3 class="text-themecolor m-b-0 m-t-0">Add Gear Category</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>gear_desc">Gear Description List</a></li>
      <li class="breadcrumb-item active">Add Gear Category</li>
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
      <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>gear_desc/save" method="post" enctype="multipart/form-data">
        <input type="hidden" id="sub_cat" name="sub_cat" />
        <div class="row">
          <div class="form-group col-md-6">
            <label for="exampleInputEmail1">Model</label>
            <select name="model_id" id="model_id" class="form-control">
              <option value="">--Select Model--</option>
              <?php foreach($models as $v){?>
              <option value="<?php echo $v->model_id; ?>"><?php echo $v->model_name; ?></option>
              <?php } ?>
            </select>
            <label for="model_id" class="error" style="color:#FF0000;display:none;"></label>
            <?php echo form_error('model_id','<span class="text-danger">','</span>'); ?></div>
          <div class="form-group col-md-6">
            <label for="exampleInputarticle">Gear Name</label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo set_value('gear_name'); ?>" placeholder="Gear Name">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> </div>
          
        </div>
		<div class="row">
		<div class="form-group col-md-6">
            <label for="exampleInputarticle">Replacement Value excluding GST</label>
            <input type="text" class="form-control" name="replacement_value_aud_ex_gst" id="replacement_value_aud_ex_gst" value="<?php echo set_value('replacement_value_aud_ex_gst'); ?>" placeholder="Replacement Value excluding GST">
            <label for="replacement_value_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('replacement_value_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
		</div>
		<div class="form-group col-md-4">
            <label for="exampleInputarticle">Replacement Value including GST</label>
            <input type="text" class="form-control" name="replacement_value_aud_inc_gst" id="replacement_value_aud_inc_gst" value="<?php echo set_value('replacement_value_aud_inc_gst'); ?>" placeholder="Replacement Value including GST">
            <label for="replacement_value_aud_inc_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('replacement_value_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
        <div class="row">
          <div class="form-group col-md-6" oninput="getprice()">
            <label for="exampleInputarticle">Per Day Cost excluding GST</label>
            <input type="text" class="form-control" name="per_day_cost_aud_ex_gst" id="per_day_cost_aud_ex_gst" value="<?php echo set_value('per_day_cost_aud_ex_gst'); ?>" placeholder="Cost Per Day excluding GST">
            <label for="per_day_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_day_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
			<div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Day Cost including GST</label>
            <input type="text" class="form-control" name="per_day_cost_aud_inc_gst" id="per_day_cost_aud_inc_gst" value="<?php echo set_value('per_day_cost_aud_inc_gst'); ?>" placeholder="Cost Per Day including GST">
            <label for="per_day_cost_aud_inc_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_day_cost_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="row">
		  <div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Weekend Cost excluding GST</label>
            <input type="text" class="form-control" name="per_weekend_cost_aud_ex_gst" id="per_weekend_cost_aud_ex_gst" value="" placeholder="Cost Per Weekend excluding GST" disabled>
            <label for="per_weekend_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_weekend_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
			<div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Weekend Cost including GST</label>
            <input type="text" class="form-control" name="per_weekend_cost_aud_inc_gst" id="per_weekend_cost_aud_inc_gst" value="" placeholder="Cost Per Weekend including GST" disabled>
            <label for="per_weekend_cost_aud_inc_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_weekend_cost_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
		  </div>
		  <div class="row">
          <div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Week Cost excluding GST</label>
            <input type="text" class="form-control" name="per_week_cost_aud_ex_gst" id="per_week_cost_aud_ex_gst" value="" placeholder="Cost Per Weekend excluding GST" disabled>
            <label for="per_week_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_week_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
			<div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Week Cost including GST</label>
            <input type="text" class="form-control" name="per_week_cost_aud_inc_gst" id="per_week_cost_aud_inc_gst" value="" placeholder="Cost Per Weekend including GST" disabled>
            <label for="per_week_cost_aud_inc_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_week_cost_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="exampleInputEmail1">Owner Remark </label>
              <textarea class="textarea_editor form-control" rows="10" name="owner_remark" id="owner_remark" value="<?php echo set_value('owner_remark'); ?>"></textarea>
              <?php echo form_error('owner_remark','<span class="text-danger">','</span>'); ?>
              <label for="owner_remark" class="error" style="color:#FF0000; display:none;"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="gear_desc_1">Gear Description 1</label>
              <textarea class="textarea_editor form-control" rows="10" name="gear_desc_1" id="gear_desc_1" value="<?php echo set_value('gear_desc_1'); ?>"></textarea>
              <?php echo form_error('gear_desc_1','<span class="text-danger">','</span>'); ?>
              <label for="gear_desc_1" class="error" style="color:#FF0000; display:none;"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="exampleInputEmail1">Gear Description 2</label>
              <textarea class="textarea_editor form-control" rows="10" name="gear_desc_2" id="gear_desc_2" value="<?php echo set_value('gear_desc_2'); ?>"></textarea>
              <?php echo form_error('gear_desc_2','<span class="text-danger">','</span>'); ?>
              <label for="gear_desc_2" class="error" style="color:#FF0000; display:none;"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">App Users</label>
              <select name="app_user_id" id="app_user_id" class="form-control">
                <option value="">--Select App User--</option>
                <?php foreach($users as $u){?>
                <option value="<?php echo $u->app_user_id; ?>"><?php echo $u->app_username; ?></option>
                <?php } ?>
              </select>
              <label for="app_user_id" class="error" style="color:#FF0000;display:none;"></label>
              <?php echo form_error('app_user_id'); ?></div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputImage">Status</label>
              <select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>"   onChange="getstatus(this.value);">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
              <?php echo form_error('status','<span class="text-danger">','</span>'); ?> </div>
          </div>
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
<script src="<?php echo base_url();?>assets/plugins/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="<?php echo base_url();?>assets/plugins/html5-editor/bootstrap-wysihtml5.js"></script>
<script>
</script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
	var j = jQuery.noConflict(); 
    j(function() {
      j("#frm").validate({
        rules: {
			brand_id: {
				required: true,
				
			},
			model: {
				required: true,
				
			},
			model_image: {
				required: true,
				accept: "image/*"
				
			},
			model_desc: {
				required: true,
				
			}
           
        },
      messages: {
			device_type_id: {
				required: "Please provide a device type",
				
			},
			model: {
				required: "Please provide model",
				
			},
			model_desc: {
				required: "Please provide page description",
				
			}
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
   
   
j(document).ready(function() {

	j('#owner_remark').wysihtml5();
	j('#gear_desc_1').wysihtml5();
	j('#gear_desc_2').wysihtml5();

});

function findmodel(x){
        j.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>gear_desc/ajax",
            data:{data:x},
            success:function(response){
                //alert(x);
				j("#model").html(response);
            }
        });
}
function getprice(){
	var a = document.frm.per_day_cost_aud_ex_gst.value;
	if(a!=''){
		document.frm.per_weekend_cost_aud_ex_gst.value =a;
		document.frm.per_week_cost_aud_ex_gst.value =a*3;
	}
	else{
		document.frm.per_weekend_cost_aud_ex_gst.value ="Cost Per Weekend";
		document.frm.per_week_cost_aud_ex_gst.value ="Cost Per Week";
	}
}
  </script>
