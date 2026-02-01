
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> View User Role </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url();?>home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url();?>user_role">User Role List</a></li>
      <li class="active">View User Role</li>
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
          <form action="<?php echo base_url();?>user_role/update" method="post" id="myFrm" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group mbr">
                <label for="exampleInputEmail1">User:</label>
				<input type="hidden" value="<?php echo $result[0]->user_role_id; ?>" name="user_role_id" />
				<select name="app_user_id" class="form-control" id="app_user_id">
					<option value="">--Select User--</option>
					<?php foreach($users as $key=>$val){ ?>
					
							<option value="<?php echo $val->app_user_id ; ?>" <?php if($result[0]->app_user_id==$val->app_user_id) echo "selected='selected'" ; ?>><?php echo $val->app_user_name ; ?></option>
					<?php } ?>
				</select>
				
				<label for="app_user_id" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('app_user_id','<span class="text-danger">','</span>'); ?> </div>
            </div>
            <div class="clr"></div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group mbr">
                <label for="exampleInputEmail1">Role:</label>
				
				<select name="app_role_id" class="form-control" id="app_role_id" onchange="rolePrivDetails()">
					<option value="">--Select Role--</option>
					<?php foreach($roles as $key=>$val){ ?>
					
							<option value="<?php echo $val->app_role_id ; ?>" <?php if($result[0]->app_role_id==$val->app_role_id) echo "selected='selected'" ; ?>><?php echo $val->app_role_name ; ?></option>
					<?php } ?>
				</select>
				
				<label for="app_role_id" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('app_role_id','<span class="text-danger">','</span>'); ?> </div>
            </div>
            
            <div class="clr"></div>
          </div>
          <div id="details">
		  <style>
			.new {
				background-color: #03161d1a;
				width: 243px;
				padding: 25px;
			}
			</style>
			<div class="new"><h4><b>Plivilege for Role:</b></h4>
			<ul>
			<?php
			$pri_type_id = $this->common_model->GetAllWhere(APP_ROLE_PRIV,array("app_role_id"=>$result[0]->app_role_id));
			foreach($pri_type_id->result() as $key=>$val)
			{
				$app_priv_id = $val->app_priv_id;
				$pri_type = $this->common_model->GetAllWhere(M_APP_PRIVILEGE,array("app_priv_id"=>$app_priv_id));
				$str='';
				foreach($pri_type->result() as $k=>$v)
				{
					$str=ucfirst($v->privilege_type); ?>
					
					<li><i><?php echo $str; ?></i></li>
					
				<?php } } ?>
			</ul>
		  </div>
         </div>
		 
		 <div class="row">
            <div class="col-sm-3">
              <div class="form-group mbr">
                <label for="exampleInputEmail1">Status:</label>
                <select name="active" id="active" class="form-control">
				<option value="Y" <?php if($result[0]->active=='Y') echo "selected='selected'"; ?>>Active</option>
				<option value="N" <?php if($result[0]->active=='N') echo "selected='selected'"; ?>>Inactive</option>
				</select>
				<label for="active" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('active','<span class="text-danger">','</span>'); ?> </div>
            </div>
            
            <div class="clr"></div>
          </div>
		 
          <div class="box-footer">
            <button type="submit" class="btn btn-success">Submit</button>
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

