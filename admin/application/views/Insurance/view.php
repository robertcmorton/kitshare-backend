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
    <h3 class="text-themecolor m-b-0 m-t-0">InsuranceType Description</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Insurance">Insurance Description List</a></li>
      <li class="breadcrumb-item active">Insurance  Description</li>
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
        <input type="hidden" name="user_gear_desc_id" id="user_gear_desc_id" value="<?php echo $result->ks_insurance_category_type_id; ?>"/>
        <div class="row">
         
          <div class="form-group col-md-4">
            <label for="exampleInputarticle"> Name</label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $result->name; ?>" placeholder=" Name">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-4">
            <label for="exampleInputarticle">Percentage </label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $result->percent; ?>" placeholder="Percentage" readonly="">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-4">
            <label for="exampleInputarticle">Created Date </label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $result->created_date; ?>" placeholder="Created Date" readonly="">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> 
          </div>
          <div class="form-group col-md-12">
              <label for="exampleInputarticle">Description</label>
              <textarea type="text" class="form-control" name="description"  value="" id="serial_number"  placeholder="Description"><?php echo $result->description ; ?></textarea>  
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('description','<span class="text-danger">','</span>'); ?> 
          </div>
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
