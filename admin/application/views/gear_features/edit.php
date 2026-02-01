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
    <h3 class="text-themecolor m-b-0 m-t-0">Modify Gear Feature</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>gear_features">Gear Features List</a></li>
      <li class="breadcrumb-item active">Modify Gear Feature</li>
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
      <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>gear_features/update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="user_gear_feature_id" id="user_gear_feature_id" value="<?php echo $result[0]->user_gear_feature_id; ?>"/>
        <input type="hidden" name="feature_display_seq_id_old" id="feature_display_seq_id_old" value="<?php echo $result[0]->feature_display_seq_id; ?>" />
        <div class="row">
          <div class="form-group col-md-4">
            <label for="exampleInputEmail1">Gear Name</label>
            <select name="user_gear_desc_id" id="user_gear_desc_id" class="form-control">
              <option value="">--Select Gear--</option>
              <?php foreach($gear_desc as $v){?>
              <option value="<?php echo $v->user_gear_desc_id; ?>" <?php if($result[0]->user_gear_desc_id==$v->user_gear_desc_id) echo "selected='selected'"; ?>><?php echo $v->gear_name; ?></option>
              <?php } ?>
            </select>
            <label for="user_gear_desc_id" class="error" style="color:#FF0000;display:none;"></label>
            <?php echo form_error('user_gear_desc_id','<span class="text-danger">','</span>'); ?></div>
          <div class="form-group col-md-4">
            <label for="exampleInputarticle">Feature Name</label>
            <input type="text" class="form-control" name="feature_name" id="feature_name" value="<?php echo $result[0]->feature_name; ?>" placeholder="Feature Name">
            <label for="feature_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('feature_name','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-4">
            <label for="exampleInputEmail1">Feature Display Sequence</label>
            <select name="feature_display_seq_id_new" id="feature_display_seq_id" class="form-control">
              <option value="">--Select Display Sequence--</option>
              <?php foreach($feature_display_seq_id as $u){?>
              <option value="<?php echo $u->feature_display_seq_id; ?>" <?php if($result[0]->feature_display_seq_id==$u->feature_display_seq_id) echo "selected='selected'"; ?>><?php echo $u->feature_display_seq_id; ?></option>
              <?php } ?>
            </select>
            <label for="feature_display_seq_id" class="error" style="color:#FF0000;display:none;"></label>
            <?php echo form_error('feature_display_seq_id','<span class="text-danger">','</span>'); ?></div>
        </div>
        <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Feature Description</label>
                <textarea class="textarea_editor form-control" rows="10" name="feature_description" id="feature_description" value="<?php echo $result[0]->feature_description; ?>"><?php echo $result[0]->feature_description; ?></textarea>
                <?php echo form_error('feature_description','<span class="text-danger">','</span>'); ?>
                <label for="feature_description" class="error" style="color:#FF0000; display:none;"></label>
              </div>
            </div>
          <div class="col-6">
            <div class="form-group">
              <label for="exampleInputImage">Status</label>
              <select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>"   onChange="getstatus(this.value);">
                <option value="Active" <?php if($result[0]->is_active=='Y') echo "selected='selected'"; ?>>Active</option>
                <option value="Inactive" <?php if($result[0]->is_active=='N') echo "selected='selected'"; ?>>Inactive</option>
              </select>
              <?php echo form_error('status','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="box-footer">
            <button type="submit" class="btn btn-success">Edit</button>
          </div>
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

	j('#feature_description').wysihtml5();

});

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
