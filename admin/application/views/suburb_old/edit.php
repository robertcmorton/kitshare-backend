        <style>
			#gs_state_id {
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>suburb/update" method="post" enctype="multipart/form-data">
								    <div class="row">
											<div class="col-sm-6">
											  <div class="form-group">
												<label for="exampleInputEmail1">State</label>
												<span style="color:#FF0000;">*</span>
												<div id="country_tt">
												<input type="hidden" name="gs_suburb_id" value="<?php echo $result[0]->gs_suburb_id; ?>"  />
												<select class="form-control valid" id="gs_state_id" name="gs_state_id" onchange="otherCountry()">
													<option value="">--Select State--</option>
													<?php foreach($states as $key=>$val){ ?>
													<option value="<?php echo $val->gs_state_id; ?>" <?php if($val->gs_state_id==$result[0]->gs_state_id) echo "selected='selected'" ; ?>><?php echo $val->gs_state_name; ?></option>
												  <?php } ?>
													</select></div>
												<label for="state_id" class="error" style="color:red; display:none;"></label>
												<?php echo form_error('state_id','<span class="text-danger">','</span>'); ?> </div>
											</div>
										  </div>
											
										  <div class="row">
											<div class="col-sm-6">
											  <div class="form-group">
												<label for="exampleInputEmail1">Suburb</label>
												<span style="color:#FF0000;">*</span>
												<input type="text" placeholder="Suburb" name="suburb_name" id="suburb_name"  class="form-control" value="<?php echo $result[0]->suburb_name; ?>" required>
												<label for="suburb_name" class="error" style="color:red; display:none;"></label>
												<?php echo form_error('suburb_name','<span class="text-danger">','</span>'); ?>
											  </div>
											</div>
											</div>
											<div class="row">
											<div class="col-sm-6">
											  <div class="form-group">
												<label for="exampleInputEmail1">Suburb Post Code</label>
												<span style="color:#FF0000;">*</span>
												<input type="text" placeholder="Suburb Post Code" name="suburb_postcode" id="suburb_postcode"  class="form-control" value="<?php echo $result[0]->suburb_postcode; ?>" required>
												<label for="suburb_postcode" class="error" style="color:red; display:none;"></label>
												<?php echo form_error('suburb_postcode','<span class="text-danger">','</span>'); ?>
											  </div>
											</div>
											
											</div>
										  <div class="row">
											<div class="col-sm-6">
											  <div class="form-group">
												<label for="exampleInputEmail1">Latitude</label>
												<span style="color:#FF0000;">*</span>
												<input type="text" placeholder="Latitude" name="latitude" id="latitude"  class="form-control" value="<?php echo $result[0]->latitude; ?>" required>
												<label for="latitude" class="error" style="color:red; display:none;"></label>
												<?php echo form_error('latitude','<span class="text-danger">','</span>'); ?>
											  </div>
											  </div>
											<div class="col-sm-6">
											  <div class="form-group">
												<label for="exampleInputEmail1">Longitude</label>
												<span style="color:#FF0000;">*</span>
												<input type="text" placeholder="Longitude" name="longitude" id="longitude"  class="form-control" value="<?php echo $result[0]->longitude; ?>" required>
												<label for="longitude" class="error" style="color:red; display:none;"></label>
												<?php echo form_error('longitude','<span class="text-danger">','</span>'); ?>
											  </div>
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
											<label for="active" class="error" style="color:#FF0000; display:none;"></label>
											<?php echo form_error('active','<span class="text-danger">','</span>'); ?> </div>
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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
<script>
function chngPriv()
{

if($('#admin_typ').val()=='super')
{
$('#privlg').hide();
$('#docPrev').hide();
$('#voucherPrev').hide();
}

}
function chngPriv2()
{

if($('#admin_type').val()=='admin')
{
$('#privlg').show();
$('#docPrev').show();
$('#voucherPrev').show();

}

}

</script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
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
           gs_state_id: {
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
           gs_state_id: {
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
   
  	var country_id = j('#gs_country_id').val();
	if(country_id=='other'){
			 //alert('ddd');
		j('#div_country_name').show();
	}else{
		j('#div_country_name').hide();
	}
 }
    
  </script>
