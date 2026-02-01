<style>
.morecontent span {
    display: none;
}
.morelink {
    display: block;
}
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
    <h3 class="text-themecolor m-b-0 m-t-0">View Gear Description</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>gear_desc">Gear Description List</a></li>
      <li class="breadcrumb-item active">View Gear Description</li>
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
      <h4 class="card-title">View </h4>
      <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>gear_desc/update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="user_gear_desc_id" id="user_gear_desc_id" value="<?php echo $result[0]->user_gear_desc_id; ?>"/>
        <div class="row">
          <div class="form-group col-md-4">
            <label for="exampleInputEmail1">Model</label>
            <select name="model_id" id="model_id" class="form-control" >
              <option value="">--Select Model--</option>
              <?php foreach($models as $v){?>
              <option value="<?php echo $v->model_id; ?>" <?php if($v->model_id==$result[0]->model_id) echo "selected='selected'";?>><?php echo $v->model_name; ?></option>
              <?php } ?>
            </select>
            <label for="model_id" class="error" style="color:#FF0000;display:none;"></label>
            <?php echo form_error('model_id','<span class="text-danger">','</span>'); ?></div>
          <div class="form-group col-md-4">
            <label for="exampleInputarticle">Gear Name</label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $result[0]->gear_name; ?>" placeholder="Gear Name">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-4">
            <label for="exampleInputarticle">Manufacturers </label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $manufac[0]->manufacturer_name; ?>" placeholder="Manufacturers" readonly="">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-4">
            <label for="exampleInputarticle">Category </label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $result[0]->gear_category_name; ?>" placeholder="Category" readonly="">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> 
          </div>
          <div class="form-group col-md-4">
            <label for="exampleInputarticle">Sub Category </label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $result[0]->sub_category_name; ?>" placeholder="Sub Category" readonly="">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> 
          </div>
           <div class="form-group col-md-4">
            <label for="exampleInputarticle">Serial Number </label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $result[0]->serial_number; ?>" placeholder="Serial Number " readonly="">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?>  
          </div>
           <div class="form-group col-md-4">
            <label for="exampleInputarticle">Condition</label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $result[0]->ks_user_gear_condition; ?>" placeholder="Condition" readonly="">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> 
          </div>
         <!--  <div class="form-group col-md-4">
            <label for="exampleInputarticle">Gear Type</label>
            <select  type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $result[0]->ks_user_gear_condition; ?>" placeholder="Gear Type" readonly="">
              <option></option>
              <option></option>
              <option></option>

            </select>
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> 
          </div> -->
          </div>

		  <div class="row">
    <div class="form-group col-md-6">
            <label for="exampleInputarticle">Replacement Value excluding GST</label>
            <input type="text" class="form-control" name="replacement_value_aud_ex_gst" id="replacement_value_aud_ex_gst" value="<?php echo number_format((float)$result[0]->replacement_value_aud_ex_gst, 2, '.', ''); ; ?>" placeholder="Replacement Value" readonly="">
            <label for="replacement_value_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('replacement_value_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
      <div class="form-group col-md-6">
            <label for="exampleInputarticle">Replacement Value including GST</label>
            <input type="text" class="form-control" name="replacement_value_aud_inc_gst" id="replacement_value_aud_inc_gst" value="<?php echo  number_format((float)$result[0]->replacement_value_aud_inc_gst, 2, '.', ''); ?>" placeholder="Replacement Value" readonly="">
            <label for="replacement_value_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('replacement_value_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
    </div>
        <div class="row">
          <div class="form-group col-md-6" oninput="getprice()">
            <label for="exampleInputarticle">Per Day Cost excluding GST</label>
            <input type="text" class="form-control" name="per_day_cost_aud_ex_gst" id="per_day_cost_aud_ex_gst" value="<?php echo  number_format((float)$result[0]->per_day_cost_aud_ex_gst, 2, '.', ''); ?>" placeholder="Cost Per Day" readonly="">
            <label for="per_day_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_day_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
      <div class="form-group col-md-6" oninput="getprice()">
            <label for="exampleInputarticle">Per Day Cost including GST</label>
            <input type="text" class="form-control" name="per_day_cost_aud_inc_gst" id="per_day_cost_aud_inc_gst" value="<?php echo number_format((float)$result[0]->per_day_cost_aud_inc_gst, 2, '.', ''); ; ?>" placeholder="Cost Per Day" readonly="">
            <label for="per_day_cost_aud_inc_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_day_cost_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
      <div class="row">
      <div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Weekend Cost excluding GST</label>
            <input type="text" class="form-control" name="per_weekend_cost_aud_ex_gst" id="per_weekend_cost_aud_ex_gst" value="<?php echo number_format((float)$result[0]->per_weekend_cost_aud_ex_gst, 2, '.', ''); ?>" placeholder="Cost Per Weekend" disabled>
            <label for="per_weekend_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_weekend_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
      <div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Weekend Cost including GST</label>
            <input type="text" class="form-control" name="per_weekend_cost_aud_inc_gst" id="per_weekend_cost_aud_inc_gst" value="<?php echo  number_format((float)$result[0]->per_weekend_cost_aud_inc_gst, 2, '.', ''); $result[0]->per_weekend_cost_aud_inc_gst; ?>" placeholder="Cost Per Weekend" disabled>
            <label for="per_weekend_cost_aud_inc_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_weekend_cost_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
      </div>
      <div class="row">
          <div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Week Cost excluding GST</label>
            <input type="text" class="form-control" name="per_week_cost_aud_ex_gst" id="per_week_cost_aud_ex_gst" value="<?php echo  number_format((float)$result[0]->per_week_cost_aud_ex_gst, 2, '.', '');; ?>" placeholder="Cost Per Weekend" readonly="" disabled>
            <label for="per_week_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_week_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
      <div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Week Cost including GST</label>
            <input type="text" class="form-control" name="per_week_cost_aud_inc_gst" id="per_week_cost_aud_inc_gst" value="<?php echo number_format((float)$result[0]->per_week_cost_aud_inc_gst, 2, '.', ''); ; ?>" placeholder="Cost Per Weekend" readonly="" disabled>
            <label for="per_week_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_week_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="exampleInputEmail1">Owner Remark </label>
              <textarea class="textarea_or form-control" rows="10" name="owner_remark" id="owner_remark" value="<?php echo set_value('owner_remark'); ?>"><?php echo $result[0]->owners_remark; ?></textarea>
              <?php echo form_error('owner_remark','<span class="text-danger">','</span>'); ?>
              <label for="owner_remark" class="error" style="color:#FF0000; display:none;"></label>
            </div>
          </div>
        </div>
        <div class="row" style="display:none;">
          <div class="col-12">
            <div class="form-group">
              <label for="gear_desc_1">Gear Description 1</label>
              <textarea class="textarea_or form-control" rows="10" name="gear_desc_1" id="gear_desc_1" value="<?php echo set_value('gear_desc_1'); ?>"><?php echo $result[0]->gear_description_1; ?></textarea>
              <?php echo form_error('gear_desc_1','<span class="text-danger">','</span>'); ?>
              <label for="gear_desc_1" class="error" style="color:#FF0000; display:none;"></label>
            </div>
          </div>
        </div>
      <!--  <div class="row">'
          <div class="col-12">
            <div class="form-group">
              <label for="exampleInputEmail1">Gear Description 2</label>
              <textarea class="textarea_or form-control" rows="10" name="gear_desc_2" id="gear_desc_2" value="<?php echo set_value('gear_desc_2'); ?>"><?php echo $result[0]->gear_description_2; ?></textarea>
              <?php echo form_error('gear_desc_2','<span class="text-danger">','</span>'); ?>
              <label for="gear_desc_2" class="error" style="color:#FF0000; display:none;"></label>
            </div>
          </div>
        </div>
	-->
        <div class="form-group col-md-6">
          <label for="exampleInputEmail1">App Users</label>
          <select name="app_user_id" id="app_user_id" class="form-control">
            <option value="">--Select App User--</option>
            <?php foreach($users as $u){?>
            <option value="<?php echo $u->app_user_id; ?>" <?php if($u->app_user_id==$result[0]->app_user_id) echo "selected='selected'";?>><?php echo $u->app_username; ?></option>
            <?php } ?>
          </select>
          <label for="app_user_id" class="error" style="color:#FF0000;display:none;"></label>
          <?php echo form_error('app_user_id'); ?></div>
        <div class="col-6">
          <div class="form-group">
            <label for="exampleInputImage">Status</label>
            <select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>"   onChange="getstatus(this.value);">
              <option value="Active" <?php if($result[0]->is_active=="Active") echo "selected='selected'";?>>Active</option>
              <option value="Inactive" <?php if($result[0]->is_active=="Inactive") echo "selected='selected'";?>>Inactive</option>
            </select>
            <?php echo form_error('status','<span class="text-danger">','</span>'); ?> </div>
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
<script type="text/javascript" src="<?php echo base_url();?>assist/js/jquery.min.js"></script>
<script type="text/javascript">

