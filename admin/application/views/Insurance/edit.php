
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
    <h3 class="text-themecolor m-b-0 m-t-0">InsuranceType Edit </h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Insurance">InsuranceType List</a></li>
      <li class="breadcrumb-item active">InsuranceType Edit </li>
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
      <form class="form-material m-t-40" name="frm" action="<?php echo base_url();?>Insurance/updateInsurance" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ks_insurance_category_type_id" id="ks_insurance_category_type_id" value="<?php echo $result->ks_insurance_category_type_id; ?>"/>
        <div class="row">
              
          <div class="form-group col-md-6">
            <label for="exampleInputarticle">Name</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo $result->name; ?>" placeholder="Edit Listing">
            <label for="gear_name" class="error" style="color:#FF0000; display:none;"></label>
            <?php echo form_error('gear_name','<span class="text-danger">','</span>'); ?>
          </div>
          <!-- <div class="form-group col-md-6">
              <label for="exampleInputarticle">Percentage</label>
              <input type="text" class="form-control" name="percent"  value="<?php echo $result->percent ; ?>" id="serial_number"  placeholder="Serial Number">
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('serial_number','<span class="text-danger">','</span>'); ?> 
          </div> -->
          <?php if($this->uri->segment(3)== '1'){ ?> 
          <div class="form-group col-md-3">
              <label for="exampleInputarticle">Max Cart</label>
              <input  type="text" class="form-control" name="max_replacement_value"   id="max_replacement_value"  value="<?php echo $settings->max_replacement_value;?>" placeholder="Max Cart">
                
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('serial_number','<span class="text-danger">','</span>'); ?> 
          </div>
          <?php } ?>
          <div class="form-group col-md-9">
              <label for="exampleInputarticle">Description</label>
              <textarea type="text" class="form-control" name="description"  value="" id="serial_number"  placeholder="Description"><?php echo $result->description ; ?></textarea>  
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('description','<span class="text-danger">','</span>'); ?> 
          </div>
          <div class="form-group col-md-3">
              <label for="exampleInputarticle">Status</label>
              <select  type="text" class="form-control" name="status"   id="status"  placeholder="Status">
                <option value="Active" >-- Select A Status--</option>
                <option value="Active" <?php if( $result->status == "Active" ){echo "selected" ; } ?> >Active</option>
                <option value="InActive" <?php if( $result->status == "InActive" ){echo "selected" ; } ?> >InActive</option>
              </select>  
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('serial_number','<span class="text-danger">','</span>'); ?> 
          </div>
           
        </div> 
        <?php if($this->uri->segment(3)== '1'){ ?>  
            <div class="form-group col-md-12">
              <h3>Tier(s)  </h3>
            </div>
           
         <?php if(!empty($tiers)){
                  $counterSrc = 0 ;
                    foreach ($tiers as  $value) {
            ?>  
            <div class="row" id="addNew<?php echo $counterSrc;?>">
              <input type="hidden" value="<?php echo $value->tiers_id; ?>" id="tiers_id_<?php echo $counterSrc;?>" name="tiers_id[]" >    
              <div class="form-group col-md-3">
                <label for="exampleInputarticle">Initial Day rate</label>
                <input  type="text" class="form-control" name="initial_value[]"   id="initial_value" value="<?php echo $value->initial_value;  ?>"  placeholder="Initial Day rate">
                <label for="" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('initial_value','<span class="text-danger">','</span>'); ?> 
              </div>
              <div class="form-group col-md-3">
                <label for="exampleInputarticle">End  Day rate</label>
                <input  type="text" class="form-control" name="end_value[]"   id="end_value" value="<?php echo $value->end_value;  ?>"   placeholder="End Day rate">
                <label for="" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('end_value','<span class="text-danger">','</span>'); ?> 
              </div> 
              <div class="form-group col-md-3">
                <label for="exampleInputarticle">Percentage(%)</label>
                <input  type="text" class="form-control" name="tiers_percentage[]"   id="tiers_percentage"  value="<?php echo $value->tiers_percentage;  ?>"  placeholder="Percentage">
                <label for="" class="error" style="color:#FF0000; display:none;"></label>
                <?php echo form_error('tiers_percentage','<span class="text-danger">','</span>'); ?> 
              </div> 
              <?php if ($counterSrc == 0 ) {?>
                 <div class="form-group col-md-3">
                 </div> 
              <?php }else{ ?>
              <div class="form-group col-md-3">
                <a onclick="DeleteTiers(<?php echo $counterSrc; ?>)" class="btn btn-danger" style="color: white;">  Delete</a>
              </div>
              <?php }?>
            </div>      
            <?php $counterSrc++; } }else{  $counterSrc = 0;?>
        <div class="row">  
          <div class="form-group col-md-3">
              <label for="exampleInputarticle">Initial Day rate</label>
              <input  type="text" class="form-control" name="initial_value[]"   id="initial_value"  placeholder="Initial Day rate">
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('initial_value','<span class="text-danger">','</span>'); ?> 
          </div>
          <div class="form-group col-md-3">
              <label for="exampleInputarticle">End Day rate</label>
              <input  type="text" class="form-control" name="end_value[]"   id="end_value"  placeholder="End  Day rate">
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('end_value','<span class="text-danger">','</span>'); ?> 
          </div>
          <div class="form-group col-md-3">
              <label for="exampleInputarticle">Percentage(%)</label>
              <input  type="text" class="form-control" name="tiers_percentage[]"   id="tiers_percentage"  placeholder="Percentage">
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('tiers_percentage','<span class="text-danger">','</span>'); ?> 
          </div>
           
        </div>
        <?php }?>
          <div  id="addReserve">

          </div>
			  
    
      <div class="form-group col-md-12">
          <a onclick="appendText()" class="btn btn-info" style="color: white;">  Add</a>
          <input type="hidden" name="counterSrc" id="counterSrc" value="<?php if($counterSrc!=''){echo $counterSrc + 1 ;}else{echo '0';}  ?>" />
      </div>
      
        
      <?php  }?> 
        
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

