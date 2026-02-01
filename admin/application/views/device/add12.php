
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
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
    <h3 class="text-themecolor m-b-0 m-t-0">Add Gear Category</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>gear_desc">Gear Description List</a></li>
      <li class="breadcrumb-item active">Add Gear Category</li>
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
      <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>gear_desc/save" method="post" enctype="multipart/form-data">
        <input type="hidden" id="sub_cat" name="sub_cat" />
        <div class="row">
         
          <div class="form-group col-md-6">
            <label for="exampleInputarticle">Edit Listing</label>
            <input type="text" class="form-control" name="gear_name" id="gear_name" value="<?php echo set_value('gear_name'); ?>" placeholder="Edit Listing">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?> </div>
			<div class="form-group col-md-6">
				<div class="autocomplete" >
              <label for="exampleInputarticle">Manufacturer</label>
              <input type="text" class="form-control manufacturer_id " name="manufacturer_id" id="manufacturer_id" value=""  placeholder="">
                             
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('manufacturer_id','<span class="text-danger">','</span>'); ?> 
			  </div>
            </div>
          
        </div>
        <div class="row">
			 
			<div class="form-group col-md-6">
				<label for="exampleInputEmail1">Model</label>
				<select name="model_id" id="model_id" class="form-control">
				  <option value="">--Select Model--</option>
				  <?php foreach($models as $v){?>
				  <option value="<?php echo $v->model_id; ?>"><?php echo $v->model_name; ?></option>
				  <?php } ?>
				</select>
				<label for="model_id" class="error" style="color:#FF0000;display:none;"></label>
            <?php echo form_error('model_id','<span class="text-danger">','</span>'); ?>
			</div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Category</label>
              <select type="text" class="form-control" name="gear_category_id" id="gear_category_id"  placeholder="">
                  <option>--Select Category--</option>
                  <?php if (!empty($category)) {
                        foreach ($category as  $value) {
                  ?>    
                      <option value="<?php echo $value->gear_category_id; ?>"><?php echo $value->gear_category_name; ?></option>    
                  <?php       }
                  } ?>
                  
              </select>  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('replacement_value_aud_ex_gst','<span class="text-danger">','</span>'); ?> 
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Sub Category</label>
              <select type="text" class="form-control" name="gear_sub_category_id" id="gear_sub_category_id"  placeholder="">
                  <option>--Select Category--</option>
                  
                  
              </select>  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('gear_sub_category_id','<span class="text-danger">','</span>'); ?> 
            </div>
           
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Condition</label>
              <select type="text" class="form-control" name="condition" id="condition"  placeholder="Condition">
                  <option>--Select Condition--</option>
                  <option value="new">New</option>
				  <option value="Like New">Like New</option>
                  <option value="Slightly Worn">Slightly Worn</option>
				  option value="Worn">Worn</option>
              </select>  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('condition','<span class="text-danger">','</span>'); ?> 
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Serial Number</label>
              <input type="text" class="form-control" name="serial_number" id="serial_number"  placeholder="Serial Number">
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('serial_number','<span class="text-danger">','</span>'); ?> 
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Type</label>
              <select type="text" class="form-control" name="ks_gear_type_id" id="gear_type"  placeholder="">
                <option>--Select Gear Type--</option>  
                <?php  
                if (!empty($gear_type)) {
                        foreach ($gear_type as  $value) {
                ?>          
                <option value="<?php echo $value->ks_gear_type_id ; ?>"><?php echo $value->ks_gear_type_name ; ?></option>  
                <?php         }
                
                } ?>
               
              </select>  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('gear_type','<span class="text-danger">','</span>'); ?> 
            </div>


   
            <div class=" form-group col-md-6" id="show_div" style="display:none" >
                 <label for="exampleInputarticle">Select Gears</label>
                <select class="js-example-basic-multiple "  id="dates-field2" name="geears_ids[]" multiple="multiple">

               </select>
              
            </div>
            <div class=" form-group col-md-6" id="show_div" >
                 <label for="exampleInputarticle">Gears</label>
              </br>
                 <input class="form-control" type="file" name="images[]" multiple="multiple">
                  
            </div>
        </div>  
		<div class="row">
		<div class="form-group col-md-6">
            <label for="exampleInputarticle">Replacement Value excluding GST</label>
            <input type="text" class="form-control" name="replacement_value_aud_ex_gst" id="replacement_value_aud_ex_gst" value="<?php echo set_value('replacement_value_aud_ex_gst'); ?>" placeholder="Replacement Value excluding GST">
            <label for="replacement_value_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('replacement_value_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
		</div>
		<div class="form-group col-md-4">
            <label for="exampleInputarticle">Replacement Value including GST</label>
            <input type="text" class="form-control" name="replacement_value_aud_inc_gst" id="replacement_value_aud_inc_gst" value="<?php echo set_value('replacement_value_aud_inc_gst'); ?>" placeholder="Replacement Value including GST">
            <label for="replacement_value_aud_inc_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('replacement_value_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
        <div class="row">
          <div class="form-group col-md-6" oninput="getprice()">
            <label for="exampleInputarticle">Per Day Cost excluding GST</label>
            <input type="text" class="form-control" name="per_day_cost_aud_ex_gst" id="per_day_cost_aud_ex_gst" value="<?php echo set_value('per_day_cost_aud_ex_gst'); ?>" placeholder="Cost Per Day excluding GST">
            <label for="per_day_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_day_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
			<div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Day Cost including GST</label>
            <input type="text" class="form-control" name="per_day_cost_aud_inc_gst" id="per_day_cost_aud_inc_gst" value="<?php echo set_value('per_day_cost_aud_inc_gst'); ?>" placeholder="Cost Per Day including GST">
            <label for="per_day_cost_aud_inc_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_day_cost_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="row">
		  <div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Weekend Cost excluding GST</label>
            <input type="text" class="form-control" name="per_weekend_cost_aud_ex_gst" id="per_weekend_cost_aud_ex_gst" value="" placeholder="Cost Per Weekend excluding GST" disabled>
            <label for="per_weekend_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_weekend_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
			<div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Weekend Cost including GST</label>
            <input type="text" class="form-control" name="per_weekend_cost_aud_inc_gst" id="per_weekend_cost_aud_inc_gst" value="" placeholder="Cost Per Weekend including GST" disabled>
            <label for="per_weekend_cost_aud_inc_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_weekend_cost_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
		  </div>
		  <div class="row">
          <div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Week Cost excluding GST</label>
            <input type="text" class="form-control" name="per_week_cost_aud_ex_gst" id="per_week_cost_aud_ex_gst" value="" placeholder="Cost Per Weekend excluding GST" disabled>
            <label for="per_week_cost_aud_ex_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_week_cost_aud_ex_gst','<span class="text-danger">','</span>'); ?> </div>
			<div class="form-group col-md-6">
            <label for="exampleInputarticle">Per Week Cost including GST</label>
            <input type="text" class="form-control" name="per_week_cost_aud_inc_gst" id="per_week_cost_aud_inc_gst" value="" placeholder="Cost Per Weekend including GST" disabled>
            <label for="per_week_cost_aud_inc_gst" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('per_week_cost_aud_inc_gst','<span class="text-danger">','</span>'); ?> </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="exampleInputEmail1">Owner Remark </label>
              <textarea class="textarea_editor form-control" rows="10" name="owner_remark" id="owner_remark" value="<?php echo set_value('owner_remark'); ?>"></textarea>
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
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">App Users</label>
              <select name="app_user_id" id="app_user_id" class="form-control">
                <option value="">--Select App User--</option>
                <?php foreach($users as $u){?>
                <option value="<?php echo $u->app_user_id; ?>"><?php echo $u->app_username; ?></option>
                <?php } ?>
              </select>
              <label for="app_user_id" class="error" style="color:#FF0000;display:none;"></label>
              <?php echo form_error('app_user_id'); ?></div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputImage">Status</label>
              <select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>"   onChange="getstatus(this.value);">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
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

	j('#owner_remark').wysihtml5();
	j('#gear_desc_1').wysihtml5();
	j('#gear_desc_2').wysihtml5();

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
j('#manufacturer_id').on('focusout',function(){
  var type=  j('#manufacturer_id').val();
	//	alert(type);
    j.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>gear_desc/getmodalsList",
            data :{ manufacturer_id :type},
            success:function(response){
				console.log(response);
           j("#model_id").html(response);
            }
        });
    
});
j('#gear_type').on('change',function(){
  var type=  j('#gear_type').val();
  if(type == "3"){
    $('#show_div').css('display','none');
  }else{
    j.ajax({
            type:"get",
            url:"<?php echo base_url(); ?>gear_desc/getgearsList",
            
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
  </script>
  <script>
function autocomplete(inp, arr,id_name) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
			  document.getElementById(id_name).setAttribute('value',inp.value);
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}
/*An array containing all the country names in the world:*/
//var countries = ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua & Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia & Herzegovina","Botswana","Brazil","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central Arfrican Republic","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cuba","Curacao","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauro","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","North Korea","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre & Miquelon","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","St Kitts & Nevis","St Lucia","St Vincent","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad & Tobago","Tunisia","Turkey","Turkmenistan","Turks & Caicos","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States of America","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe"];
  var countries = <?php echo json_encode($manufacturers) ?>;
 // var country = countries.replace(/'/g, "");
// var  countries = country ;
//console.log(countries);

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete(document.getElementById("manufacturer_id"), countries,document.querySelector('.manufacturer_id').id);


var modals = <?php echo json_encode($models1) ?>;
autocomplete(document.getElementById("model_id"), modals);
</script>


  <style type="text/css">
.select2-selection--multiple{

  width: 500px
}
.autocomplete-items{
  overflow-y: scroll;
  height: 100px;
}

</style>  
