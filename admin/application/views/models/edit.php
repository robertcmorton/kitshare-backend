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
    <h3 class="text-themecolor m-b-0 m-t-0">Modify Model</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>models">Model List</a></li>
      <li class="breadcrumb-item active">Modify Model</li>
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
      <form class="form-material m-t-40" action="<?php echo base_url();?>models/update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="model_id" id="model_id" value="<?php echo $result[0]->model_id; ?>"/>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Manufacturer</label>
              <select name="manufacturer_id" id="manufacturer_id" class="form-control">
                <option value="">--Select Manufacturer--</option>
                <?php foreach($manufacturer as $u){?>
                <option value="<?php echo $u->manufacturer_id; ?>" <?php if($result[0]->manufacturer_id==$u->manufacturer_id) echo "selected=selected" ; ?>><?php echo $u->manufacturer_name; ?></option>
                <?php } ?>
              </select>
              <label for="manufacturer_id" class="error" style="color:#FF0000;display:none;"></label>
              <?php echo form_error('manufacturer_id','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputarticle">Model Name</label>
              <input type="text" class="form-control" name="model" id="model_name" value="<?php echo $result[0]->model_name; ?>" placeholder="Model Name">
              <label for="model_name" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('model_name','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Gear Category</label>
              <select name="gear_category_id" id="gear_category_id" class="form-control">
                <option value="">--Select Gear Category--</option>
                <?php foreach($gear_category as $v){?>
                <option value="<?php echo $v->gear_category_id; ?>" <?php if($result[0]->gear_category_id==$v->gear_category_id) echo "selected=selected" ; ?>><?php echo $v->gear_category_name; ?></option>
                <?php } ?>
              </select>
              <label for="gear_category_id" class="error" style="color:#FF0000;display:none;"></label>
              <?php echo form_error('gear_category_id','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Gear Sub  Category</label>
              <select name="gear_sub_category_id" id="gear_sub_category_id" class="form-control">
                <option value="">--Select Gear Category--</option>
                <?php foreach($gear_sub_category as $v){?>
                <option value="<?php echo $v->gear_category_id; ?>" <?php if($result[0]->gear_sub_category_id==$v->gear_category_id) echo "selected=selected" ; ?>><?php echo $v->gear_category_name; ?></option>
                <?php } ?>
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
              <textarea rows=5 class="form-control" name="model_description" id="model_description" placeholder="Model Description"> <?php echo stripslashes($result[0]->model_description);?></textarea>
              <label for="model_description" class="error" style="color:#FF0000;display:none;"></label>
              <?php echo form_error('model_description','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="col-sm-6">
            
			<?php if($result[0]->model_image==''){ ?>
				<img src="<?php echo base_url(); ?>assets/images/noimage.png" width="80px" height="80px" />
			<?php } else { ?>
			  <img src="<?php echo ROOT_PATH;?>site_upload/model_images/<?php echo $result[0]->model_image;?>" width="80px" height="80px" />
			<?php } ?>
				  
			  
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="input-file-now">Model Image</label>
              <input type = "file" id="input-file-now" style="display:block; margin-top:6px;" name="model_image" size = "20"  accept='image/*' />
              <?php echo form_error('model_image','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  
		  <div class="clr"></div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputImage">Status</label>
              <select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>"   onChange="getstatus(this.value);">
                <option value="Y" <?php if($result[0]->is_active=='Y') echo "selected=selected" ; ?>>Active</option>
                <option value="N" <?php if($result[0]->is_active=='N') echo "selected=selected" ; ?>>Inactive</option>
              </select>
              <?php echo form_error('status','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
		<div class="clr"></div>
		
		<div class="row">
          <!--<div class="col-sm-3">
            <div class="form-group">
              <label for="input-file-now">Per Day Cost USD</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_day_cost_usd" class="form-control" value="<?php //echo $result[0]->per_day_cost_usd; ?>" size = "20"  accept='image/*' />
              <?php //echo form_error('per_day_cost_usd','<span class="text-danger">','</span>'); ?> </div>
          </div>-->
		  <div class="col-sm-3">
            <div class="form-group">
              <label for="input-file-now">Per Day Cost  GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_day_cost_usd" class="form-control" value="<?php echo $result[0]->per_day_cost_usd; ?>" size = "20"  accept='image/*' />
              <?php echo form_error('per_day_cost_usd','<span class="text-danger">','</span>'); ?> </div>
          </div>
		      <div class="clr"></div>
          <div class="col-sm-3" >
            <div class="form-group">
              <label for="input-file-now">Per Day Cost AUD Ex GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_day_cost_aud_ex_gst" class="form-control" value="<?php echo $result[0]->per_day_cost_aud_ex_gst; ?>" size = "20"  accept='image/*' />
              <?php echo form_error('per_day_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label for="input-file-now">Replacement Value(AUD) ex GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="replacement_value_aud_ex_gst" class="form-control" value="<?php echo $result[0]->replacement_value_aud_ex_gst; ?>" size = "20"  accept='image/*' />
              <?php echo form_error('replacement_value_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <!--<div class="col-sm-3">
            <div class="form-group">
              <label for="input-file-now">Per Weekend Cost USD</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_weekend_cost_usd" class="form-control" value="<?php //echo $result[0]->per_weekend_cost_usd; ?>" size = "20"  accept='image/*' />
              <?php //echo form_error('per_weekend_cost_usd','<span class="text-danger">','</span>'); ?> </div>
          </div>-->
		  <div class="col-sm-3" style="display:none" >
            <div class="form-group">
              <label for="input-file-now">Per Weekend Cost AUD In GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_weekend_cost_aud_in_gst" class="form-control" value="<?php echo $result[0]->per_weekend_cost_aud_inc_gst; ?>" size = "20"  accept='image/*' />
              <?php echo form_error('per_weekend_cost_aud_in_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-3" style="display:none">
            <div class="form-group">
              <label for="input-file-now">Per Week Cost AUD Ex GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_week_cost_aud_ex_gst" class="form-control" value="<?php echo $result[0]->per_week_cost_aud_ex_gst; ?>" size = "20"  accept='image/*' />
              <?php echo form_error('per_week_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
		
		<div class="clr"></div>
		
		
		<div class="row">
          <!--<div class="col-sm-3">
            <div class="form-group">
              <label for="input-file-now">Per Weekend Cost Ex USD</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_weekend_cost_aud_ex_gst" class="form-control" value="<?php //echo $result[0]->per_weekend_cost_aud_ex_gst; ?>" size = "20"  accept='image/*' />
              <?php //echo form_error('per_weekend_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>-->
		  
		  <div class="clr"></div>
          <!--<div class="col-sm-3">
            <div class="form-group">
              <label for="input-file-now">Per Week Cost USD</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_week_cost_usd" class="form-control" value="<?php //echo $result[0]->per_week_cost_usd; ?>" size = "20"  accept='image/*' />
              <?php //echo form_error('per_week_cost_usd','<span class="text-danger">','</span>'); ?> </div>
          </div>-->
		  
		      <div class="col-sm-3" style="display:none">
            <div class="form-group" >
              <label for="input-file-now">Per Week Cost AUD In GST</label>
              <input type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="per_week_cost_aud_in_gst" class="form-control" value="<?php echo $result[0]->per_week_cost_aud_inc_gst; ?>" size = "20"  accept='image/*' />
              <?php echo form_error('per_week_cost_aud_in_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  
		      <div class="col-sm-3" style="display:none">
            <div class="form-group">
              <label for="input-file-now">Replacement Value(AUD) inc GST</label>
              <input type = "text" id="replacement_value_aud_inc_gst" style="display:block; margin-top:6px;" name="replacement_value_aud_inc_gst" class="form-control" value="<?php echo $result[0]->replacement_value_aud_inc_gst; ?>" size = "20"  accept='image/*' />
              <?php echo form_error('replacement_value_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-3">
            <div class="form-group">
              <label for="input-file-now">Replacement/Day rate %</label>
              <input type = "text" id="replacement_day_rate_percent" style="display:block; margin-top:6px;" name="replacement_day_rate_percent" class="form-control" value="<?php echo $result[0]->replacement_day_rate_percent; ?>" size = "20"  accept='image/*' />
              <?php echo form_error('replacement_day_rate_percent','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
		
		<div class="clr"></div>
		
		<div class="row">
          
		 <!-- <div class="col-sm-3">
            <div class="form-group">
              <label for="input-file-now">Replacement Value(USD)</label>
              <input type = "text" id="replacement_value_usd" style="display:block; margin-top:6px;" name="replacement_value_usd" class="form-control" value="<?php //echo $result[0]->replacement_value_usd; ?>" size = "20"  accept='image/*' />
              <?php //echo form_error('replacement_value_usd','<span class="text-danger">','</span>'); ?> </div>
          </div>-->
		  
		  <div class="col-sm-3" style="display:none">
            <div class="form-group">
              <label for="input-file-now"> Approved</label>
			  <select type="hidden"  class="form-control" id="input-file-now"   name="is_approved" style="display:block; margin-top:6px;">
			  	<option value="Y" <?php if($result[0]->is_approved=='Y') echo "selected=selected"; ?>>Yes</option>
				<option value="N" <?php if($result[0]->is_approved=='N') echo "selected=selected"; ?>>No</option>
			  </select>
			  
              <?php echo form_error('is_approved','<span class="text-danger">','</span>'); ?> </div>
          </div>
		 </div>
		 <div class="row">
		  
		  
		  <?php if($result[0]->is_approved=='Y'){ ?>	  
          <div class="col-sm-3" style="display:none">
            <div class="form-group">
			
              <label for="input-file-now">Approved On</label>
              <input readonly="" type = "text" id="input-file-now" style="display:block; margin-top:6px;" name="approved_on" class="form-control" value="<?php echo date('M d, Y', strtotime($result[0]->approved_on)); ?>" >
              <?php echo form_error('approved_on','<span class="text-danger">','</span>'); ?> 
			  </div>
          </div>
		   <?php }?>
		 </div>
		
		
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

