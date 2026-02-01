<style>
			#ks_state_id {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
 			#suburb_name {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
			#suburb_postcode {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
			#latitude {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
			#longitude {
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
    <h3 class="text-themecolor m-b-0 m-t-0">Modify Suburb</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>suburb">Suburb List</a></li>
      <li class="breadcrumb-item active">Modify Suburb</li>
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
      <form class="form-material m-t-40" id="myFrm" action="<?php echo base_url();?>suburb/update" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">State</label>
              <span style="color:#FF0000;">*</span>
              <div id="country_tt">
                <input type="hidden" name="ks_suburb_id" value="<?php echo $result[0]->ks_suburb_id; ?>"  />
                <select class="form-control valid" id="ks_state_id" name="ks_state_id">
                  <option value="">--Select State--</option>
                  <?php foreach($states as $key=>$val){ ?>
                  <option value="<?php echo $val->ks_state_id; ?>" <?php if($val->ks_state_id==$result[0]->ks_state_id) echo "selected='selected'" ; ?>><?php echo $val->ks_state_name; ?></option>
                  <?php } ?>
                </select>
              </div>
              <label for="ks_state_id" class="error" style="color:red; display:none; font-size:12px"></label>
              <?php echo form_error('state_id','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Suburb</label>
              <span style="color:#FF0000;">*</span>
              <input type="text" placeholder="Suburb" name="suburb_name" id="suburb_name"  class="form-control" value="<?php echo $result[0]->suburb_name; ?>" required>
              <label for="suburb_name" class="error" style="color:red; display:none; font-size:12px"></label>
              <?php echo form_error('suburb_name','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Suburb Post Code</label>
              <span style="color:#FF0000;">*</span>
              <input type="text" placeholder="Suburb Post Code" name="suburb_postcode" id="suburb_postcode"  class="form-control" value="<?php echo $result[0]->suburb_postcode; ?>" required>
              <label for="suburb_postcode" class="error" style="color:red; display:none; font-size:12px"></label>
              <?php echo form_error('suburb_postcode','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Timezone</label>
              <input type="text" placeholder="Timezone" name="time_zone" id="time_zone"  class="form-control" value="<?php echo $result[0]->time_zone; ?>" required>
              <label for="suburb_postcode" class="error" style="color:red; display:none; font-size:12px"></label>
              <?php echo form_error('time_zone','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Latitude</label>
              <span style="color:#FF0000;">*</span>
              <input type="text" placeholder="Latitude" name="latitude" id="latitude"  class="form-control" value="<?php echo $result[0]->latitude; ?>" required>
              <label for="latitude" class="error" style="color:red; display:none; font-size:12px"></label>
              <?php echo form_error('latitude','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Longitude</label>
              <span style="color:#FF0000;">*</span>
              <input type="text" placeholder="Longitude" name="longitude" id="longitude"  class="form-control" value="<?php echo $result[0]->longitude; ?>" required>
              <label for="longitude" class="error" style="color:red; display:none; font-size:12px"></label>
              <?php echo form_error('longitude','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Active:</label>
              <select name="is_active" id="is_active" class="form-control">
                <option value="Y" <?php if($result[0]->is_active=='Y') echo "selected='selected'"; ?>>Yes</option>
                <option value="N" <?php if($result[0]->is_active=='N') echo "selected='selected'"; ?>>No</option>
              </select>
              <label for="active" class="error" style="color:#FF0000; display:none; font-size:12px"></label>
              <?php echo form_error('active','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="clr"></div>
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
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
	var j = jQuery.noConflict(); 

    j(function() {
      j("#myFrm").validate({
        rules: {
           ks_state_id: {
                required: true,
            },
            suburb_name: {
                required: true,
            },
			suburb_postcode:{
				required: true,
			},
			latitude: {
                required: true,
            },
            longitude: {
                required: true,
            }
			
        },
      messages: {
           ks_state_id: {
					required: "Please Provide State",
				},
           suburb_name: {
					required: "Please Provide Suburb",
				},
			suburb_postcode:{
				required: "Please Provide Suburb Post Code",
			},
			latitude: {
                required: "Please Provide Latitude",
            },
            longitude: {
                required: "Please Provide Longitude",
            }
				
            
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
    
  </script>
