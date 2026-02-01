<style>
#manufacturer {
  border: none;
  border-radius: 0px;
  padding-left: 0px;
  border-bottom: 1px solid #d9d9d9; }
#status {
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
    <h3 class="text-themecolor m-b-0 m-t-0">Add Model</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>models">Model List</a></li>
      <li class="breadcrumb-item active">Add Model</li>
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
      <form class="form-material m-t-40" action="<?php echo base_url();?>models/save" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Manufacturer</label>
              <select name="manufacturer_id" id="manufacturer_id" class="form-control">
                <option value="">--Select Manufacturer--</option>
                <?php foreach($manufacturer as $u){?>
                <option value="<?php echo $u->manufacturer_id; ?>"><?php echo $u->manufacturer_name; ?></option>
                <?php } ?>
              </select>
              <label for="manufacturer_id" class="error" style="color:#FF0000;display:none;"></label>
              <?php echo form_error('manufacturer_id','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputarticle">Model Name</label>
              <input type="text" class="form-control" name="model" id="model" value="<?php echo set_value('model'); ?>" placeholder="Model">
              <label for="model" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('model','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Gear Category</label>
              <select name="gear_category_id" id="gear_category_id" class="form-control">
                <option value="">--Select Gear Category--</option>
                <?php foreach($gear_category as $v){?>
                <option value="<?php echo $v->gear_category_id; ?>"><?php echo $v->gear_category_name; ?></option>
                <?php } ?>
              </select>
              <label for="gear_category_id" class="error" style="color:#FF0000;display:none;"></label>
              <?php echo form_error('gear_category_id','<span class="text-danger">','</span>'); ?> </div>
          </div>
		      <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Gear Sub  Category</label>
              <select name="gear_sub_category_id" id="gear_sub_category_id" class="form-control">
               
              </select>
              <label for="gear_category_id" class="error" style="color:#FF0000;display:none;"></label>
              <?php echo form_error('gear_category_id','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Model Description</label>
              <textarea rows=5 class="form-control" name="model_description" id="model_description" placeholder="Model Description" value="<?php echo set_value('model_description');?>"></textarea>
              <label for="model_description" class="error" style="color:#FF0000;display:none;"></label>
              <?php echo form_error('model_description','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
         
          <div class="col-sm-6">
            <div class="form-group">
              <label for="input-file-now">Model Image</label>
              <input type = "file" id="input-file-now" name="model_image" style="display:block; margin-top:6px;" size="20"  accept='image/*' />
              <?php echo form_error('model_image','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputImage">Status</label>
              <select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>"   onChange="getstatus(this.value);">
                <option value="Y">Active</option>
                <option value="N">Inactive</option>
              </select>
              <?php echo form_error('status','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
        <div class="clr"></div>
		
		
		<div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="input-file-now">Per Day Cost USD</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_day_cost_usd" class="form-control" value=""  />
              <?php echo form_error('per_day_cost_usd','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-3">
            <div class="form-group">
              <label for="input-file-now">Per Day Cost AUD ex GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_day_cost_aud_ex_gst" class="form-control" value="" />
              <?php echo form_error('per_day_cost_usd','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="clr"></div>
          <div class="col-sm-4" >
            <div class="form-group">
              <label for="input-file-now">Replacement Value (AUD) ex GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="replacement_value_aud_ex_gst" class="form-control" value="" />
              <?php echo form_error('replacement_value_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-3" style="display:none">
            <div class="form-group">
              <label for="input-file-now">Per Weekend Cost USD</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_weekend_cost_usd" class="form-control" value="" />
              <?php echo form_error('per_weekend_cost_usd','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
		
		<div class="clr"></div>
		
		
		<div class="row">
          <div class="col-sm-3" >
            <div class="form-group">
              <label for="input-file-now">Replacement / Day Rate %</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="replacement_day_rate_percent" class="form-control" value="" />
              <?php echo form_error('replacement_day_rate_percent','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <!-- <div class="col-sm-3">
            <div class="form-group">
              <label for="input-file-now">Per Weekend Cost AUD In GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_weekend_cost_aud_in_gst" class="form-control" value="" />
              <?php echo form_error('per_weekend_cost_aud_in_gst','<span class="text-danger">','</span>'); ?> </div>
          </div> -->
		  <div class="clr"></div>
          <div class="col-sm-3" style="display:none">
            <div class="form-group">
              <label for="input-file-now">Per Week Cost USD</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_week_cost_usd" class="form-control" value="" />
              <?php echo form_error('per_week_cost_usd','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-3" style="display:none">
            <div class="form-group">
              <label for="input-file-now">Per Week Cost AUD ex GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_week_cost_aud_ex_gst" class="form-control" value="" />
              <?php echo form_error('per_week_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
		
		<div class="clr"></div>
		
		<div class="row">
          <div class="col-sm-3" style="display:none">
            <div class="form-group">
              <label for="input-file-now">Per Week Cost AUD In GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_week_cost_aud_in_gst" class="form-control" value="" />
              <?php echo form_error('per_week_cost_aud_in_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-3" style="display:none">
            <div class="form-group">
              <label for="input-file-now"> Approved</label>
              
			  
			  <select class="form-control" id="input-file-now"   name="is_approved" style="display:block; margin-top:6px;">
			  	<option value="Y">Yes</option>
				<option value="N">No</option>
			  </select>
			  
              <?php echo form_error('is_approved','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="clr"></div>
		  
		 
		  
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

<script>
$('#gear_category_id').on('change',function (){
	
	var gear_category_id = $('#gear_category_id').val();
	$.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>models/getsubcategory",
            data:{gear_category_id:gear_category_id,},
            success:function(response){
                //alert(y);
				$('#gear_sub_category_id').html(response);
            }
        });
	
})
</script>
