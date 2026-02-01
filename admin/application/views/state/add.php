<style>
			#ks_country_id {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
 			#ks_country_name {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
			#ks_state_name {
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
    <h3 class="text-themecolor m-b-0 m-t-0">Add State</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>state">State List</a></li>
      <li class="breadcrumb-item active">Add State</li>
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
      <form class="form-material m-t-40" action="<?php echo base_url();?>state/save" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="ks_country_id">Country</label>
              <span style="color:#FF0000;">*</span>
              <select class="form-control valid" name="ks_country_id" id="ks_country_id" onchange="otherCountry()">
                <option value="">--Select Country--</option>
                <?php foreach($countries as $key=>$val){ ?>
                <option value="<?php echo $val->ks_country_id; ?>"><?php echo $val->ks_country_name; ?></option>
                <?php } ?>
                <option value="other">Other</option>
              </select>
              <label for="ks_country_id" class="error" style="color:red; display:none;"></label>
              <?php echo form_error('ks_country_id','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div style="display:none;" id="div_country_name" >
              <div class="form-group">
                <label for="ks_country_name">Add Country</label>
                <span style="color:#FF0000;">*</span>
                <input placeholder="Country Name" name="ks_country_name" id="ks_country_name" class="form-control" value="" type="text">
                <label for="ks_country_name" class="error" style="color:red; display:none;"></label>
				
              </div>
            </div>
          </div>
        </div>
        <div class="clr"></div>
        <div class="row" >
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">State</label>
              <span style="color:#FF0000;">*</span>
              <input placeholder="State" name="ks_state_name" id="ks_state_name" class="form-control" value="" type="text">
			  <?php echo form_error('ks_state_name','<span class="text-danger">','</span>'); ?>
              <label for="ks_state_name" class="error" style="color:red; display:none;"></label>
            </div>
          </div>
		      <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">State Code</label>
              <input placeholder="State Code" name="ks_state_code" id="ks_state_code" class="form-control" value="" type="text">
			  <?php echo form_error('ks_state_code','<span class="text-danger">','</span>'); ?>
              <label for="ks_state_code" class="error" style="color:red; display:none;"></label>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Emergency Services Levy (ESL)</label>
              <input type="text" placeholder="Emergency Services Levy (ESL)" name="ks_state_esl" id="ks_state_esl"  class="form-control" value="" >
              <label for="ks_state_esl" class="error" style="color:red; display:none;"></label>
              <?php echo form_error('ks_state_esl','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Stamp Duty (S/D)</label>
              <input type="text" placeholder="Stamp Duty (S/D)" name="ks_state_sd" id="ks_state_sd"  class="form-control" >
              <label for="ks_state_sd" class="error" style="color:red; display:none;"></label>
              <?php echo form_error('ks_state_sd','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Premium Base Rate</label>
              <input type="text" placeholder="Premium Base Rate" name="base_rate" id="base_rate"  class="form-control" value="<?php echo $result[0]->base_rate; ?>" required>
              <label for="base_rate" class="error" style="color:red; display:none;"></label>
              <?php echo form_error('base_rate','<span class="text-danger">','</span>'); ?> </div>
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
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
	var j = jQuery.noConflict(); 
	
	function country(){
	
		var con = $("#continent_id").val();
		
		$.ajax({ url: '<?php echo base_url() ?>city/getCountry',
         data: {action: con},
         type: 'post',
         success: function(output) {
		                      //alert(output);
							  $("#country_tt").html(output);
						  }
		});
		
	}

    j(function() {
      j("#myFrm").validate({
        rules: {
           ks_country_id: {
                required: true,
            },
            ks_country_name: {
                required: true,
            },
			ks_state_name:{
				required: true,
			}
           
        },
      messages: {
           ks_country_id: {
					required: "Please Provide Country",
				},
           ks_country_name: {
					required: "Please Provide Country",
				},
			ks_state_name:{
				required: "Please Provide State",
			}
				
            
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  
  
  /*function otherCountry(){
  alert();
	    j('#new_country1').html('<div class="row"><div class="col-sm-6">'+
								  '<div class="form-group">'+
									'<label for="exampleInputEmail1">Add Country</label>'+
									'<span style="color:#FF0000;">*</span>'+
									'<input placeholder="Country Name" name="country_name" id="country_name" class="form-control" value="" type="text">'+
									'<label for="country_name" class="error" style="color:red; display:none;"></label>'+
								  '</div>'+
								'</div></div>');
  }
  */
  function hideCountry(){
       // alert("sss");
  		j('#new_country1').hide();
  
  }
  
  function otherCountry()
  {
   
  	var country_id = j('#ks_country_id').val();
	if(country_id=='other'){
			 //alert('ddd');
		j('#div_country_name').show();
	}else{
		j('#div_country_name').hide();
	}
 }
    
  </script>
