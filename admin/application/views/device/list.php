<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from wrappixel.com/demos/admin-templates/material-pro/material/table-data-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Dec 2017 12:51:16 GMT -->
<head>
<!-- You can change the theme colors from here -->
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="fix-header card-no-border">
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
  <svg class="circular" viewBox="25 25 50 50">
    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
  </svg>
</div>

<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">
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
    <h3 class="text-themecolor m-b-0 m-t-0">Gear Description List</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item active">Gear Description List</li>
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
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Gear Description List</h4>
        <h6 class="card-subtitle">(
          <?php if($total_rows>1){ echo $total_rows." Gears"; }else{ echo $total_rows." Gears"; }?>
          )</h6>
        <div class="box-header with-border">
          <h3 class="box-title">Search by:</h3>
          <div class="panel-body">
            <div class="has-feedback">
              <form class="form-inline example example53" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>gear_desc">
                <div class="form-group col-md-12 no_padding">
                  <!--<input type="text" class="" name="gear_category_type" placeholder="Gear Category Type" value="">-->
                  <input type="text" class="" name="model" placeholder="Model" value="<?php echo $model; ?>">
                  <input type="text" class="" name="gear_name" placeholder="Gear Name" value="<?php echo $gear_name; ?>" >
				   <select type="text" class="" name="ks_gear_type_id" placeholder="Gear Type" value=""  style="padding: 10px;font-size: 17px;border: 1px solid #007fb4;float: left;width: 25% !important;background: #f1f1f1;margin-right: 5px !important;">
					<option value="">-- Select Type--</option>
					<?php foreach($type AS $types  ){ ?>
					<option value="<?php echo $types->ks_gear_type_id; ?>"  <?php if($types->ks_gear_type_id == $ks_gear_type_id){  echo "selected" ; } ?>  ><?php echo $types->ks_gear_type_name; ?></option>	
					<?php } ?>
				   </select>
				    <select type="text" class="" name="app_user_id" placeholder="" value=""  style="padding: 10px;font-size: 17px;border: 1px solid #007fb4;float: left;width: 25% !important;background: #f1f1f1;margin-right: 5px !important;">
					<option value="">-- Select Users--</option>
					<?php foreach($app_users AS $users  ){ ?>
					<option value="<?php echo $users->app_user_id; ?>"  <?php if($users->app_user_id == $app_user_id){  echo "selected" ; } ?> ><?php echo $users->app_user_first_name.' '.  $users->app_user_last_name; ?></option>	
					<?php } ?>
				   </select>
				   <select type="text" class="" name="ks_category_id" placeholder="" value="" id="ks_category_id" style="padding: 10px;font-size: 17px;border: 1px solid #007fb4;float: left;width: 25% !important;background: #f1f1f1;margin-right: 5px !important;">
					<option value="">-- Select Category--</option>
					<?php foreach($category AS $categories  ){ ?>
					<option value="<?php echo $categories->gear_category_id; ?>" <?php if($categories->gear_category_id == $ks_category_id ){ echo "selected" ; } ?>><?php echo $categories->gear_category_name; ?></option>	
					<?php } ?>
				   </select>
				    <select type="text" class="" name="ks_category_id" placeholder="" value="" id="sub_cat" style="padding: 10px;font-size: 17px;border: 1px solid #007fb4;float: left;width: 25% !important;background: #f1f1f1;margin-right: 5px !important;">
					<option value="">-- Select Category First--</option>
					
					
				   </select>
				   
				   
                  <button type="submit"><i class="fa fa-search"></i></button>
                </div>
                <div class="form-group col-md-2"> <a href="<?php echo base_url();?>gear_desc/add" class="btn btn-primary pull-right add_userbtn">Add Listing &nbsp;<i class="fa fa-plus" aria-hidden="true"></i></a> </div>
                <div class="form-group col-md-2"> <a href="<?php echo base_url();?>gear_desc/downloadCsv" class="btn btn-primary pull-right add_userbtn">Download Csv &nbsp;<i class="fa fa-download" aria-hidden="true"></i></a> </div>
              </form>
              <!-- </div> -->
              <!-- </div> -->
            </div>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <form name="bulk_action_form" action="<?php echo base_url();?>gear_desc/select_delete" method="post" onSubmit="return delete_confirm();"/>
        <div class="box-body no-padding">
          <div class="mailbox-controls">
            <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/>
            <div class="pull-right" align="right"> <?php echo $paginator;?>
              <!-- /.btn-group -->
            </div>

            <!-- /.pull-right -->
          </div>
          <div class="row pull-right">

            <div class="col-md-12">

            <strong>Per page view: </strong>

            <select name="limit" id="limit">

              <option value="100" <?php if($limit==100) echo "selected=selected" ; ?>>100</option>

              <option value="200" <?php if($limit==200) echo "selected=selected" ; ?>>200</option>

              <option value="500" <?php if($limit==500) echo "selected=selected" ; ?>>500</option>

            </select>

            </div>

          </div>
          <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="150%" style="align:center;table-hlayout:fixed;">
              <thead>
                <tr>
                  <th class="font_n"><input type="checkbox" name="select_all" id="select_all"  /></th>
                  <th class="font_n">#</th>
                  <th class="font_n">Gear Name</th>
                  <th class="font_n">Model</th>
                  <th class="font_n">Category</th>
                  <th class="font_n">Per Day Cost</th>
                  <!-- <th class="font_n">Per Weekend Cost</th>
                  <th class="font_n">Per Week Cost</th> -->
                  <th class="font_n">List Details Date</th>
                  <th class="font_n">Status</th>
                  <th class="font_n">Deleted</th>
                  <th class="font_n">Seach Hideen</th>
                  <th class="font_n">Gear Type</th>
                  <th class="font_n">Date Created</th>
                  <th class="font_n">App User</th>

                  <th class="font_n">Date Updated</th>
                  <th class="font_n taC">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if($result->num_rows()>0){
				
				  if($this->input->get('per_page')!='')
				  {
					$i = $this->input->get('per_page')+1;
				  }
				  else
				  {
					$i=1;
				  }
				  foreach($result->result() as $row){
				//	print_r($row);die;
					?>
                <tr>
                  <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->user_gear_desc_id;?>"></td>
                  <td class="mailbox-name"><?php echo $i;?></td>
                  <td class="mailbox-subject"><?php echo $row->gear_name;?></td>
                  <td class="mailbox-subject"><?php echo $row->model_name;?></td>
				  <td class="mailbox-subject"><?php echo $row->gear_category_name;?></td>
                  <!--<td class="mailbox-subject">-->
                  <?php /*$sql9="SELECT gear_display_image FROM ks_user_gear_images WHERE user_gear_desc_id=".$row->user_gear_desc_id;$result9=$this->db->query($sql9);foreach($result9->result() as $row9){*/?>
                  <!--<img src="./Gear Images/-->
                  <?php /*echo $row9->gear_display_image;}*/?>
                  <!--" style="width:100%;height:100%;"></td>-->
                  <td class="mailbox-subject"><?php echo $row->per_day_cost_aud_ex_gst; ?></td>
                 <!--  <td class="mailbox-subject"><?php echo $row->per_weekend_cost_aud_ex_gst; ?></td>
                  <td class="mailbox-subject"><?php echo $row->per_week_cost_aud_ex_gst; ?></td>
                   --><td class="mailbox-subject"><?php echo $row->gear_delisting_date; ?></td>
                  <td class="mailbox-subject"><?php if($row->is_active=='Y'){ echo "Active"; }elseif ($row->is_active=='C') {echo "Closed"; } else { echo "Inactive"; } ?></td>
                  <td class="mailbox-subject"><?php if($row->is_deleted=='Yes'){ echo "Deleted"; }else { echo ""; } ?></td>
                  <td class="mailbox-subject"><?php if($row->gear_hide_search_results=='N'){ echo "Private"; }else { echo "Public"; } ?></td>
                  <td class="mailbox-subject"><?php echo $row->ks_gear_type_name;  ?></td>
                  <td class="mailbox-date"><?php echo $row->create_date; ?></td>
                  <td class="mailbox-subject"><?php echo $row->app_user_first_name.' '.  $row->app_user_last_name; ?></td>
                  <td class="mailbox-date"><?php if($row->update_date==''){ echo "-"; } else { echo $row->update_date; } ?></td>
                  <td class="mailbox-date">
				  <a href="<?php echo base_url();?>gear_desc/view/<?php echo $row->user_gear_desc_id; ?>" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
				  <a href="<?php echo base_url();?>gear_desc/edit/<?php echo $row->user_gear_desc_id; ?>" title="Modify"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
				  <a href="<?php echo base_url();?>gear_desc/upload/<?php echo $row->user_gear_desc_id; ?>" title="Uploaded Image"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>
				  <a onClick="return confirm('Would You Like To Delete This?');" href="<?php echo base_url();?>gear_desc/delete_record/<?php echo $row->user_gear_desc_id; ?>" title="Delete"><i class="fa fa-trash-o"></i></a> </td>
                </tr>
                <?php $i++; }   ?>
                <?php } else {?>
                <tr>
                  <td colspan="14" align="center">No record found</td>
                </tr>
                <?php  }?>
              </tbody>
            </table>
            <!-- /.table -->
          </div>
          <!-- /.mail-box-messages -->
        </div>
        </form>
        <!-- /.box-body -->
        <div class="box-footer no-padding">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <!-- /.btn-group -->
            <div class="pull-right"> <?php echo $paginator;?> </div>
            <!-- /.btn-group -->
          </div>
          <!-- /.pull-right -->
        </div>
      </div>
    </div>
    <!-- /. box -->
  </div>
  <!-- /.col -->
  <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
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

	$(function() {

    $("#limit").change(function() {

        var limit = $('option:selected', this).val();
        // alert(limit);
    window.location.href = "<?php echo base_url() ?>gear_desc/?limit="+limit;

    

    

    });

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
$('#ks_category_id').change( function (){
	var categories = $('#ks_category_id').val();
	
	 $.ajax({
            type:"get",
            url:"<?php echo base_url(); ?>gear_desc/getsubcat/"+categories,
            
            success:function(response){
                
			$("#sub_cat").html(response);
            }
        });
});	

</script>
