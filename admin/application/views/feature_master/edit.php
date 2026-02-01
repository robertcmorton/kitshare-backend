<div class="page-wrapper">
<div class="container-fluid">
<div class="row page-titles">
  <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Edit Feature</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>feature_master">Features List</a></li>
      <li class="breadcrumb-item active">Edit Feature</li>
    </ol>
  </div>
</div>
<div class="row">
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit</h4>
      <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>feature_master/update" method="post" enctype="multipart/form-data">
        <input type="hidden" id="sub_cat" name="sub_cat" />
        <div class="row">
          <div class="form-group col-md-6">
            <label for="exampleInputEmail1">Gear Category Name</label>
            <select name="gear_category_id" id="gear_category_id_1" class="form-control" onChange="findnext(this.value,1,<?php echo $cnt;?>)"> >
              <option value="">--Select Gear Category--</option>
              <?php foreach($gear_category as $v){?>
              <option value="<?php echo $v->gear_category_id; ?>" <?php if($result[0]->gear_category_id==$v->gear_category_id) echo "selected='selected'";?>><?php echo $v->gear_category_name; ?></option>
              <?php } ?>
            </select>
            <div id="gear"></div>
            <label for="gear_category_id1" class="error" style="color:#FF0000;display:none;"></label>
            <?php echo form_error('gear_category_id','<span class="text-danger">','</span>'); ?></div>
        </div>
        <div class="row">
          <div class="form-group col-md-4">
            <label for="exampleInputarticle">Feature Name</label>
            <input type="text" class="form-control" name="feature_name" id="feature_name" value="<?php echo $result[0]->feature_name; ?>" placeholder="Feature Name">
            <label for="feature_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('feature_name','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-4">
            <label for="exampleInputarticle">Feature Abbr</label>
            <input type="text" class="form-control" name="feature_abbr" id="feature_abbr" value="<?php echo $result[0]->feature_abbr; ?>" placeholder="Feature Abbr">
            <label for="feature_abbr" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('feature_abbr','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-4">
            <label for="exampleInputarticle">Feature Unit</label>
            <input type="text" class="form-control" name="feature_unit" id="feature_unit" value="<?php echo $result[0]->feature_unit; ?>" placeholder="Feature Name">
            <label for="feature_unit" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('feature_unit','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="row" id="divFeatureValue">
		<?php
		//Feature lists are fetched
		$query_features = $this->model->get_feature_list($result[0]->feature_master_id);
		$res_features = $query_features->result_array();
		if(count($res_features)>0){
			
			$i = 1;
			$j = count($res_features);
			foreach($res_features as $row_features){
			
			if($i==1)
			 	$feature_val_label = "Feature Value";
			else
				$feature_val_label = "&nbsp;";
		?>
		<div class="form-group col-md-4" id="appenddivFeatureValue<?php echo $i;?>">
		<label for="exampleInputImage"><?php echo $feature_val_label;?></label>
		<input type="text" class="form-control" name="feature_value[]" id="feature_value" value="<?php echo $row_features['feature_values'];?>" placeholder="Feature Value">&nbsp;<?php if($i==1){?>
		<a href="javascript:void(0);" onclick="addFeatureValue('divFeatureValue',<?php echo $j;?>,'addFeatureVal<?php echo $i;?>')" id="addFeatureVal1">Add More</a>
		<?php }?>
		</div>
		<?php
			$i++;
			}
		}else{
		?>
		<div class="form-group col-md-4" id="appenddivFeatureValue1">
		<label for="exampleInputImage">Feature Value</label>
		<input type="text" class="form-control" name="feature_value[]" id="feature_value" value="" placeholder="Feature Value">&nbsp;<a href="javascript:void(0);" onclick="addFeatureValue('divFeatureValue',1,'addFeatureVal1')" id="addFeatureVal1">Add More</a>
		</div>
		<?php
		}
		?>
		</div>
			
		<div class="row">
		<div class="form-group col-md-4">
			<label for="exampleInputImage">Status</label>
			<select class="form-control select2" name="status" id="status" style="width:100%;">
			  <option value="Active" <?php if($result[0]->is_active=="Active") echo "selected='selected'";?>>Active</option>
			  <option value="Inactive" <?php if($result[0]->is_active=="Inactive") echo "selected='selected'";?>>Inactive</option>
			</select>
			<?php echo form_error('status','<span class="text-danger">','</span>'); ?> </div>
		<div class="col-md-8"></div>
		</div>
        <div class="clr"></div>
        <div class="box-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
        <div class="clr"></div>
		<input type="hidden" name="feature_master_id" value="<?php echo $feature_master_id;?>" />
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
	var id = '<?php echo  $this->uri->segment(3); ?>';
	$.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>gear_categories/ajax/"+id,
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
			$('#'+placeholder+'').append(response);
		
		}
	
	});

}


function removeFeatureValue(remplaceholder){
	$('#'+remplaceholder+'').remove();
}
</script>
