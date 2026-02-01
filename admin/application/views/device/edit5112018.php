
<link href="<?php echo base_url('assets/css/jquery.typeahead.css'); ?>" rel="stylesheet" />
<script src="<?php echo base_url('assets/js/jquery.typeahead.js'); ?>"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script> 
<!--<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>  -->
<script src="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<link href="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
<?php  //print_r($unavailable_data);?>
<style>
  #gear_category_id {
    border: none;
    border-radius: 0px;
    padding-left: 0px;
    border-bottom: 1px solid #d9d9d9; }
  #gear_category_name {
    border: none;
    border-radius: 0px;
    padding-left: 0px;
    border-bottom: 1px solid #d9d9d9; }
  #is_active {
    border: none;
    border-radius: 0px;
    padding-left: 0px;
    border-bottom: 1px solid #d9d9d9; }
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
    <h3 class="text-themecolor m-b-0 m-t-0">Edit Gear </h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>gear_desc">Gear Description List</a></li>
      <li class="breadcrumb-item active">Edit Gear </li>
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
      <h4 class="card-title">Edit</h4>
      <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>gear_desc/update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="user_gear_desc_id" id="user_gear_desc_id" value="<?php echo $result[0]->user_gear_desc_id; ?>"/>
        <input type="hidden" id="sub_cat" name="sub_cat" />
        <div class="row">
              
          <div class="form-group col-md-6">
            <label for="exampleInputarticle">Edit Listing</label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo $result[0]->gear_name; ?>" placeholder="Edit Listing">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> </div>
                       
              
       <div class=" col-md-6">
                  
              <label for="exampleInputarticle">Manufacturer</label>
                <!-- <div class="typeahead__container">
                  <div class="typeahead__field">
                      <div class="typeahead__query">
                      <input class="js-typeahead-manufaturer form-control"
                                 name="manufacturer_name"  value="<?php echo $result[0]->manufacturer_name; ?>" id="manufacturer_name" type="search" autofocus  autocomplete="off">
                      <label for="" class="error" style="color:#FF0000; display:none;"></label>
                      <?php echo form_error('manufacturer_id','<span class="text-danger">','</span>'); ?> 


                      </div>
                  </div>
                </div> -->
                 <select name="manufacturer_name" id="manufacturer_name" class="form-control">
                  <option value="">--Select Manufacturer--</option>
                  <?php foreach($manufacturers_array as $v){?>
                  <option value="<?php echo $v->manufacturer_name ; ?>"<?php if( $v->manufacturer_name ==  $result[0]->manufacturer_name){echo "selected";} ?>  ><?php echo $v->manufacturer_name  ; ?></option>
                  <?php } ?>
                </select>
          </div>     
          
        </div>
        <div class="row">
       <div class=" col-md-6">
              <label for="exampleInputEmail1">Model</label>        
               <!--  <div class="typeahead__container">
                    <div class="typeahead__field">
                        <div class="typeahead__query">
                            <input class="js-typeahead-modals form-control"  name="modal_name" id="modal_name"  value="<?php echo $result[0]->model_name; ?>" type="search" autocomplete="off">
                              <label for="model_id" class="error" style="color:#FF0000;display:none;"></label>
                            <?php echo form_error('model_id','<span class="text-danger">','</span>'); ?>
        
                        </div>
                    </div>
                </div> -->

                  <select name="modal_name" id="modal_name" class="form-control">
 
                  <option value="">--Select Model--</option>
                  <?php foreach($models as $v){?>
                  <option value="<?php echo $v->model_name ; ?>"<?php if( $v->model_name ==  $result[0]->model_name){echo "selected";} ?>  ><?php echo $v->model_name  ; ?></option>
                  <?php } ?>
                </select>
              </div>  
      
            <div class=" col-md-6">
              <label for="exampleInputarticle">Category</label>
             <!--  <div class="typeahead__container">
                    <div class="typeahead__field">
                        <div class="typeahead__query">
                            <input class="js-typeahead-category form-control"  name="category_name" id="modal_name"  value="<?php echo $result[0]->gear_category_name; ?>" type="search" autocomplete="off">
                              <label for="category_name" class="error" style="color:#FF0000;display:none;"></label>
                            <?php echo form_error('category_name','<span class="text-danger">','</span>'); ?>
        
                        </div>
                    </div>
                </div>  -->
                <select name="category_name" id="gear_category_id" class="form-control">
                      <option value="">--Select Category--</option>
                      <?php foreach($category as $v){?>
                      <option value="<?php echo $v->gear_category_name ; ?>" <?php if($v->gear_category_name == $result[0]->gear_category_name){  echo "selected";  } ?>  ><?php echo $v->gear_category_name  ; ?></option>
                      <?php } ?>
                  </select>

              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('replacement_value_aud_ex_gst','<span class="text-danger">','</span>'); ?> 
            </div>
            <div class=" col-md-6">
              <label for="exampleInputarticle">Sub Category</label>
              <!-- <div class="typeahead__container">
                    <div class="typeahead__field">
                        <div class="typeahead__query">
                            <input class="js-typeahead-sub-category form-control"  name="sub_category_name" id="sub_category_name" value="<?php echo  $result[0]->sub_category_name?>"  type="search" autocomplete="off">
                              <label for="sub_category_name" class="error" style="color:#FF0000;display:none;"></label>
                            <?php echo form_error('sub_category_name','<span class="text-danger">','</span>'); ?>
        
                        </div>
                    </div>
                </div> -->
                <select name="sub_category_name" id="gear_sub_category_id" class="form-control">
                    category_sub
                    category_sub
                      <option value="">--Select Category--</option>
                      <?php foreach($category_sub as $v){?>
                      <option value="<?php echo $v->gear_category_name ; ?>" <?php if($v->gear_category_name == $result[0]->sub_category_name){  echo "selected";  } ?>  ><?php echo $v->gear_category_name  ; ?></option>
                      <?php } ?>
                </select>  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('gear_sub_category_id','<span class="text-danger">','</span>'); ?> 
            </div>
           
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Condition</label>
              <select type="text" class="form-control" name="condition" id="condition"  placeholder="Condition">
                  <option>--Select Condition--</option>
                  <option value="new" <?php if( $result[0]->ks_user_gear_condition =='new'){echo "selected" ;}  ?>>New</option>
                  <option value="Like New" <?php if( $result[0]->ks_user_gear_condition =='Like New'){echo "selected" ;}  ?> >Like New</option>
                  <option value="Slightly Worn" <?php if( $result[0]->ks_user_gear_condition =='Slightly Worn'){echo "selected" ;}  ?> >Slightly Worn</option>
                  <option value="worn" <?php if( $result[0]->ks_user_gear_condition =='worn'){echo "selected" ;}  ?> >Worn</option>
              </select>  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('condition','<span class="text-danger">','</span>'); ?> 
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Serial Number</label>
              <input type="text" class="form-control" name="serial_number "  value="<?php echo $result[0]->serial_number ; ?>" id="serial_number"  placeholder="Serial Number">
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('serial_number','<span class="text-danger">','</span>'); ?> 
            </div>
			 <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">App Users</label>
              <select name="app_user_id" id="app_user_id" class="form-control">
                <option value="">--Select App User--</option>
                <?php foreach($users as $u){?>
                <option value="<?php echo $u->app_user_id; ?>" <?php if($u->app_user_id==$result[0]->app_user_id) echo "selected='selected'";?> ><?php echo $u->app_username; ?></option>
                <?php } ?>
              </select>
              <label for="app_user_id" class="error" style="color:#FF0000;display:none;"></label>
              <?php echo form_error('app_user_id'); ?></div>
          </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Type</label>
              <select type="text" class="form-control" name="ks_gear_type_id" id="gear_type"  placeholder="">
                <option>--Select Gear Type--</option>  
                <?php  
                if (!empty($gear_type)) {
                        foreach ($gear_type as  $value) {
                ?>          
                <option value="<?php echo $value->ks_gear_type_id ; ?>" <?php if( $result[0]->ks_gear_type_id == $value->ks_gear_type_id){echo "selected" ;}  ?>><?php echo $value->ks_gear_type_name ; ?></option>  
                <?php         }
                
                } ?>
               
              </select>  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('gear_type','<span class="text-danger">','</span>'); ?> 
            </div>


   
            <!-- <div class=" form-group col-md-6" id="show_div" >
                 <label for="exampleInputarticle">Select Gears</label>
                <select  class="js-example-basic-multiple form-group  "  id="dates-field2" name="geears_ids[]" multiple="multiple">
                    <?php if(!empty($gear_list)){
                      foreach ($gear_list as $value) {
                        $accessories =  explode(',',$result[0]->accessories);
          ?>    
          <option value="<?php  echo $value->user_gear_desc_id ;?>" <?php if(in_array($value->user_gear_desc_id, $accessories)){ echo "selected" ; } ?> ><?php  echo $value->gear_name ;?></option>
                  <?php       } 

                  } ?>
               </select>
              
            </div> -->
            <div class=" form-group col-md-6" id="show_div"  >
                 <label for="exampleInputarticle">Add Accessories"</label>
               </br>
                <select class="js-example-basic-multiple "  id="dates-field2" name="geears_ids[]" multiple="multiple">
                    <?php if(!empty($gear_list)){
                      foreach ($gear_list as $value) {
                        $accessories =  explode(',',$result[0]->accessories);
          ?>    
          <option value="<?php  echo $value->user_gear_desc_id ;?>" <?php if(in_array($value->user_gear_desc_id, $accessories)){ echo "selected" ; } ?> ><?php  echo $value->gear_name ;?></option>
                  <?php       } 

                  } ?>
               </select>
              
            </div>
            <div class=" form-group col-md-6" id="show_div" >
                 <label for="exampleInputarticle">ADD Photos</label>
              </br>
                 <input class="form-control" type="file"  id="dates-field2" name="images[]" multiple="multiple">
                  
            </div>
			 <div class=" form-group col-md-6"  >
                 <label for="exampleInputarticle">Google 360 image link</label>
              </br>
                 <input class="form-control" type="text" name="google_360_link"  value="<?php  echo $result[0]->google_360_link ?>">
                  
            </div>
			<?php if(empty($unavailable_data)){ ?>
			<div class=" form-group col-md-4"  >
                 <label for="exampleInputarticle">UnAvailable Dates </label>
              </br>
                 <input class="form-control datepicker " type="text" name="unavailble_dates[]" placeholder="UnAvailable Dates" multiple="">
				 
                  
            </div>
			<?php }else{ 
			
			foreach($unavailable_data AS $unavailables){
				//print_r($unavailables);
				 $from_date =  date('m/d/Y', strtotime($unavailables->unavailable_from_date));
				 $to_date =  date('m/d/Y', strtotime($unavailables->unavailable_to_date));
			?>
				<div class=" form-group col-md-4"  >
                 <label for="exampleInputarticle">UnAvailable Dates </label>
              </br>
                 <input class="form-control datepicker " value="<?php  echo $from_date.' - '.$to_date;?>" type="text" name="unavailble_dates[]" placeholder="UnAvailable Dates" multiple="">
				  
                  <input type="hidden" value="<?php echo $unavailables->ks_gear_unavailable_id ; ?>"  name="ks_gear_unavailable_id[]" >
				  <a href="<?php echo base_url().'gear_desc/deleteUnacailable/'.$unavailables->ks_gear_unavailable_id.'/'.$result[0]->user_gear_desc_id;; ?>" >Delete</a>
            </div>
			<?php } } ?> 
			<div class="form-group col-md-2">
                    <div class="form-group"> <br>
                      <a onclick="appendText4()" class=""> <i class="fa fa-plus"></i> Add</a>
                      
                    </div>
             </div>
        </div> 
		<div class="row" id="adddates" >
			
		</div>			
    <div class="row">
    <div class="form-group col-md-6">
            <label for="exampleInputarticle">Replacement Value excluding GST</label>
            <input type="text" class="form-control" name="replacement_value_aud_ex_gst" id="replacement_value_aud_ex_gst" value="<?php echo $result[0]->replacement_value_aud_ex_gst; ?>" placeholder="Replacement Value excluding GST">
            <label for="replacement_value_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('replacement_value_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
    
          <div class="form-group col-md-6" oninput="getprice()">
            <label for="exampleInputarticle">Per Day Cost excluding GST</label>
            <input type="text" class="form-control" name="per_day_cost_aud_ex_gst" id="per_day_cost_aud_ex_gst" value="<?php  echo $result[0]->per_day_cost_aud_ex_gst; ?>" placeholder="Cost Per Day excluding GST">
            <label for="per_day_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_day_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> 
            </div>
        </div>
     <!--  <div class="row">
      <div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Weekend Cost excluding GST</label>
            <input type="text" class="form-control" name="per_weekend_cost_aud_ex_gst" id="per_weekend_cost_aud_ex_gst" value="<?php echo $result[0]->per_weekend_cost_aud_ex_gst;   ?>" placeholder="Cost Per Weekend excluding GST" >
            <label for="per_weekend_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_weekend_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Week Cost excluding GST</label>
            <input type="text" class="form-control" name="per_week_cost_aud_ex_gst" id="per_week_cost_aud_ex_gst" value="<?php echo $result[0]->per_week_cost_aud_ex_gst ?>" placeholder="Cost Per Weekend excluding GST" >
            <label for="per_week_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_week_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
        </div> -->
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="exampleInputEmail1">Owner Remark </label>
              <textarea class="textarea_editor form-control" rows="10" name="owner_remark" id="owner_remark" value="<?php echo set_value('owner_remark'); ?>"><?php echo $result[0]->owners_remark;?></textarea>
              <?php echo form_error('owner_remark','<span class="text-danger">','</span>'); ?>
              <label for="owner_remark" class="error" style="color:#FF0000; display:none;"></label>
            </div>
          </div>
        </div>
       <!-- <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="gear_desc_1">Gear Description 1</label>
              <textarea class="textarea_editor form-control" rows="10" name="gear_desc_1" id="gear_desc_1" value="<?php echo set_value('gear_desc_1'); ?>"></textarea>
              <?php echo form_error('gear_desc_1','<span class="text-danger">','</span>'); ?>
              <label for="gear_desc_1" class="error" style="color:#FF0000; display:none;"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="exampleInputEmail1">Gear Description 2</label>
              <textarea class="textarea_editor form-control" rows="10" name="gear_desc_2" id="gear_desc_2" value="<?php echo set_value('gear_desc_2'); ?>"></textarea>
              <?php echo form_error('gear_desc_2','<span class="text-danger">','</span>'); ?>
              <label for="gear_desc_2" class="error" style="color:#FF0000; display:none;"></label>
            </div>
          </div>
        </div>
    -->
        <div class="row">
          <div class=" form-group col-md-6"  >
                 <label for="exampleInputarticle">Add Address</label>
				 </br>
                <select class="js-example-basic-multiple "  id="dates-field2_address" name="address_id[]" multiple="multiple">
				<?php if(!empty($address_array)){ 
					print_r($address_array);
						foreach($address_array As $address){
							  $address_sacef =  explode(',',$result[0]->ks_gear_address_id);
				?>		
					<option value="<?php echo $address->user_address_id ?>"  <?php if(in_array($address->user_address_id, $address_sacef)){ echo "selected" ; } ?>  ><?php echo $address->street_address_line1  .' ' . $address->ks_state_name. ' ' . $address->suburb_name . $address->postcode ;?></option>; 				
				<?php 		}
				} ?>	
               </select>
              
            </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputImage">Status</label>
              <select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>"   onChange="getstatus(this.value);">
                <option value="Active" <?php if($result[0]->is_active=="Y") echo "selected='selected'";?> >Active</option>
                <option value="Inactive" <?php if($result[0]->is_active=="N") echo "selected='selected'";?> >Inactive</option>
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
<script type="text/javascript">
        var modals = <?php echo json_encode($models1) ?>;
        var countries = <?php echo json_encode($manufacturers) ?> ;
        var category_data  = <?php echo json_encode($category_data) ?> ;
        var sub_category_data  = <?php echo json_encode($category_sub__data) ?> ;
       
        typeof $.typeahead === 'function' && $.typeahead({
            input: ".js-typeahead-manufaturer",
            minLength: 1,
            order: "asc",
            emptyTemplate: "no result for {{query}}",
            source: {
                    data: countries
            },
                   
        });
        typeof $.typeahead === 'function' && $.typeahead({
            input: ".js-typeahead-category",
            minLength: 1,
            order: "asc",
            emptyTemplate: "no result for {{query}}",
            source: {
                    data: category_data
            },
                   
        });
          typeof $.typeahead === 'function' && $.typeahead({
            input: ".js-typeahead-sub-category",
            minLength: 1,
            order: "asc",
            emptyTemplate: "no result for {{query}}",
            source: {
                    data: sub_category_data
            },
                   
        });


        typeof $.typeahead === 'function' && $.typeahead({
            input: ".js-typeahead-modals",
            minLength: 1,
            searchOnFocus: true,
            blurOnTab: false,
            emptyTemplate: 'no result found',
            source: {
                data: modals,
            },
            debug: true
        });

    </script>
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
j('#manufacturer_name').on('change',function(){
    var manufacturer_id =  j('#manufacturer_name').val();
  
    j.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>gear_desc/getmodalsList",
            data:{manufacturer_id:manufacturer_id},
            success:function(response){
                console.log(response);
        j("#modal_name").html(response);
            }
        });

});
j(".datepicker").daterangepicker({
  minDate: moment().subtract(2, 'years'),
  callback: function (startDate, endDate, period) {
    $(this).val(startDate.format('L') + ' – ' + endDate.format('L'));
  }
});
j('#gear_category_id').on('change',function(){
  var category_id =  j('#gear_category_id').val();
  //alert(category_id);
    j.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>gear_desc/getsubcategory",
            data:{category_id:category_id},
            success:function(response){
                console.log(response);
        j("#gear_sub_category_id").html(response);
            }
        });
});
j('#app_user_id').on('change' , function(){
	//alert("hello");
	var app_user_id =   $('#app_user_id').val();
	//alert(app_user_id);
	j.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>gear_desc/getAppuserAddress",
            data:{app_user_id:app_user_id},
            success:function(response){
                console.log(response);
       // j("#gear_sub_category_id").html(response);
	   j("#dates-field2_address").html(response);
            }
        });
		
	
});

