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

      <h3 class="text-themecolor m-b-0 m-t-0">Order Issue List</h3>

      <ol class="breadcrumb">

        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>

        <li class="breadcrumb-item"><a href="<?php echo base_url();?>Orders">Order   List</a></li>
        <li class="breadcrumb-item active">Order Issue  List</li>

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
            <li class="nav-item"> <a class="nav-link "href="#issuelist" onclick="PageReload('issuelist')"  role="tab">Issue Listing</a> </li>
            <li class="nav-item"> <a class="nav-link < ?php  if($this->uri->segment(4) =='owner'){echo  'active';} ?>  "  href="#owner"  onclick="PageReload('owner')"  role="tab">Checklist Issue</a> </li>
           
         </ul>

         <div class="tab-content">
            <div class="tab-pane  <?php  if($this->uri->segment(4) =="issuelist"){echo  "active";} ?> "id="issuelist" role="tabpanel">
              <div class="card-body">
                <?php 
                //   print_r($mangelist['result']->result());
                // print_r($mangelist); ?>
                  <h4 class="card-title">Order Issue List  Order ID :-- <?php echo $this->uri->segment(3);?></h4>
               <h6 class="card-subtitle">(<?php if($total_rows>1){ echo $total_rows." Issue"; }else{ echo $total_rows." Issue"; } ?> )</h6>
                <?php echo $this->session->userdata('success'); ?>
       
                <form name="bulk_action_form" action="<?php echo base_url();?>app_users/select_delete" method="post" onSubmit="return delete_confirm();"/>
              
                 <!-- Check all button -->
                
                 <div class="table-responsive m-t-40">
                    <table id="issuelist" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">

                      <thead>
                        <th>#</th>
                        <th>OrderID</th>
                        <th>Type</th>
                        <th>Issue</th>
                        <th>Created Date</th>
                        <th>Created By</th>
                      </thead>

                      <tbody>

                     <?php 
                     if (count($result) > 0 ) {
                           # code...
                         
                     $i = 1;
                         foreach($result as $row){ 
                          ?>

                                <tr>
                                  <td class="mailbox-name"><?php echo $i;?></td>
                                  <td class="mailbox-subject"><?php echo $row->order_id;?></td>
                                   <td class="mailbox-subject"><?php echo $row->type;?></td> 
                                   <td class="mailbox-subject"><?php echo $row->issue;?></td> 
                                   <td class="mailbox-subject"><?php echo $row->created_date;?></td> 
                                  <td class="mailbox-subject"><?php echo $row->create_user_firstname .' ' .$row->create_user_lastname;?></td>

                                 <!--  <td class="mailbox-subject"><?php echo $row->send_first_name .' ' . $row->send_last_name;?></td> -->

                                 
                                  

                                  <!-- <td></td> -->

                                    
                                </tr>

                                <?php $i++; }}else{ ?>

                                

                                <tr>

                                  <td colspan="10" align="center">No record found</td>

                                </tr>

                                <?php }?>

                              </tbody>

                      </table>
                 </div>
                </form>
              </div>
            </div>
            <div class="tab-pane  <?php  if($this->uri->segment(4) =="owner"){echo  "active";} ?>" id="owner" role="tabpanel">
              <div class="card-body">
                <h4 class="card-title"> Checklist Issue </h4><h4 class="card-title">Order  Checklist Issue List  Order ID :-- <?php echo $this->uri->segment(3);?></h4>

                <?php echo $this->session->userdata('success'); ?>
              
                 <form name="bulk_action_form" action="<?php echo base_url();?>app_users/select_delete" method="post" onSubmit="return delete_confirm();"/>
              
                 <!-- Check all button -->
                
                 <div class="table-responsive m-t-40">
                    <table id="issuelist" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">

                      <thead>
                        <th>#</th>
                        <th>OrderID</th>
                        <th>Type</th>
                        <th>Issue</th>
                        <th>Created Date</th>
                        <th>Created By</th>
                      </thead>

                      <tbody>

                     <?php 
                     if (count($issuelist) > 0 ) {
                           # code...
                         
                     $i = 1;
                         foreach($issuelist as $row){ 
                          ?>

                                <tr>
                                  <td class="mailbox-name"><?php echo $i;?></td>
                                  <td class="mailbox-subject"><?php echo $row->order_id;?></td>
                                   <td class="mailbox-subject"><?php echo $row->type;?></td> 
                                   <td class="mailbox-subject"><img src="<?php echo FRONT_URL;  ?>checklist/<?php echo $row->image;?>" width="100" height="100"></td> 
                                   <td class="mailbox-subject"><?php echo $row->created_date;?></td> 
                                  <td class="mailbox-subject"><?php echo $row->create_user_firstname .' ' .$row->create_user_lastname;?></td>

                                 <!--  <td class="mailbox-subject"><?php echo $row->send_first_name .' ' . $row->send_last_name;?></td> -->

                                 
                                  

                                  <!-- <td></td> -->

                                    
                                </tr>

                                <?php $i++; }}else{ ?>

                                

                                <tr>

                                  <td colspan="10" align="center">No record found</td>

                                </tr>

                                <?php }?>

                              </tbody>

                      </table>
                 </div>
                </form>
              </div>
            </div>
                                <!--second tab-->
        
                               
          </div>   
        <div class="card-body">

        

          

          <?php echo $this->session->userdata('success'); ?>

		  <!--<div class="row">CSV

		  <div class="form-group col-md-2"> <a href="#" class="btn btn-primary pull-right add_userbtn" data-toggle="modal" data-target="#myModal">CSV Upload&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></a> </div>

		  </div>-->

          <div class="box-header with-border">


            <div class="panel-body">
              <div class="has-feedback">

               
              </div>
            </div>
            
          </div>
          <div class="box-header with-border">


            <div class="panel-body">
              <div class="has-feedback">
               
              </div>
            </div>
            
          </div>


         
         <!--  <form name="bulk_action_form" action="<?php echo base_url();?>app_users/select_delete" method="post" onSubmit="return delete_confirm();"/> -->

		    <!-- Check all button -->
           <div  class=" col-md-12" id="error_msg_serial_no"></div>
			

		   <div class="row" align="right">



				<div class="pull-right" align="right">

				<!-- 	 <a href="#" class="btn btn-primary pull-right add_userbtn" data-toggle="modal" data-target="#myModal">CSV Upload&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;

					<a href="<?php echo base_url();?>app_users/downloadallcsv" class="btn btn-primary pull-right add_userbtn" >CSV Download&nbsp;<i class="fa fa-download" aria-hidden="true"></i></a>&nbsp; 
 -->
				
				</div>

			</div>

          <!-- <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/> -->

		  

		  

          <div class="table-responsive m-t-40">

            

          </div>

          </form>

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

