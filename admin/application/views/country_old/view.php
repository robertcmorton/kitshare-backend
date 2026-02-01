
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> View Module </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url();?>home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url();?>app_module">Module List</a></li>
      <li class="active">View Module</li>
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
          <form action="<?php echo base_url();?>app_module/update" method="post" id="myFrm" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group mbr">
                <label for="exampleInputEmail1">Module:</label>
				<input type="hidden" value="<?php echo $result[0]->mod_id; ?>" name="mod_id" id="mod_id" />
                <input type="text" name="app_module_page" id="app_module_page" placeholder="Module" class="form-control" value="<?php echo $result[0]->app_module_page; ?>" />
				<label for="app_module_page" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('app_module_page','<span class="text-danger">','</span>'); ?> </div>
            </div>
            <div class="clr"></div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group mbr">
                <label for="exampleInputEmail1">Description:</label>
                <textarea name="app_module_desc" id="app_module_desc" placeholder="Description" class="form-control" value="" ><?php echo $result[0]->app_module_desc; ?></textarea>
				<label for="app_module_desc" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('app_module_desc','<span class="text-danger">','</span>'); ?> </div>
            </div>
            
            <div class="clr"></div>
          </div>
          
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group mbr">
                <label for="exampleInputEmail1">Active:</label>
                <select name="active" id="active" class="form-control">
				<option value="Y" <?php if($result[0]->active=='Y') echo "selected='selected'"; ?>>Yes</option>
				<option value="N" <?php if($result[0]->active=='N') echo "selected='selected'"; ?>>No</option>
				</select>
				<label for="active" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('active','<span class="text-danger">','</span>'); ?> </div>
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

