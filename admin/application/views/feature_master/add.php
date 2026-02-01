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
                        <h3 class="text-themecolor m-b-0 m-t-0">Add Feature</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>feature_master">Features List</a></li>
                            <li class="breadcrumb-item active">Add Feature</li>
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
                                <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>feature_master/save" method="post" enctype="multipart/form-data"><input type="hidden" id="sub_cat" name="sub_cat" />
										<div class="form-group col-md-6">
										<label for="exampleInputEmail1">Gear Category Name</label>
										<select name="gear_category_id_1" id="gear_category_id_1" class="form-control" onChange="findnext(this.value,1,<?php echo $cnt;?>)">>
										  <option value="">--Select Gear Category--</option>
										  <?php foreach($gear_category as $v){?>
										  <option value="<?php echo $v->gear_category_id; ?>"><?php echo $v->gear_category_name; ?></option>
										  <?php } ?>
										</select>
										<div id="gear"></div>
										<label for="gear_category_id" class="error" style="color:#FF0000;display:none;"></label>
										<?php echo form_error('gear_category_id','<span class="text-danger">','</span>'); ?></div>
										<div class="row">
									<div class="form-group col-md-4">
										<label for="exampleInputarticle">Feature Name</label>
										<input type="text" class="form-control" name="feature_name" id="feature_name" value="<?php echo set_value('feature_name'); ?>" placeholder="Feature Name">
										<label for="feature_name" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('feature_name','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-4">
										<label for="exampleInputarticle">Feature Abbr</label>
										<input type="text" class="form-control" name="feature_abbr" id="feature_abbr" value="<?php echo set_value('feature_abbr'); ?>" placeholder="Feature Abbr">
										<label for="feature_abbr" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('feature_abbr','<span class="text-danger">','</span>'); ?> 
									</div>
									<div class="form-group col-md-4">
										<label for="exampleInputarticle">Feature Unit</label>
										<input type="text" class="form-control" name="feature_unit" id="feature_unit" value="<?php echo set_value('feature_unit'); ?>" placeholder="Feature Name">
										<label for="feature_unit" class="error" style="color:#FF0000; display:none;"></label>
										<?php echo form_error('feature_unit','<span class="text-danger">','</span>'); ?> 
									</div>
									
									</div>
									<div class="row" id="divFeatureValue">
										<div class="form-group col-md-4" id="appenddivFeatureValue1">
												<label for="exampleInputImage">Feature Value</label>
												<input type="text" class="form-control" name="feature_value[]" id="feature_value" value="" placeholder="Feature Value">&nbsp;<a href="javascript:void(0);" onclick="addFeatureValue('divFeatureValue',1,'addFeatureVal1')" id="addFeatureVal1">Add More</a>
										</div>
									</div>
									<div class="row">
										<div class="col-3">
											<div class="form-group">
											<label for="exampleInputImage">Status</label>
											<select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>" onChange="getstatus(this.value);">
											  <option value="Y">Active</option>
											  <option value="N">Inactive</option>
											</select>
											<?php echo form_error('status','<span class="text-danger">','</span>'); ?> </div>
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
function findnext(x,y,z){
	$.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>gear_categories/ajax",
            data:{data:x,count:y},
            success:function(response){
                //alert(y);
				var c=y+1;
				//alert(a);
				while(c<z){$("#category_"+c).remove();c++};
				$("#gear").append(response);
				$("#sub_cat").val(y);
            }
        });
}

function addFeatureValue(placeholder,counter,addplaceholder){
	
	counter=counter+1;
	
	$.ajax({
		type:"post",
		url:"<?php echo base_url(); ?>feature_master/add_feature_value",
		data:{'counter':counter},
		success:function(response){
			j('#'+placeholder+'').append(response);
		
		}
	
	});

}


function removeFeatureValue(remplaceholder){
	j('#'+remplaceholder+'').remove();
}

  </script>