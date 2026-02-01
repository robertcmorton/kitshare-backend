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

      <h3 class="text-themecolor m-b-0 m-t-0">Order List</h3>

      <ol class="breadcrumb">

        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>

        <li class="breadcrumb-item active">Order List</li>

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

          <h4 class="card-title">Order List</h4>

          <h6 class="card-subtitle">(

            <?php if($total_rows>1){ echo $total_rows." Order"; }else{ echo $total_rows." Order"; } ?>

            )</h6>

          <?php echo $this->session->userdata('success'); ?>

      <!--<div class="row">CSV

      <div class="form-group col-md-2"> <a href="#" class="btn btn-primary pull-right add_userbtn" data-toggle="modal" data-target="#myModal">CSV Upload&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></a> </div>

      </div>-->

          <div class="box-header with-border">

            <h3 class="box-title">Search by:</h3>

            <div class="panel-body">
              <div class="has-feedback">

               <form class="form-inline  example example51" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>Orders">

                  <div class="form-group col-md-12 no_padding">

                    <input type="text" class="" name="amount" placeholder="Amount" value="<?php echo $amount; ?>" size="20">
                    <select type="text" class="" name="is_payment_completed" placeholder="" value="" id="ks_category_id" style="padding: 10px;font-size: 17px;border: 1px solid #007fb4;float: left;width: 10% !important;background: #f1f1f1;margin-right: 5px !important;"> 
                       <option value="">SELECT PAYMENT </option> 
                       <option value="Y">Paid</option> 
                       <option value="N">UnPaid</option> 
                    </select>  

                    <input type="text" class="duration" id="duration" name="duration" placeholder="" value="" size="20">
                    <select type="text" class="" name="status" placeholder="" value="" id="ks_category_id" style="padding: 10px;font-size: 17px;border: 1px solid #007fb4;float: left;width: 12% !important;background: #f1f1f1;margin-right: 5px !important;"> 
                        <option value="">SELECT STATUS </option>
                        <option value="Quote">Quote</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Rejected">Declined</option>
                        <option value="Reservation">Reservation</option>
                        <option value="Contract">Contract</option>
                        <option value="Completed" >Completed</option>
                        <option value="Expired" >Expired</option>
                    </select>  
                    <button type="submit"style="width:4% !important" ><i class="fa fa-search"></i></button>

                  </div>
                </form> 
              </div>
            </div>
            
          </div>
          <div class="box-header with-border">

            <h3 class="box-title">Downloaod Summary</h3>

            <div class="panel-body">
              <div class="has-feedback">
                <form class="form-inline example example53" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>orders/OrdersSummmary">
                    <div class="form-group col-md-12 no_padding">
                      <input type="text" autocomplete="off" class="game_date"  required="" id="game_date" name="start_date" placeholder="Start Date" value="">
                      <input type="text" autocomplete="off" class="game_date" required=""  name="end_date" placeholder="End Date" value="<?php // echo $gear_name; ?>" >
                      <button type="submit" >Download Summary</button>
                    </div>
                </form>
              </div>
            </div>
            
          </div>


         
          <form name="bulk_action_form" action="<?php echo base_url();?>app_users/select_delete" method="post" onSubmit="return delete_confirm();"/>

        <!-- Check all button -->
           <div  class=" col-md-12" id="error_msg_serial_no"></div>
      

       <div class="row" align="right">

        Showing <?php echo ($offset+1); ?> to <?php if($total_rows<($limit+$offset)) echo $total_rows ; else echo ($limit+$offset) ; ?> of <?php echo $total_rows; ?>

        <div class="pull-right" align="right">

        <!--   <a href="#" class="btn btn-primary pull-right add_userbtn" data-toggle="modal" data-target="#myModal">CSV Upload&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;

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

          <option value="25" <?php if($limit==25) echo "selected=selected" ; ?>>25</option>

          <option value="50" <?php if($limit==50) echo "selected=selected" ; ?>>50</option>

          <option value="100" <?php if($limit==100) echo "selected=selected" ; ?>>100</option>

        </select>

        </div>

    </div>

          <div class="table-responsive m-t-40">

            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">

              <thead>

              <!-- <th><input type="checkbox" name="select_all" id="select_all"  /></th> -->

                <th>#</th>

         <?php

          $order_by = $this->input->get('order_by');

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

