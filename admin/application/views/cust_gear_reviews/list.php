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
      <h3 class="text-themecolor m-b-0 m-t-0">Customer Gear Reviews List</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
        <li class="breadcrumb-item active">Customer Gear Reviews List</li>
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
          <h4 class="card-title">Customer Gear Reviews List</h4>
          <h6 class="card-subtitle">(
            <?php if($total_rows>1){ echo $total_rows." Reviews"; }else{ echo $total_rows." Review"; }?>
            )</h6>
          <div class="box-header with-border">
            <h3 class="box-title">Search by:</h3>
            <div class="panel-body">
              <div class="has-feedback">
              <form class="form-inline example example53" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>cust_gear_reviews">
                <div class="form-group col-md-9 no_padding">
                  <input type="text" class="" name="gear_name" placeholder="Order Id" value="<?php echo $gear_name; ?>">
                  <!-- <input type="text" class="" name="cust_gear_review_desc" placeholder="Reviews" value="<?php echo $cust_gear_review_desc; ?>"> -->
                  <button type="submit"><i class="fa fa-search"></i></button>
                </div>
                </div>
              </form>
              <!-- <div class="form-group col-md-4"> -->
              <!-- <div class="form-group col-md-8"> -->
              <!-- </div> -->
              <!-- </div> -->
            </div>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <form name="bulk_action_form" action="<?php echo base_url();?>cust_gear_reviews/select_delete" method="post" onSubmit="return delete_confirm();"/>
        <div class="box-body no-padding">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <input type="submit" class="btn btn-danger" style="margin-top:20px; margin-left:20px;" name="bulk_delete_submit" value="Delete"/>
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
          </div>
          <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">
              <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" id="select_all"  /></th>
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
				  
                  
                  <th>Project Name</th>
                  <th>Order Id</th>
                  <th>Review By</th>
                  <th>Review To</th>
                  <th>Rating</th>
				          <th>Description</th>
                  <!-- <th>Status</th> -->
                  <th>Date</th>
                  <th class="taC">Action</th>
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
                  <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->ks_cust_gear_review_id;?>"></td>
                  <td class="mailbox-name"><?php echo $i;?></td>
                  <td class="mailbox-subject"><?php echo $row->project_name ;?></td>
                  <td class="mailbox-subject"><?php echo $row->order_id ;?></td>
                  <td class="mailbox-subject"><?php echo  $row->review_given_by_f_name . $row->review_given_by_l_name?>
                 
                  <td class="mailbox-subject"><?php echo  $row->review_given_to_f_name . $row->review_given_to_l_name ?></td>
                  <td class="mailbox-subject">
                     <?php 
                      for ($i=0; $i < $row->star_rating; $i++) { ?>
                           <i class="fa fa-star" style="color: goldenrod;"></i>                        
                     <?php }

                     ?> 

                   </td>
				          <td class="mailbox-subject"><?php echo word_limiter($row->cust_gear_review_desc,10);?></td>
                  <!-- <td class="mailbox-subject"><?php if($row->is_active=='Y')echo "Active";else echo "Inactive";?></td> -->
                  <td class="mailbox-subject"><?php echo $row->cust_gear_review_date;?></td> 
                  <td class="mailbox-date"><a href="<?php echo base_url(); ?>cust_gear_reviews/view_reviews/<?php echo $row->order_id; ?>" title="View Reviews" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
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
		window.location.href = "<?php echo base_url() ?>cust_gear_reviews/?limit="+limit;
		
		
    });
});
</script>