j('#gear_type').on('change',function(){
  var type=  j('#gear_type').val();
   var app_user_id=  j('#app_user_id').val();
  if(type == "3"){
    $('#show_div').css('display','none');
  }else{
    j.ajax({
            type:"get",
            url:"<?php echo base_url(); ?>gear_desc/getgearsList"+app_user_id,
            
            success:function(response){
            j("#dates-field2").html(response);
            }
        });
    $('#show_div').css('display','block');
  };
});
function findmodel(x){
        j.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>gear_desc/ajax",
            data:{data:x},
            success:function(response){
                //alert(x);
        j("#model").html(response);
            }
        });
}
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

j(document).ready(function() {
    j('.js-example-basic-multiple').select2();
});
function appendText4(){
	
	
	j("#adddates").append('<div class=" form-group col-md-4"  ><label for="exampleInputarticle">UnAvailable Dates </label></br><input class="form-control datepicker " type="text" name="unavailble_dates[]" placeholder="UnAvailable Dates" multiple=""></div>');
	j(".datepicker").daterangepicker({
	  minDate: moment().subtract(2, 'years'),
	  callback: function (startDate, endDate, period) {
		j(this).val(startDate.format('L') + ' – ' + endDate.format('L'));
	  }
	});
	
	
}
  </script>
  

  <style type="text/css">

.select2-selection--multiple{

  width: 500px
}

span.select2.select2-container.select2-container--default{
	    width: 500px !important
}

</style>  