<!--                 <th>Gear Name</th>
 -->                <th>Project Name</th>

                <th>Requested Period</th>

                <th>Rented  Days</th>

                <th>Paid Amount</th>
                <th>Deposit Amount</th>
                <!-- <th>Paid Status</th> -->
                

               <!--  <th>Gear Listing</th> -->

                <th class="taC">Action</th>
                <th>Order Status</th>
                 <th>Payment Status</th>
                 <th>Deposit Status</th>
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
          //print_r($row); die;
          ?>

                <tr>

                  <!-- <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->app_user_id;?>"></td> -->

                  <td class="mailbox-name"><?php echo $i;?></td>

                  <td class="mailbox-subject"><?php echo $row->order_id;?></td>

                  <td class="mailbox-subject"><?php echo $row->app_user_first_name .' ' .$row->app_user_last_name;?></td>

                  <td class="mailbox-subject"><?php echo $row->buyer_first_name .' ' . $row->buyer_last_name;?></td>

                  <!-- <td class="mailbox-subject"><?php echo $row->gear_name;?></td> -->
                  <td class="mailbox-subject"><?php echo $row->project_name;?></td>

                  <td class="mailbox-subject"><?php echo date('d-m-Y',strtotime($row->gear_rent_request_from_date)) . ' To ' .date('d-m-Y',strtotime($row->gear_rent_request_to_date)) ;?></td>

                  <td class="mailbox-subject"><?php echo $row->total_rent_days ; ?>

                   </td>

                  <td class="mailbox-subject"><?php echo number_format((float)  $row->total_rent_amount , 2, '.', '');?></td>
                  <td class="mailbox-subject"><?php 

                   if (!empty($row->insurance_amount)) {
                    //  echo  number_format((float)  $row->insurance_amount , 2, '.', '') ;
                      echo  0 ;
                    }else{

                      echo  number_format((float)  $row->security_deposit , 2, '.', '') ;
                    } ?>
                  </td> 


                  <!-- <td></td> -->

                  <td class="mailbox-date">
                    <a href="<?php echo base_url();?>Orders/Invocie/<?php echo $row->order_id; ?>" title="Invoice" class="btn btn-primary btn-xs"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a> 
                    <a href="<?php echo base_url();?>Orders/Details/<?php echo $row->order_id; ?>" title="Order Details" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a> 
                    <a href="<?php echo base_url();?>Orders/IssueList/<?php echo $row->order_id; ?>" title="Order IssueList" class="btn btn-primary btn-xs"><i class="fa fa-bookmark" aria-hidden="true"></i></a> 
                  </td>
                  <td class="mailbox-date"> 
                      <input type="hidden" id="order_id" class="order_id" value="<?php echo $row->order_id; ?>">
                      <select name="status_order" class="status_order" id="status_order">
                        <option <?php if($row->order_status == '1' || $row->order_status == '' ){echo "selected"; } ?>>Quote</option>
                        <option value="Cancelled" <?php if($row->order_status == '5'){echo "selected"; } ?> >Cancelled</option>
                        <option value="Rejected" <?php if($row->order_status == '6'){echo "selected"; } ?> >Declined</option>
                        <option value="Reservation" <?php if($row->order_status == '2'){echo "selected"; } ?> >Reservation</option>
                        <option value="Contract"  <?php if($row->order_status == '3'){echo "selected"; } ?> >Contract</option>
                        <option value="Completed" <?php if($row->order_status == '4'){echo "selected"; } ?> >Completed</option>
                        <option value="Expired" <?php if($row->order_status == '8'){echo "selected"; } ?> >Expired</option>
                        <option value="Archived" <?php if($row->order_status == '7'){echo "selected"; } ?> >Archived</option>
                        
                      </select> 

                  </td>
                   <td class="mailbox-date">
                       <select name="payment_status" class="payment_status" id="payment_status">
                        <option <?php if($row->paymnet_status == 'STORED' || $row->paymnet_status == '' ){echo "selected"; } ?>>STORED </option>
                        <option value="AUTHORISED" <?php if($row->paymnet_status == 'AUTHORISED'){echo "selected"; } ?> >AUTHORISED</option>
                        <option value="RECEIVED" <?php if($row->paymnet_status == 'RECEIVED'){echo "selected"; } ?> >SETTLED</option>
                        <option value="PAID" <?php if($row->paymnet_status == 'PAID'){echo "selected"; } ?> >PAID</option>
                        <option value="PARTIAL REFUND"  <?php if($row->paymnet_status == 'PARTIAL REFUND'){echo "selected"; } ?> >PARTIAL REFUND</option>
                        <option value="DECLINED" <?php if($row->paymnet_status == 'DECLINED'){echo "selected"; } ?> >DECLINED</option>
                        
                      </select> 
                  </td>
                  <td class="mailbox-date">
                        <select name="deposite_status" class="deposite_status" id="deposite_status">
                        <option value="NONE" <?php if($row->deposite_status == 'NONE' || $row->deposite_status == '' ){echo "selected"; } ?>>NONE </option>
                        <option value="STORED" <?php if($row->deposite_status == 'STORED'){echo "selected"; } ?> > STORED</option>
                        <option value="AUTHORISED" <?php if($row->deposite_status == 'AUTHORISED'){echo "selected"; } ?> >AUTHORISED</option>
                        <option value="RECEIVED" <?php if($row->deposite_status == 'RECEIVED'){echo "selected"; } ?> >SETTLED</option>
                        <option value="REFUNDED" <?php if($row->deposite_status == 'REFUNDED'){echo "selected"; } ?> >REFUNDED</option>
                        <option value="Collected" <?php if($row->deposite_status == 'Collected'){echo "selected"; } ?> >Collected</option>
                        <option value="Cancelled" <?php if($row->deposite_status == 'Cancelled'){echo "selected"; } ?> >Cancelled</option>
                        
                      </select> 
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
      $('.deposite_status').on('change',function(){
        var deposite_status  =  $(this).closest('tr').find('td .deposite_status').val();
      //  alert(deposite_status);
        var order_id  =  $(this).closest('tr').find('td .order_id').val();
      // alert(order_id);
        $.ajax({
            type:'Post',
            url: '<?php echo base_url(); ?>Orders/UpdateDepositStatus',
            data :{deposite_status : deposite_status ,order_id :order_id },
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
</script>

