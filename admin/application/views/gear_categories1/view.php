<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> View Gear Categories </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url();?>home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url();?>gear_categories">Gear Categories List</a></li>
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
            <form action="<?php echo base_url();?>gear_categories/update" method="post" id="myFrm" enctype="multipart/form-data">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Category</label>
					<input type="hidden" name="category_id" value="<?php echo $result[0]->gear_category_id; ?>"  />
                    <select name="gear_category_id" id="gear_category_id" class="form-control">
                      <option value="">--Select Category--</option>
                      <?php foreach($gear_categories as $v){?>
                      <option value="<?php echo $v->gear_category_id; ?>" <?php if($result[0]->gear_sub_category_id==$v->gear_category_id) echo "selected=selected" ; ?>><?php echo $v->gear_category_name; ?></option>
                      <?php } ?>
                    </select>
                    <label for="gear_category_id" class="error" style="color:#FF0000;display:none;"></label>
                    <?php echo form_error('gear_category_id','<span class="text-danger">','</span>'); ?> </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="exampleInputarticle">Category Name </label>
                    <input type="text" class="form-control" name="gear_category_name" id="gear_category_name" value="<?php echo $result[0]->gear_category_name; ?>" placeholder="Specification">
                    <label for="gear_category_name" class="error" style="color:#FF0000; display:none;"></label>
                    <?php echo form_error('gear_category_name','<span class="text-danger">','</span>'); ?> </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="exampleInputImage">Status</label>
                    <select class="form-control select2" name="is_active" id="is_active" style="width:100%;" value="<?php echo set_value('is_active'); ?>"   onChange="getstatus(this.value);">
                      <option value="Y"<?php if($result[0]->is_active=='Y') echo "selected=selected" ; ?>>Yes</option>
                      <option value="N"<?php if($result[0]->is_active=='N') echo "selected=selected" ; ?>>No</option>
                    </select>
                    <?php echo form_error('is_active','<span class="text-danger">','</span>'); ?> </div>
                </div>
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
			spec_id: {
				required: true,
				
			},
			device_id: {
				required: true,
				
			},
			spec_value: {
				required: true,
				
			},
           
        },
      messages: {
			spec_id: {
				required: "Please provide a specification type",
				
			},
			device_id: {
				required: "Please provide a device",
				
			},
			spec_value: {
				required: "Please provide specification",
				
			}, 
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
    
  </script>