<div id="myModal" class="modal fade" role="dialog">

  <div class="modal-dialog modal-sm">

    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">

	  <h4 class="modal-title">Upload CSV</h4>

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        

      </div>

      <form class="form-inline" data-toggle="validator" role="form" method="post" action="<?php echo base_url();?>app_users/import" enctype="multipart/form-data">

        <div class="modal-body">

          <div class="form-group">

            <input type="file" name="file" id="csv">

            </br>

          </div>

        </div>

        <div class="modal-footer">

          <button type="submit" class="btn btn-success">Submit</button>

        </div>

      </form>

    </div>

  </div>

</div>

<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/daterangepicker/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-datepicker.js"></script>
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



	
      $('.status_order').on('change',function(){
        var status_order  =  $(this).closest('tr').find('td .status_order').val();
        var order_id  =  $(this).closest('tr').find('td .order_id').val();
        $.ajax({
            type:'Post',
            url: '<?php echo base_url(); ?>Orders/UpdateStatus',
            data :{status_order : status_order ,order_id :order_id },
            dataType:'Json',
            success: function(data){
              if (data.status == '0') {
                    $('#error_msg_serial_no').html(data.message);
                    setTimeout(function () {
                       $('#error_msg_serial_no').html('');
                    }, 2500);
              }else{
                    $('#error_msg_serial_no').html(data.message);
                    setTimeout(function () {
                       $('#error_msg_serial_no').html('');
                   }, 2500);
              };
              location.reload();
            }
        });    
      });
       $('.payment_status').on('change',function(){
        var payment_status  =  $(this).closest('tr').find('td .payment_status').val();
        var order_id  =  $(this).closest('tr').find('td .order_id').val();
        $.ajax({
            type:'Post',
            url: '<?php echo base_url(); ?>Orders/UpdatePaymentStatus',
            data :{payment_status : payment_status ,order_id :order_id },
            dataType:'Json',
            success: function(data){
              if (data.status == '0') {
                    $('#error_msg_serial_no').html(data.message);
                    setTimeout(function () {
                       $('#error_msg_serial_no').html('');
                    }, 2500);
              }else{
                    $('#error_msg_serial_no').html(data.message);
                    setTimeout(function () {
                       $('#error_msg_serial_no').html('');
                   }, 2500);
              };
              location.reload();
            }
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

$(function() {

    $("#limit").change(function() {

        var limit = $('option:selected', this).val();

		window.location.href = "<?php echo base_url() ?>app_users/?limit="+limit;

		

		

    });

});

var j = jQuery.noConflict();
j(function() {

  j('input[name="duration"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear'
      }
  });

  j('input[name="duration"]').on('apply.daterangepicker', function(ev, picker) {
      j(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });
}); 
var j = jQuery.noConflict(); 
j(function() {
  j('.game_date').datepicker({
autoclose: true
});
}); 
function PageReload(type) {
     window.location.href = "<?php echo base_url().'Orders/IssueList/'.$this->uri->segment(3).'/' ?>"+type;
}
</script>