function delete_confirm(){

	var result = confirm("Are you sure to delete ?");

	if(result){

		return true;

	}else{

		return false;

	}

}



$(document).ready(function(){

    $('#select_all').on('click',function(){

        if(this.checked){

            $('.checkbox').each(function(){

                this.checked = true;

            });

        }else{

             $('.checkbox').each(function(){

                this.checked = false;

            });

        }

    });

	

	$('.checkbox').on('click',function(){

		if($('.checkbox:checked').length == $('.checkbox').length){

			$('#select_all').prop('checked',true);

		}else{

			$('#select_all').prop('checked',false);

		}

	});

});
$('#select-all').click(function(event) {
        var $that = $(this);
        $(':checkbox').each(function() {
            this.checked = $that.is(':checked');
        });
    });

</script>
<script src="<?php echo base_url();?>assets/plugins/html5-or/wysihtml5-0.3.0.js"></script>
<script src="<?php echo base_url();?>assets/plugins/html5-or/bootstrap-wysihtml5.js"></script>
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

	j('#owner_remark').wysihtml5();
	j('#gear_desc_1').wysihtml5();
	j('#gear_desc_2').wysihtml5();

});

function getprice(){
	var a = document.frm.per_day_cost_aud_ex_gst.value;
	if(a!=''){
		document.frm.per_weekend_cost_aud_ex_gst.value =a;
		document.frm.per_week_cost_aud_ex_gst.value =a*3;
	}
	else{
		document.frm.per_weekend_cost_aud_ex_gst.value ="Cost Per Weekend";
		document.frm.per_week_cost_aud_ex_gst.value ="Cost Per Week";
	}
}

/*-----show lees more ------*/
var jj = jQuery.noConflict(); 
jj(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 50;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "Show more >";
    var lesstext = "Show less";
    

    jj('.more').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 
            jj(this).html(html);
        }
 
    });
 
    jj(".morelink").click(function(){
        if(jj(this).hasClass("less")) {
            jj(this).removeClass("less");
            jj(this).html(moretext);
        } else {
            jj(this).addClass("less");
            jj(this).html(lesstext);
        }
        jj(this).parent().prev().toggle();
        jj(this).prev().toggle();
        return false;
    });
});
/*-----end show lees more ------*/


  </script>
