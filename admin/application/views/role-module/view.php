<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> View Role Module </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url();?>home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url();?>role_module">Role Module List</a></li>
      <li class="active">View Role Module</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- /.box -->
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">View </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body pad">
          <form action="<?php echo base_url();?>role_module/update" method="post" id="myFrm" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group mbr">
                <label for="exampleInputEmail1">Module:</label>
				<input type="hidden" value="<?php echo $result[0]->role_mod_id; ?>" name="role_mod_id"  />
				<select name="mod_id" class="form-control" id="mod_id">
					<option value="">--Select User--</option>
					<?php foreach($modules as $key=>$val){ ?>
					
							<option value="<?php echo $val->mod_id ; ?>" <?php if($val->mod_id==$result[0]->mod_id) echo "selected='selected'" ; ?>><?php echo $val->app_module_page ; ?></option>
					<?php } ?>
				</select>
				
				<label for="mod_id" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('mod_id','<span class="text-danger">','</span>'); ?> </div>
            </div>
            <div class="clr"></div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group mbr">
                <label for="exampleInputEmail1">Role:</label>
				
				<select name="app_role_id" class="form-control" id="app_role_id" >
					<option value="">--Select Role--</option>
					<?php foreach($roles as $key=>$val){ ?>
					
							<option value="<?php echo $val->app_role_id ; ?>" <?php if($val->app_role_id==$result[0]->app_role_id) echo "selected='selected'" ; ?>><?php echo $val->app_role_name ; ?></option>
					<?php } ?>
				</select>
				
				<label for="app_role_id" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('app_role_id','<span class="text-danger">','</span>'); ?> </div>
            </div>
            
            <div class="clr"></div>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /.col-->
</div>
<!-- ./row -->
</section>
<!-- /.content -->
</div>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url();?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
  $(function () {
    CKEDITOR.replace('app_role_desc');
    $(".textarea").wysihtml5();
  });
</script>