function appendText () {
  var counterSrc=$('#counterSrc').val();
  counterSrc++;
  var html = '<div class="row"  id="addNew'+counterSrc+'"><div class="form-group col-md-3"><label for="exampleInputarticle">Initial Day rate</label><input  type="text" class="form-control" name="initial_value[]"   id="initial_value"  placeholder="Initial Day rate"><label for="" class="error" style="color:#FF0000; display:none;"></label></div><div class="form-group col-md-3"><label for="exampleInputarticle">End Day rate</label><input  type="text" class="form-control" name="end_value[]"   id="end_value"  placeholder="End Day rate"><label for="" class="error" style="color:#FF0000; display:none;"></label></div><div class="form-group col-md-3"><label for="exampleInputarticle">Percentage(%)</label><input  type="text" class="form-control" name="tiers_percentage[]"   id="tiers_percentage"  placeholder="Percentage"><label for="" class="error" style="color:#FF0000; display:none;"></label></div><div class="form-group col-md-3"><a onclick="rmvSrc3('+counterSrc+')" class="btn btn-danger" style="color: white;">  Delete</a></div></div></div></div>';
  $("#addReserve").append(html);
  $('#counterSrc').val(counterSrc);
}
function rmvSrc3(param){
  $('#addNew'+param+'').remove();
  var counterSrc=$('#counterSrc').val();
  counterSrc--;
  $('#counterSrc').val(counterSrc);
}
function DeleteTiers (counterSrc) {
  var tiers_id =$("#tiers_id_"+counterSrc +"").val();
  $.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>Insurance/DeleteTiers",
            data:{tiers_id:tiers_id},
            success:function(response){
                rmvSrc3(counterSrc);
            }
        });
}

  </script>
  

  
