<?php //print_r($user_privileges); ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> View Role Privilege </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url();?>home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url();?>menu_privilege">Role Privileges</a></li>
      <li class="active">View</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-8">
        <!-- /.box -->
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">View </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body pad">
            <form action="<?php echo base_url();?>menu_privilege/update" method="post" id="myFrm">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Role</label>
                    <span style="color:#FF0000;">*</span>
					<?php // echo $result[0]->app_role_name; ?>
					<input type="hidden" name="app_role_id" id="app_role_id" value="<?php echo $result[0]->app_role_id; ?>" />
                    <select class="form-control"  disabled>
                      <option value="">--Select role--</option>
                      <?php if(!empty($role)) { foreach($role as $key=>$value){ ?>
                      <option value="<?php echo $value->app_role_id; ?>" <?php if($result[0]->app_role_id==$value->app_role_id) echo "selected='selected'" ; ?>><?php echo $value->app_role_name; ?></option>
                      <?php } } ?>
                    </select>
                    <label for="app_role_id" class="error" style="color:red; display:none;"></label>
                    <?php echo form_error('app_role_id','<span class="text-danger">','</span>'); ?> </div>
                </div>
                
                <div class="clr"></div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group mbr">
                    <label for="exampleInputEmail1">Privilege Type</label>
                    <span style="color:#FF0000;">*</span>
                    <div class="row">
					
					<?php foreach($privilege as $key=>$val){ ?>
                      <div class="col-sm-2">
                        <input type="checkbox" name="privilege_type[]" id="" value="<?php echo $val->app_priv_id; ?>" <?php if($result[$key]->app_priv_id==$val->app_priv_id) echo "checked='checked'" ; ?> > <?php echo ucfirst($val->privilege_type); ?></div>
                      
                      <?php } ?>
                      
                    </div>
                    <label for="privilege_type[]" class="error" style="color:red; display:none;"></label>
                    <?php echo form_error('privilege_type[]','<span class="text-danger">','</span>'); ?> </div>
                </div>
                <div class="clr"></div>
              </div>
              
            </form>
          </div>
        </div>
      </div>
      <!-- /.col-->
    </div>
    <!-- ./row -->
  </section>
  <!-- /.content -->
</div>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
	var j = jQuery.noConflict(); 

    j(function() {
      j("#myFrm").validate({
        rules: {
           
		     app_role_id: {
					required: true,
					
                 },
				"privilege_type[]": {
					required: true,
					minlength: 1 
					
				}
           
        },
      messages: {
              
				app_role_id: {
					required: "Please provide role",
					
					
				},
				"privilege_type[]": {
					required: "Please select at least one type of privilege",
					
				}
				
				
            
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
    
  </script>
