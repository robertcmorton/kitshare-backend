<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from wrappixel.com/demos/admin-templates/material-pro/material/table-data-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Dec 2017 12:51:16 GMT -->
<head>
<!-- You can change the theme colors from here -->
<link href="css/colors/blue.css" id="theme" rel="stylesheet">
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
      <h3 class="text-themecolor m-b-0 m-t-0">
         <?php  if($this->uri->segment(4) =="mangelist") {
           echo "User's Gears Listing";
          }elseif ($this->uri->segment(4) =="owner") {
            echo "User's Rents Listing";
          }elseif (($this->uri->segment(4) =="renter")) {
            echo "User's Rented Gears" ;
          } ?></h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
		<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_users">App Users List</a></li>
        <li class="breadcrumb-item active">
          <?php  if($this->uri->segment(4) =="mangelist") {
           echo "User's Gears Listing";
          }elseif ($this->uri->segment(4) =="owner") {
            echo "User's Rents Listing";
          }elseif (($this->uri->segment(4) =="renter")) {
            echo "User's Rented Gears" ;
          } ?>
        </li>
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
         <ul class="nav nav-tabs profile-tab" role="tablist">
            <li class="nav-item"> <a class="nav-link <?php  if($this->uri->segment(4) =="mangelist"){echo  "active";} ?>"href="#mangelist" onclick="PageReload('mangelist')"  role="tab">Manage Listing</a> </li>
            <li class="nav-item"> <a class="nav-link <?php  if($this->uri->segment(4) =="owner"){echo  "active";} ?>"  href="#owner"  onclick="PageReload('owner')" role="tab">Owner Listing</a> </li>
            <li class="nav-item"> <a class="nav-link <?php  if($this->uri->segment(4) =="renter"){echo  "active";} ?>" href="#renter"  onclick="PageReload('renter')" role="tab">Renter Listing</a> </li>
         </ul>
         <div class="tab-content">
            <div class="tab-pane <?php  if($this->uri->segment(4) =="mangelist"){echo  "active";} ?>" id="mangelist" role="tabpanel">
              <div class="card-body">
                <?php 
                //   print_r($mangelist['result']->result());
                // print_r($mangelist); ?>
                <h4 class="card-title">Gears Listing</h4>
                <h6 class="card-subtitle">(
                  <?php if($mangelist['total_rows']>1){ echo $mangelist['total_rows']." Gears"; }else{ echo $mangelist['total_rows']." Gears"; } ?>
                  )
                </h6>
                <?php echo $this->session->userdata('success'); ?>
       
                <form name="bulk_action_form" action="<?php echo base_url();?>app_users/select_delete" method="post" onSubmit="return delete_confirm();"/>
                 <?php echo $mangelist['paginator'];?>
                 <!-- Check all button -->
                 <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/>
                 <div class="table-responsive m-t-40">
                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">
                      <thead>
                        <tr>
                          <th class="font_n"><input type="checkbox" name="select_all" id="select_all"  /></th>
                          <th class="font_n">#</th>
                          <th class="font_n">Gear Name</th>
                          <th class="font_n">Model</th>
                          <th class="font_n">Category</th>
                          <th class="font_n">Per Day Cost</th>
                          <th class="font_n">Per Weekend Cost</th>
                          <th class="font_n">Per Week Cost</th>
                          <th class="font_n">List Details Date</th>
                          <th class="font_n">Status</th>
                          <th class="font_n">Gear Type</th>
                          <th class="font_n">Date Created</th>
                          <th class="font_n">App User</th>

                          <th class="font_n">Date Updated</th>
                          <th class="font_n taC">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($mangelist['total_rows']>0){
                        
                          if($this->input->get('per_page')!='')
                            {
                            $i = $this->input->get('per_page')+1;
                            }
                            else
                            {
                            $i=1;
                            }

                            foreach($mangelist['result']->result()  as $row ){ ?>
                        <tr>
                        <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->user_gear_desc_id;?>"></td>
                        <td class="mailbox-name"><?php echo $i;?></td>
                        <td class="mailbox-subject"><?php echo $row->gear_name;?></td>
                        <td class="mailbox-subject"><?php echo $row->model_name;?></td>
                        <td class="mailbox-subject"><?php echo $row->gear_category_name;?></td>
                        <td class="mailbox-subject"><?php echo $row->per_day_cost_aud_ex_gst; ?></td>
                        <td class="mailbox-subject"><?php echo $row->per_weekend_cost_aud_ex_gst; ?></td>
                        <td class="mailbox-subject"><?php echo $row->per_week_cost_aud_ex_gst; ?></td>
                        <td class="mailbox-subject"><?php echo $row->gear_delisting_date; ?></td>
                        <td class="mailbox-subject"><?php if($row->is_active=='Y'){ echo "Active"; } else { echo "Inactive"; } ?></td>
                        <td class="mailbox-subject"><?php echo $row->ks_gear_type_name;  ?></td>
                        <td class="mailbox-date"><?php echo $row->create_date; ?></td>
                        <td class="mailbox-subject"><?php echo $row->app_user_first_name.' '.  $row->app_user_last_name; ?></td>
                        <td class="mailbox-date"><?php if($row->update_date==''){ echo "-"; } else { echo $row->update_date; } ?></td>
                        <td class="mailbox-date">
                            <a href="<?php echo base_url();?>gear_desc/view/<?php echo $row->user_gear_desc_id; ?>" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <a href="<?php echo base_url();?>gear_desc/edit/<?php echo $row->user_gear_desc_id; ?>" title="Modify"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                            <a href="<?php echo base_url();?>gear_desc/upload/<?php echo $row->user_gear_desc_id; ?>" title="Uploaded Image"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>
                            <!-- <a onClick="return confirm('Would You Like To Delete This?');" href="<?php echo base_url();?>gear_desc/delete_record/<?php echo $row->user_gear_desc_id; ?>" title="Delete"><i class="fa fa-trash-o"></i></a> </td> -->
                        </tr>
                          <?php $i++; } ?>
                          <?php } else { ?>
                          <tr>
                            <td colspan="15" align="center">No record found</td>
                          </tr>
                          <?php  }?>
                      </tbody>
                    </table>
                 </div>
                </form>
              </div>
            </div>
            <div class="tab-pane  <?php  if($this->uri->segment(4) =="owner"){echo  "active";} ?>" id="owner" role="tabpanel">
              <div class="card-body">
                <h4 class="card-title"> Rents Listing </h4>
                <h6 class="card-subtitle">(
                  <?php if($total_rows>1){ echo $total_rows." Rented Gear"; }else{ echo $total_rows." Rented Gear"; } ?>
                  )
                </h6>
                <?php echo $this->session->userdata('success'); ?>
       
                <form name="bulk_action_form" action="<?php echo base_url();?>app_users/select_delete" method="post" onSubmit="return delete_confirm();"/>
                 <?php echo $paginator;?>
                 <!-- Check all button -->
                 <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/>
                 <div class="table-responsive m-t-40">
                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">
                      <thead>
                        <th><input type="checkbox" name="select_all" id="select_all"  /></th>
                        <th>#</th>
                        <th>Gear</th>
                        <th>Requested From</th>
                        <th>Requested To</th>
                        <th>Total Request Date</th>
                        <th>Paid</th>
                        <th>Paid Amount</th>
                        <th>Delivered</th>
                        <th>Approved</th>
                        <th class="taC">Action</th>
                      </thead>
                      <tbody>
                        <?php if($total_rows>0){
                        
                          if($this->input->get('per_page')!='')
                            {
                            $i = $this->input->get('per_page')+1;
                            }
                            else
                            {
                            $i=1;
                            }

                            foreach($result as $row){
                             ?>
                        <tr>
                            <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->app_user_id;?>"></td>
                            <td class="mailbox-name"><?php echo $i;?></td>
                            <td class="mailbox-subject"><?php echo $row->gear_name ;?></td>
                            <td class="mailbox-subject"><?php
                                    $str = $row->gear_rent_request_from_date;
                                  $arr = explode(" ",$str);
                                  $date = $arr[0];
                                  $time = $arr[1];
                                  echo date('d M, Y', strtotime($date)); 
                                  ?> <br/>at &nbsp;<?php
                                    
                                  $time = $arr[1];
                                  echo date("g:i A", strtotime($time)); 
                                  ?></td>
                            <td class="mailbox-subject"><?php
                                    $str = $row->gear_rent_request_to_date;
                                  $arr = explode(" ",$str);
                                  $date = $arr[0];
                                  $time = $arr[1];
                                  echo date('d M, Y', strtotime($date)); 
                                  ?> <br/>at &nbsp;<?php
                                    
                                  $time = $arr[1];
                                  echo date("g:i A", strtotime($time)); 
                                  ?>
                            </td>
                            <td class="mailbox-subject"><?php echo $row->gear_total_rent_request_days;?></td>
                            <td class="mailbox-subject"><?php //echo $row->app_user_id;  ?>
                                    
                                <?php if($row->is_payment_completed == 'Y'){echo "Yes" ;}else{echo "No" ;}   ?>
                            </td>
                            <td><?php echo $row->total_rent_amount; ?></td>
                            <td class="mailbox-subject"><?php $delivery_count = $this->common_model->countAll('ks_user_gear_rent_deliveries',array("user_gear_rent_id"=>$row->user_gear_rent_id,"is_returned"=>'N')); if($delivery_count>0) echo 'Yes' ; else echo 'No'; ?></td>
                            <td class="mailbox-subject"><?php if($row->is_rent_approved=='n' or $row->is_rent_approved=='N') echo 'No'; else echo 'Yes'; ?></td>
                            <td class="mailbox-date">
                              <a href="<?php echo base_url();?>app_users/rent_details/<?php echo $row->app_user_id; ?>/<?php echo $row->user_gear_rent_detail_id; ?>" title="Modify" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>
                              <a href="<?php echo base_url();?>app_users/chat_history/<?php echo $row->app_user_id; ?>/<?php echo $row->user_gear_desc_id .'/' .$row->create_user; ?>" title="Modify" ><i class="fa fa-comments-o" aria-hidden="true"></i></a>

                            </td>
                        </tr>
                          <?php $i++; } ?>
                          <?php } else { ?>
                          <tr>
                            <td colspan="11" align="center">No record found</td>
                          </tr>
                          <?php  }?>
                      </tbody>
                    </table>
                 </div>
                </form>
              </div>
            </div>
                                <!--second tab-->
            <div class="tab-pane <?php  if($this->uri->segment(4) =="renter"){echo  "active";} ?>" id="renter" role="tabpanel">
              <div class="card-body">
                <h4 class="card-title">Rented Gears</h4>
                <h6 class="card-subtitle">(
                  <?php if($ownerlist['total_rows']>1){ echo $ownerlist['total_rows']." Rented Gears"; }else{ echo $ownerlist['total_rows']." Rented Gears"; } ?>
                  )
                </h6>
                <?php //print_r($ownerlist['result']); ?>
                <?php echo $this->session->userdata('success'); ?>
       
                <form name="bulk_action_form" action="<?php echo base_url();?>app_users/select_delete" method="post" onSubmit="return delete_confirm();"/>
                 <?php echo $ownerlist['paginator'];?>
                 <!-- Check all button -->
                 <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/>
                 <div class="table-responsive m-t-40">
                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">
                      <thead>
                        <th><input type="checkbox" name="select_all" id="select_all"  /></th>
                        <th>#</th>
                        <th>Gear</th>
                        <th>Requested From</th>
                        <th>Requested To</th>
                        <th>Total Request Date</th>
                        <th>Paid</th>
                        <th>Delivered</th>
                        <th>Approved</th>
                        <th class="taC">Action</th>
                      </thead>
                      <tbody>
                        <?php if($ownerlist['total_rows']>0){
                        
                          if($this->input->get('per_page')!='')
                            {
                            $i = $this->input->get('per_page')+1;
                            }
                            else
                            {
                            $i=1;
                            }

                            foreach($ownerlist['result'] as $row){ ?>
                        <tr>
                            <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->app_user_id;?>"></td>
                            <td class="mailbox-name"><?php echo $i;?></td>
                            <td class="mailbox-subject"><?php echo $row->gear_name ;?></td>
                            <td class="mailbox-subject"><?php
                                    $str = $row->gear_rent_request_from_date;
                                  $arr = explode(" ",$str);
                                  $date = $arr[0];
                                  $time = $arr[1];
                                  echo date('d M, Y', strtotime($date)); 
                                  ?> <br/>at &nbsp;<?php
                                    
                                  $time = $arr[1];
                                  echo date("g:i A", strtotime($time)); 
                                  ?></td>
                            <td class="mailbox-subject"><?php
                                    $str = $row->gear_rent_request_to_date;
                                  $arr = explode(" ",$str);
                                  $date = $arr[0];
                                  $time = $arr[1];
                                  echo date('d M, Y', strtotime($date)); 
                                  ?> <br/>at &nbsp;<?php
                                    
                                  $time = $arr[1];
                                  echo date("g:i A", strtotime($time)); 
                                  ?>
                            </td>
                            <td class="mailbox-subject"><?php echo $row->gear_total_rent_request_days;?></td>
                            <td class="mailbox-subject"><?php //echo $row->app_user_id;  ?>
                                    <?php $payment = $this->common_model->countAll('ks_user_gear_payments',array("app_user_id"=>$row->app_user_id)); if($payment>0) echo 'Yes' ; else echo 'No'; ?>
                    
                            </td>
                            <td class="mailbox-subject"><?php $delivery_count = $this->common_model->countAll('ks_user_gear_rent_deliveries',array("user_gear_rent_id"=>$row->user_gear_rent_id,"is_returned"=>'N')); if($delivery_count>0) echo 'Yes' ; else echo 'No'; ?></td>
                            <td class="mailbox-subject"><?php if($row->is_rent_approved=='n' or $row->is_rent_approved=='N') echo 'No'; else echo 'Yes'; ?></td>
                            <td class="mailbox-date">
                              <a href="<?php echo base_url();?>app_users/rent_details/<?php echo $row->app_user_id; ?>/<?php echo $row->user_gear_rent_detail_id; ?>" title="Modify" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>
                              <a href="<?php echo base_url();?>app_users/chat_history/<?php echo $row->app_user_id; ?>/<?php echo $row->user_gear_desc_id .'/' .$row->create_user; ?>" title="Modify" ><i class="fa fa-comments-o" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                          <?php $i++; } ?>
                          <?php } else { ?>
                          <tr>
                            <td colspan="11" align="center">No record found</td>
                          </tr>
                          <?php  }?>
                      </tbody>
                    </table>
                 </div>
                </form>
              </div>
            </div>
                               
          </div>
        
      </div>
      <!-- ============================================================== -->
      <!-- End PAge Content -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
  </div>
  <!-- ============================================================== -->
  <!-- End Page wrapper  -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
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
function PageReload(type) {
     window.location.href = "<?php echo base_url().'app_users/rent/'.$this->uri->segment(3).'/' ?>"+type;
}
</script>
