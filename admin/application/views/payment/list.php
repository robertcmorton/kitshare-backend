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

      <h3 class="text-themecolor m-b-0 m-t-0">Payment List</h3>

      <ol class="breadcrumb">

        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>

        <li class="breadcrumb-item active">Payment List</li>

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

          <h4 class="card-title">Payment History</h4>

          <h6 class="card-subtitle">(

            <?php if($total_rows>1){ echo $total_rows." Payment"; }else{ echo $total_rows." Payment"; } ?>

            )</h6>

          <?php echo $this->session->userdata('success'); ?>

		  <!--<div class="row">CSV

		  <div class="form-group col-md-2"> <a href="#" class="btn btn-primary pull-right add_userbtn" data-toggle="modal" data-target="#myModal">CSV Upload&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></a> </div>

		  </div>-->

          <div class="box-header with-border">

            <h3 class="box-title">Search by:</h3>

            <div class="panel-body">

              <div class="has-feedback">

                <form class="form-inline example example53" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>payments/PaymentSummmary">
                    <div class="form-group col-md-12 no_padding">
                      <input type="text" autocomplete="off" class="game_date"  required="" id="game_date" name="start_date" placeholder="Start Date" value="">
                      <input type="text" autocomplete="off" class="game_date" required=""  name="end_date" placeholder="End Date" value="<?php // echo $gear_name; ?>" >
                      <button type="submit" style="width: 26%;">Download Payment Summary</button>
                    </div>
                </form>
              </div>

            </div>

          </div>

          <form name="bulk_action_form" action="<?php echo base_url();?>app_users/select_delete" method="post" onSubmit="return delete_confirm();"/>

		    <!-- Check all button -->

			

		   <div class="row" align="right">

				Showing <?php echo ($offset+1); ?> to <?php if($total_rows<($limit+$offset)) echo $total_rows ; else echo ($limit+$offset) ; ?> of <?php echo $total_rows; ?>

				<div class="pull-right" align="right">

				<!-- 	 <a href="#" class="btn btn-primary pull-right add_userbtn" data-toggle="modal" data-target="#myModal">CSV Upload&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;

					<a href="<?php echo base_url();?>app_users/downloadallcsv" class="btn btn-primary pull-right add_userbtn" >CSV Download&nbsp;<i class="fa fa-download" aria-hidden="true"></i></a>&nbsp; 
 -->
					<?php echo $paginator;?> 

				</div>

			</div>

          <!-- <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/> -->

		  

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

            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">

              <thead>

              <!-- <th><input type="checkbox" name="select_all" id="select_all"  /></th> -->

                <th>#</th>

				 <?php

					$order_by	= $this->input->get('order_by');

					if($order_by!=''){

						if($order_by == 'ASC'){

								$order_by = 'DESC';

								$arrow = 'down';

						}

						elseif($order_by == 'DESC'){

							$order_by = 'ASC';

							$arrow = 'up';

						}

					}

					else

					{

						$order_by = 'DESC';

						$arrow = 'down';

					}

				  ?>

				      <th>OrderID</th>

               <th>Owner</th>

                <th>Renter</th>

               

                <th>Transaction Id</th>

                <th>Transaction Amount</th>
                <th>GST Registered</th>
                <th>Owner Insurance Amount</th>
                <th>Owner Amount</th>
                <th><a href="<?php echo base_url() ?>Payments?order_by=<?php echo $order_by; ?>&limit=<?php echo $limit; ?>&per_page=<?php echo $offset; ?>" >Date<?php if($total_rows>1) { ?><i class="fa fa-angle-<?php echo $arrow; ?>" style="font-size: 24px;"></i><?php } ?> </a></th>
                <th>Refund Amount</th>
                <th>Refund Date</th>
                <th>Owner Payment Status</th>
               

				       <!--  <th>Gear Listing</th> -->

                <th class="taC">Action</th>
                <th class="taC">Settle Payment</th>
                <th class="taC">Refund Payment</th>
                </thead>

              <tbody>

                <?php if(!empty($result)){

				

				if($this->input->get('per_page')!='')

				  {

					$i = $this->input->get('per_page')+1;

				  }

				  else

				  {

					$i=1;

				  }



				 foreach($result as $row){ 
            if ($row->owner_app_show_business_name =='Y') {
              $row->app_user_first_name = $row->owner_app_bussiness_name ;
              $row->app_user_last_name = '' ;
            }
            if ($row->renter_app_show_business_name =='Y') {
                
              $row->buyer_first_name = $row->renter_app_bussiness_name ;
              $row->buyer_last_name = '' ;
            }
         ?>

                <tr>

                  <td class="mailbox-name"><?php echo $i;?></td>
                  <td class="mailbox-subject"><?php echo $row->order_id;?></td>
                  <td class="mailbox-subject"><a href="#" onclick="GetOwnerBankDetails('<?php echo $row->order_id;?>')"><?php echo $row->app_user_first_name .' ' .$row->app_user_last_name;?><a></td>
                  <td class="mailbox-subject"><a href="#" onclick="GetRenterBankDetails('<?php echo $row->order_id;?>')"><?php echo $row->buyer_first_name .' ' . $row->buyer_last_name;?></a></td>
                  <td class="mailbox-subject"><?php echo $row->transaction_id;?></td>
                  <td class="mailbox-subject"><?php echo $row->transaction_amount ; ?></td>
                 
                  <td class="mailbox-subject"><?php echo  $row->is_registered_for_gst ; ?></td>
                   <?php 
                  if ($row->is_registered_for_gst == 'Y') {
                    $owner_insurance_amount =    ((int) $row->owner_insurance_amount)*1.1 ;
                    ;
                      $total = $owner_insurance_amount  ;
                      $owner_insurance_amount =  number_format((float) $total, 2, '.', '');
                  }else{  
                    $owner_insurance_amount =  number_format((float) $row->owner_insurance_amount, 2, '.', '');
                  }  ?>
                  <td class="mailbox-subject"><?php echo  $owner_insurance_amount; ?></td>

                  <?php 
                  if ($row->is_registered_for_gst == 'Y') {
                      $subtotal =  (($row->total_rent_amount_ex_gst*85)/100 + (int)$row->owner_insurance_amount)*1.1 ;
                      $total = $subtotal  ;
                      $owner_amount =  number_format((float) $total, 2, '.', '');
                  }else{  
                    $owner_amount =  number_format((float) (($row->total_rent_amount_ex_gst*85 )/100)+ (int)$row->owner_insurance_amount, 2, '.', '') ; }?>
                  <td class="mailbox-subject"><?php echo  $owner_amount?></td>
                  <td class="mailbox-subject"><?php echo  $row->create_date?></td>
                  <td class="mailbox-subject"><?php echo $row->refund_amount;?></td>
                  <td class="mailbox-subject"><?php echo $row->refund_date;?></td> 
                  <?php if(empty($row->status)){
                        $status ='Stored';
                  }else{
                        $status =$row->status;
                  } ?>
                  <td class="mailbox-subject"><?php echo  $status;?></td>
                
				          <!-- <td></td> -->

                  <td class="mailbox-date">
                    <a href="<?php echo base_url();?>Orders/Details/<?php echo $row->order_id; ?>" title="Order Details" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a> 
                    <a href="<?php echo base_url();?>Orders/IssueList/<?php echo $row->order_id; ?>" title="Order IssueList" class="btn btn-primary btn-xs"><i class="fa fa-bookmark" aria-hidden="true"></i></a>
                  </td>
                  <td class="mailbox-date">
                    <a href="#" title="Mark As Paid" onclick="MarkAsPaid('<?php echo $row->order_id; ?>' ,'<?php echo $owner_amount +$owner_insurance_amount ; ?>')" class="btn btn-primary btn-xs"><i class="fa fa-money" aria-hidden="true"></i></a> 
                  </td>
                  <td class="mailbox-date">
                    <a href="#" title="Refund a Payment" onclick="RefundPayment('<?php echo $row->order_id; ?>' ,'<?php echo $row->transaction_amount; ?>' )" class="btn btn-primary btn-xs"><i class="fa fa-undo"></i></a> 
                  </td>

                </tr>

                <?php $i++; } ?>

                <?php } else {?>

                <tr>

                  <td colspan="10" align="center">No record found</td>

                </tr>

                <?php  }?>

              </tbody>

            </table>

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

