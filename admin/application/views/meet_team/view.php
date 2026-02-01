
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> View User </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url();?>home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url();?>users">User List</a></li>
      <li class="active">View User</li>
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
          <form action="<?php echo base_url();?>users/update" method="post" id="myFrm" enctype="multipart/form-data">
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group mbr">
                  <label for="exampleInputEmail1">Username:</label>
				   <input type="hidden" name="app_user_id" id="app_user_id" value="<?php echo $user[0]->app_user_id; ?>"  />
                  <input type="text" name="username" id="username" placeholder="Username" class="form-control" value="<?php echo $user[0]->app_user_name; ?>"  />
				  <label for="username" class="error" style="color:#FF0000; display:none;"></label>
                </div>
              </div>
              <div class="clr"></div>
            </div>
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group mbr">
                  <label for="exampleInputEmail1">Password:</label>
                  <?php $password= $this->common_model->base64De(2,$user[0]->app_user_pwd) ?>
                  <input type="password" name="password" id="password" placeholder="Password" class="form-control" value="<?php echo $password; ?>" />
				  <label for="password" class="error" style="color:#FF0000; display:none;"></label>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group mbr">
                  <label for="exampleInputEmail1">Confirm Password:</label>
                  <input type="password" name="cnfpwd" id="cnfpwd" placeholder="Confirm Password" class="form-control" value="<?php echo $password; ?>" />
				  <label for="cnfpwd" class="error" style="color:#FF0000; display:none;"></label>
                </div>
              </div>
              <div class="clr"></div>
            </div>
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="exampleInputEmail1">Active:</label>
                  <select class="form-control select2" name="active" id="active" style="width:100%;" >
                    <option value="Y"<?php if ($user[0]->active == 'Y') echo "selected='selected'"; ?>>Active</option>
                    <option value="N"<?php if ($user[0]->active == 'N') echo "selected='selected'"; ?>>Inactive</option>
                  </select>
                </div>
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
    CKEDITOR.replace('member_desc');
    $(".textarea").wysihtml5();
  });
</script>

