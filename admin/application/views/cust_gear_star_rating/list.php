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
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Customer Gear Ratings List</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item active">Customer Gear Ratings List</li>
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
                                <h4 class="card-title">Customer Gear Ratings List</h4>
                                <h6 class="card-subtitle">(<?php if($total_rows>1){ echo $total_rows." Reviews"; }else{ echo $total_rows." Review"; }?>)</h6>
								<div class="box-header with-border">
								  <h3 class="box-title">Search by:</h3>
								  <div class="panel-body">
									<div class="has-feedback">
									  <form class="form-inline example" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>cust_gear_reviews">
										<div class="form-group col-md-5 no_padding">
										  <input type="text" class="" name="gear_name" placeholder="Gear Name" value="<?php echo $gear_name; ?>">
                                            <button type="submit"><i class="fa fa-search"></i></button>
										</div></div>
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
									<!-- /.pull-right -->
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
										<th><a href="<?php echo base_url() ?>cust_gear_star_rating?order_by=<?php echo $order_by; ?>&limit=<?php echo $limit; ?>&per_page=<?php echo $offset; ?>">Gear Name <?php if($total_rows>1) { ?><i class="fa fa-angle-<?php echo $arrow; ?>" style="font-size: 24px;"></i><?php } ?></th>
									  
									  <th>Gear Image</th>
									  <th>Average Star Rating</th>
								      <th>Status</th>
									  <th class="taC">Action</th>
									</tr>
								  </thead>
								  <tbody>
									<?php if($result->num_rows()>0){$i=1;

									  foreach($result->result() as $row){ ?>
									<tr>
									  <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->cust_gear_rating_id;?>"></td>
									  <td class="mailbox-name"><?php echo $i;?></td>
									  <td class="mailbox-subject"><?php $sql1="SELECT gear_name FROM ks_user_gear_description WHERE user_gear_desc_id=".$row->user_gear_desc_id;$result1=$this->db->query($sql1);foreach($result1->result() as $row1){echo $row1->gear_name; }?></td>
									  <td class="mailbox-subject"><?php $sql1="SELECT gear_display_image FROM ks_user_gear_images WHERE user_gear_desc_id=".$row->user_gear_desc_id." AND gear_display_seq_id=1";$result1=$this->db->query($sql1);foreach($result1->result() as $row1){?><img class="img-thumbnail" src="<?php echo GEAR_IMAGE."/".$row1->gear_display_image; }?>" /></td>
									  <td class="mailbox-subject"><?php $sql1="SELECT AVG(gear_star_rating_value) as average FROM ks_cust_gear_star_rating WHERE user_gear_desc_id=".$row->user_gear_desc_id;$result1=$this->db->query($sql1);foreach($result1->result() as $row1){echo $row1->average; }?></td>
									  <td class="mailbox-subject"><?php if($row->is_active=='Y')echo "Active";else echo "Inactive";?></td>
									  <td class="mailbox-date"><a href="<?php echo base_url(); ?>cust_gear_star_rating/view_ratings/<?php echo $row->user_gear_desc_id; ?>" title="View Ratings" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
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
		window.location.href = "<?php echo base_url() ?>cust_gear_star_rating/?limit="+limit;
		
		
    });
});
</script>
