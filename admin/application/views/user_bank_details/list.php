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
    <h3 class="text-themecolor m-b-0 m-t-0">User Bank Details List</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item active">User Bank Details List</li>
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
        <h4 class="card-title">User Bank Details List</h4>
        <h6 class="card-subtitle">(
          <?php if($total_rows>1){ echo $total_rows." Details"; }else{ echo $total_rows." Detail"; }?>
          )</h6>
        <div class="box-header with-border">
          <h3 class="box-title">Search by:</h3>
          <div class="panel-body">
            <div class="has-feedback">
              <form class="form-inline example example53" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>user_bank_details">
                <div class="form-group col-md-9 no_padding">
                  <input type="text" class="" name="branch_code" placeholder="Branch Code" value="<?php echo $branch_code; ?>">
                  <input type="text" class="" name="bank_name" placeholder="BSB Number" value="<?php echo $bsb_number; ?>">
                  <input type="text" class="" name="branch_suburb_name" placeholder="Suburb" value="<?php echo $branch_suburb_name; ?>">
                  <button type="submit"><i class="fa fa-search"></i></button>
                </div>
                <div class="form-group col-md-2"> <a href="<?php echo base_url();?>user_bank_details/add" class="btn btn-primary pull-right add_userbtn">Add User Bank Details &nbsp;<i class="fa fa-plus" aria-hidden="true"></i></a> </div>
				
              </form>
              <!-- <div class="form-group col-md-4"> -->
              <!-- <div class="form-group col-md-8"> -->
              <!-- </div> -->
              <!-- </div> -->
            </div>
			<br/>
			<div class="row" style="clear:both;">
				<div class="form-group col-md-2"> <a href="#" class="btn btn-primary pull-right add_userbtn" data-toggle="modal" data-target="#myModal">CSV Upload&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></a> </div>
				<div class="form-group col-md-2"> <a href="<?php echo base_url();?>user_bank_details/downloadallcsv" class="btn btn-primary pull-right add_userbtn" >CSV Download&nbsp;<i class="fa fa-download" aria-hidden="true"></i></a> </div>
				</div>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <form name="bulk_action_form" action="<?php echo base_url();?>user_bank_details/select_delete" method="post" onSubmit="return delete_confirm();"/>
        <div class="box-body no-padding">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/>
            <div class="pull-right"> <?php echo $paginator;?>
              <div class="col-md-12">
		  <strong>Per page view: </strong>
		   
			<select name="limit" id="limit">
				<option value="25" <?php if($limit==25) echo "selected=selected" ; ?>>25</option>
				<option value="50" <?php if($limit==50) echo "selected=selected" ; ?>>50</option>
				<option value="100" <?php if($limit==100) echo "selected=selected" ; ?>>100</option>
			</select>
		  </div>
		  <div class="description">
			Showing <?php echo ($offset+1); ?> to <?php if($total_rows<($limit+$offset)) echo $total_rows ; else echo ($limit+$offset) ; ?> of <?php echo $total_rows; ?>
		</div>
            </div>
            <!-- /.pull-right -->
          </div>
          <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">
              <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" id="select_all"  /></th>
                  <th class="font_n">#</th>
                  <th class="font_n">User</th>
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
				   <th><a href="<?php echo base_url() ?>user_bank_details?order_by=<?php echo $order_by; ?>&limit=<?php echo $limit; ?>&per_page=<?php echo $offset; ?>">Bank Name <?php if($total_rows>1) { ?><i class="fa fa-angle-<?php echo $arrow; ?>" style="font-size: 24px;"></i><?php } ?></th>
                 
                  <th class="font_n">Account Type</th>
                  <th class="font_n">Branch Code</th>
                  <!--<th class="font_n">BSB Number</th>-->
                  <!--<th class="font_n">Branch City</th>
                  <th class="font_n">Branch Zip Code</th>-->
                  <th class="font_n">Branch Suburb</th>
                  <th class="font_n">User Account Number</th>
                  <th class="font_n">Braintree  Connection Status</th>
                  <th class="font_n">Status</th>
                  <th class="font_n">Create User</th>
                  <th class="font_n">Create Date</th>
                  <th class="taC font_n">Action</th>
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

				foreach($result->result() as $row){ ?>
                <tr>
                  <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->user_bank_detail_id;?>"></td>
                  <td class="mailbox-name font_n"><?php echo $i;?></td>
                  <?php $sql1="SELECT * FROM ks_users WHERE app_user_id=".$row->app_user_id;
                  $result1=$this->db->query($sql1);
                  $row1  = $result1->row();
                      if ($row1->show_business_name == 'Y' ) {
                          $name = $row1->bussiness_name ;
                      }else{
                          $name = $row1->app_user_first_name .' '.$row1->app_user_last_name;
                        }

                   ?>
                  <td class="mailbox-subject font_n"><?php echo $name;  ?></td>
                  <td class="mailbox-subject font_n"><?php $sql1="SELECT bank_name FROM ks_banks WHERE bank_id=".$row->bank_id;$result1=$this->db->query($sql1);foreach($result1->result() as $row1){echo $row1->bank_name; }?></td>
                  <td class="mailbox-subject font_n"><?php echo $row->account_type;?></td>
                  <td class="mailbox-subject font_n"><?php echo $row->branch_code;?></td>
                  <!--<td class="mailbox-subject font_n"><?php // echo $row->bsb_number;?></td>-->
                  <!--<td class="mailbox-subject font_n"><?php //echo $row->branch_city;?></td>
                  <td class="mailbox-subject font_n"><?php // echo $row->branch_zip_code;?></td>-->
                  <td class="mailbox-subject font_n"><?php $sql1="SELECT ks_state_name FROM ks_states WHERE ks_state_id=".$row->branch_state_id;$result1=$this->db->query($sql1);foreach($result1->result() as $row1){echo $row1->ks_state_name; }?></td>
                  <td class="mailbox-subject font_n"><?php echo $row->user_account_number;?></td>
                  <td class="mailbox-subject font_n"><?php if($row->accept_stripe_connection=='Y')echo "Accepted"; else echo "Not Accepted";?></td>
                  <td class="mailbox-subject font_n"><?php if($row->is_active=='Y')echo "Active";else echo "Inactive";?></td>
                  <td class="mailbox-subject font_n"><?php echo $row->create_user;?></td>
                  <td class="mailbox-subject font_n"><?php echo date('d M, Y', strtotime($row->create_date)); ?></td>
                  <td class="mailbox-date font_n"><a href="<?php echo base_url();?>user_bank_details/edit/<?php echo $row->user_bank_detail_id; ?>" title="Modify" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a onClick="return confirm('Would You Like To Delete This ?');" href="<?php echo base_url();?>user_bank_details/delete_record/<?php echo $row->user_bank_detail_id; ?>" title="Delete" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a></td>
                </tr>
                <?php $i++; } ?>
                <?php } else {?>
                <tr>
                  <td colspan="16" align="center">No record found</td>
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
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
	  <h4 class="modal-title">Upload CSV</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <form class="form-inline" data-toggle="validator" role="form" method="post" action="<?php echo base_url();?>user_bank_details/import" enctype="multipart/form-data">
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
		window.location.href = "<?php echo base_url() ?>user_bank_details/?limit="+limit;
		
		
    });
});

</script>
