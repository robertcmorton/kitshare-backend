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
                        <h3 class="text-themecolor m-b-0 m-t-0">Modify User Bank Detail</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>user_bank_details">User Bank Details List</a></li>
                            <li class="breadcrumb-item active">Modify User Bank Detail</li>
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
                                <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>user_bank_details/update" method="post" enctype="multipart/form-data"><input type="hidden" id="user_bank_detail_id" name="user_bank_detail_id" value="<?php echo $result[0]->user_bank_detail_id; ?>"/>
									<div class="row">
									<div class="form-group col-md-4">
										<label for="exampleInputarticle">Bank Name</label>
										<select class="form-control" name="bank_id" id="bank_id" value="<?php echo set_value('bank_id'); ?>">
											<option value="">--Select Bank--</option>
											<?php foreach($bank as $u){?>
											<option value="<?php echo $u->bank_id;?>" <?php if($result[0]->bank_id==$u->bank_id) echo "selected='selected'"; ?>><?php echo $u->bank_name; ?></option>
											<?php } ?>
										</select>
										<label for="bank_id" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('bank_id','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-4">
										<label for="exampleInputarticle">App User</label>
										<select class="form-control" name="app_user_id" id="app_user_id" value="<?php echo set_value('app_user_id'); ?>">
											<option value="">--Select User--</option>
											<?php foreach($user as $u){?>
											<option value="<?php echo $u->app_user_id;?>" <?php if($result[0]->app_user_id==$u->app_user_id) echo "selected='selected'"; ?>><?php echo $u->app_username; ?></option>
											<?php } ?>
										</select>
										<label for="app_user_id" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('app_user_id','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-4">
										<label for="exampleInputarticle">Account Type</label>
										<input type="text" class="form-control" name="account_type" id="account_type" value="<?php echo $result[0]->account_type; ?>" placeholder="Account Type">
										<label for="account_type" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('account_type','<span class="text-danger">','</span>'); ?> 
									</div>
									</div>
									<div class="row">
									<div class="form-group col-md-6">
										<label for="exampleInputarticle">Branch Code</label>
										<input type="text" class="form-control" name="branch_code" id="branch_code" value="<?php echo $result[0]->branch_code; ?>" placeholder="Branch Code">
										<label for="branch_code" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('branch_code','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-6">
										<label for="exampleInputarticle">BSB Number</label>
										<input type="text" class="form-control" name="bsb_number" id="bsb_number" value="<?php echo $result[0]->bsb_number; ?>" placeholder="BSB Number">
										<label for="bsb_number" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('bsb_number','<span class="text-danger">','</span>'); ?> 
									</div>
									</div>
									<div class="row">
									<div class="form-group col-md-3">
										<label for="exampleInputarticle">Branch Address</label>
										<input type="text" class="form-control" name="branch_address" id="branch_address" value="<?php echo $result[0]->branch_address; ?>" placeholder="Branch Address">
										<label for="branch_address" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('branch_address','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-3">
										<label for="exampleInputarticle">Branch Street</label>
										<input type="text" class="form-control" name="branch_street" id="branch_street" value="<?php echo $result[0]->branch_street; ?>" placeholder="Branch Street">
										<label for="branch_street" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('branch_street','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-3">
										<label for="exampleInputarticle">Branch City</label>
										<input type="text" class="form-control" name="branch_city" id="branch_city" value="<?php echo $result[0]->branch_city; ?>" placeholder="Branch City">
										<label for="branch_city" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('branch_city','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-3">
										<label for="exampleInputarticle">Branch Zip Code</label>
										<input type="text" class="form-control" name="branch_zip_code" id="branch_zip_code" value="<?php echo $result[0]->branch_zip_code; ?>" placeholder="Branch Zip Code">
										<label for="branch_zip_code" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('branch_zip_code','<span class="text-danger">','</span>'); ?> 
									</div>
									</div>
									<div class="row">
									<div class="form-group col-md-4">
										<label for="exampleInputarticle">Branch Country</label>
										<select class="form-control" name="branch_country_id" id="branch_country_id" onChange="findstate(this.value)">
											<option value="">--Select Country--</option>
											<?php foreach($country as $c){?>
											<option value="<?php echo $c->ks_country_id;?>" <?php if($result[0]->branch_country_id==$c->ks_country_id) echo "selected='selected'"; ?>><?php echo $c->ks_country_name; ?></option>
											<?php } ?>
										</select>
										<label for="branch_country_id" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('branch_country_id','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-4">
										<label for="exampleInputarticle">Branch State</label>
										<select class="form-control" name="branch_state_id" id="branch_state_id" value="<?php echo set_value('branch_state_id'); ?>" onChange="findsuburb(this.value)">
											<option value="">--Select State--</option>
											<?php $sql1="SELECT * FROM ks_states WHERE ks_country_id=".$result[0]->branch_country_id;$result1=$this->db->query($sql1); $result2=$result1->result();foreach($result2 as $s){?>
											<option value="<?php echo $s->ks_state_id;?>" <?php if($result[0]->branch_state_id==$s->ks_state_id) echo "selected='selected'"; ?>><?php echo $s->ks_state_name; ?></option>
											<?php } ?>
										</select>
										<label for="branch_state_id" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('branch_state_id','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-4">
										<label for="exampleInputarticle">Branch Suburb</label>
										<select class="form-control" name="branch_suburb_id" id="branch_suburb_id" >
											<option value="">--Select Suburb--</option>
											<?php $sql1="SELECT * FROM ks_suburbs WHERE ks_state_id=".$result[0]->branch_state_id;
											$result1=$this->db->query($sql1); 
											$result2=$result1->result();
											foreach($result2 as $su){?>
											<option value="<?php echo $su->ks_suburb_id;?>"<?php if($result[0]->branch_suburb_id==$su->ks_suburb_id)  echo "selected='selected'" ; ?> ><?php echo $su->suburb_name; ?></option>
											<?php } ?>
											</select>
											
										<label for="branch_suburb_id" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('branch_suburb_id','<span class="text-danger">','</span>'); ?> 
									</div>
									</div>
									<div class="row">
									<div class="form-group col-md-4">
										<label for="exampleInputarticle">User Account Number</label>
										<input type="text" class="form-control" name="user_account_number" id="user_account_number" value="<?php echo $result[0]->user_account_number; ?>" placeholder="User Account Number">
										<label for="user_account_number" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('user_account_number','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-4">
										<label for="exampleInputImage">Stripe Connection</label>
											<select class="form-control select2" name="accept_stripe_connection" id="accept_stripe_connection" style="width:100%;" value="<?php echo $result[0]->accept_stripe_connection; ?>">
											  <option value="Y">Accepted</option>
											  <option value="N">Not Accepted</option>
											</select>
											<?php echo form_error('accept_stripe_connection','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-4">
										<label for="exampleInputImage">Status</label>
											<select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>">
											  <option value="Y" <?php if($result[0]->is_active=="Y") echo "selected='selected'";?>>Active</option>
											  <option value="N" <?php if($result[0]->is_active=="N") echo "selected='selected'";?>>Inactive</option>
											</select>
											<?php echo form_error('status','<span class="text-danger">','</span>'); ?> 
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

	j('#feature_description').wysihtml5();
});
function findstate(x){
	j.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>user_bank_details/ajax_state",
            data:{data:x},
            success:function(response){
				j("#branch_state_id").html(response);
            }
        });
}
function findsuburb(x){
	j.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>user_bank_details/ajax_suburb",
            data:{data:x},
            success:function(response){
				j("#branch_suburb_id").html(response);
            }
        });
}
  </script>