<div class="modal fade" id="PaymentModel" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Payment Details </h4>
        </div>
        <div class="modal-body">
            <form action="<?php echo base_url(); ?>payments/markpaid" method="post"> 
          <div class="row">
            <input type="hidden" name="order_id" id="order_id" value="">
            <input type="hidden" name="order_amount" id="order_amount" value="">

            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Payment Amount</label>
              <input type="text" class="form-control" name="owner_payment_amount" id="owner_payment_amount"  placeholder="Payment Amount" required>
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('owner_payment_amount','<span class="text-danger">','</span>'); ?> 
            </div>
          
             <div class="form-group col-md-6">
              <label for="exampleInputarticle"> Payment Date</label>
              <input type="date" class="form-control" name="owner_payment_dates" id="owner_payment_dates"  placeholder="Payment Dates" required>
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('owner_payment_dates','<span class="text-danger">','</span>'); ?> 
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Remark</label>
              <textarea type="text" class="form-control" name="remark" id="remark"  placeholder="Remark" required></textarea>

                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('remark','<span class="text-danger">','</span>'); ?> 
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Submit</button>             
        </form>  
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="RefundModel" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Refund Details </h4>
        </div>
        <div class="modal-body">
            <form action="<?php echo base_url(); ?>payments/refundPayment" method="post"> 
          <div class="row">
            <input type="hidden" name="order_id" id="order_id_refund" value="">
            <input type="hidden" name="order_amount" id="order_amount_refund" value="">

            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Refund Amount</label>
              <input type="text" class="form-control" name="owner_payment_amount" id="owner_payment_amount_refund"  placeholder="Refund Amount" required>
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('owner_payment_amount','<span class="text-danger">','</span>'); ?> 
            </div>
          
             <div class="form-group col-md-6">
              <label for="exampleInputarticle"> Refund Date</label>
              <input type="date" class="form-control" name="owner_payment_dates" id="owner_payment_dates"  placeholder="Payment Dates" required>
                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('owner_payment_dates','<span class="text-danger">','</span>'); ?> 
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Remark</label>
              <textarea type="text" class="form-control" name="remark" id="remark"  placeholder="Remark" required></textarea>

                  
              <label for="" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('remark','<span class="text-danger">','</span>'); ?> 
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Submit</button>             
        </form>  
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="OwnerBankModel" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Bank Details </h4>
        </div>
        <div class="modal-body">
          <div class="row">
          

            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Account Name</label>
              <input type="text" class="form-control" name="account_name" id="account_name"  value="" readonly>
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Account Number</label>
              <input type="text" class="form-control" name="account_number" id="account_number"  value="" readonly>
            </div>
             <div class="form-group col-md-6">
              <label for="exampleInputarticle">Bsb Number</label>
              <input type="text" class="form-control" name="bsb_number" id="bsb_number"  value="" readonly>
            </div>
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Bank NAME </label>
              <input type="text" class="form-control" name="bank_name" id="bank_name"  value="" readonly >
            </div> 
            <div class="form-group col-md-6">
              <label for="exampleInputarticle">Bank Details </label>
              <input type="text" class="form-control" name="bank_detail" id="bank_detail"  value="" readonly>
            </div>
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="ErrorBankModel" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Bank Details </h4>
        </div>
        <div class="modal-body">
          <div class="row">
          

            <div class="form-group col-md-6" id="error_msg_serial_no">
             
            </div>
          
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
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

		window.location.href = "<?php echo base_url() ?>Payments/?limit="+limit;

		

		

    });

});
function MarkAsPaid(order_id,amount) {
   $('#PaymentModel').modal('show');
   
   $('#order_id').val(order_id);
   $('#order_amount').val(amount);
   $('#owner_payment_amount').val(amount);
};
function GetOwnerBankDetails (order_id) {
  
  $.ajax({
            type:'Post',
            url: '<?php echo base_url(); ?>Payments/GetOwnerBankDetails',
            data :{order_id :order_id },
            dataType:'Json',
            success: function(data){
              console.log(data);
              if (data.status == '0') {
                $('#ErrorBankModel').modal('show');
                    $('#error_msg_serial_no').html(data.message);
                    // setTimeout(function () {
                    //    $('#error_msg_serial_no').html('');
                    // }, 2500);
              }else{
                $('#OwnerBankModel').modal('show');
                $('#bsb_number').val(data.bankdetails.bsb_number);
                $('#bank_name').val(data.bankdetails.bank_name);
                $('#account_name').val(data.bankdetails.user_account_name);
                $('#account_number').val(data.bankdetails.user_account_number);
                $('#bank_detail').val(data.bankdetails.bank_head_office);
                    // $('#error_msg_serial_no').html(data.message);
                    
              };
            }
        }); 

}
function GetRenterBankDetails (order_id) {
  
  $.ajax({
            type:'Post',
            url: '<?php echo base_url(); ?>Payments/GetRenterBankDetails',
            data :{order_id :order_id },
            dataType:'Json',
            success: function(data){
              console.log(data);
              if (data.status == '0') {
                $('#ErrorBankModel').modal('show');
                    $('#error_msg_serial_no').html(data.message);
                    // setTimeout(function () {
                    //    $('#error_msg_serial_no').html('');
                    // }, 2500);
              }else{
                $('#OwnerBankModel').modal('show');
                $('#bsb_number').val(data.bankdetails.bsb_number);
                $('#bank_name').val(data.bankdetails.bank_name);
                $('#account_name').val(data.bankdetails.user_account_name);
                $('#account_number').val(data.bankdetails.user_account_number);
                $('#bank_detail').val(data.bankdetails.bank_head_office);
                    // $('#error_msg_serial_no').html(data.message);
                    
              };
            }
        }); 

}
function RefundPayment(order_id,amount) {
  alert
  $('#RefundModel').modal('show');
   $('#order_id_refund').val(order_id);
   $('#order_amount_refund').val(amount);
   $('#owner_payment_amount_refund').val(amount);
};
var j = jQuery.noConflict(); 
j(function() {
  j('.game_date').datepicker({
autoclose: true
});
});
</script>

