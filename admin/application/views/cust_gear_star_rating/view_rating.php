<!DOCTYPE html>
<html lang="en">

<style>
	span font{
		color:#990000;
	}

	span b {
		font-size: 10px;
	}
</style>
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Gear Ratings List</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>cust_gear_star_rating">Customer Gear Ratings List</a></li>
							<li class="breadcrumb-item active">Gear Ratings List</li>
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
                                <h4 class="card-title">Gear Details</h4>
								<div class="box-header with-border">
								  </div>
								  <!-- /.box-tools -->
								</div>
								<!-- /.box-header -->
								<div class="box-body no-padding">
									<div class="row">
										<div class="col-md-3" style="padding-left:3em;" >
											<?php $sql1="SELECT gear_display_image FROM ks_user_gear_images WHERE user_gear_desc_id=".$gears[0]->user_gear_desc_id." AND gear_display_seq_id=1";$result1=$this->db->query($sql1);foreach($result1->result() as $row1){?><img class="img-thumbnail" src="<?php echo base_url();?>uploads/gear_images/<?php echo $row1->gear_display_image; }?>"/>
										</div>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-3">Gear Name</div>
												<div class="col-md-1">:</div>
												<div><p><?php echo $gears[0]->gear_name; ?></p></div>
											</div>
											<div class="row">
												<div class="col-md-3"></div>
												<div class="col-md-1"></div>
												<div><p><?php echo $gears[0]->gear_description_1; ?></p>
												<p><?php echo $gears[0]->gear_description_2; ?></p></div>
											</div>
											<div class="row">
												<div class="col-md-3">Per day cost (incl of GST)</div>
												<div class="col-md-1">:</div>
												<div><p><?php echo $gears[0]->per_day_cost_aud_inc_gst; ?></p></div>
											</div>
											<div class="row">
												<div class="col-md-3">Replacement Value (incl of GST)</div>
												<div class="col-md-1">:</div>
												<div><p><?php echo $gears[0]->replacement_value_aud_inc_gst; ?></p></div>
											</div>
											<div class="row">
												<div class="col-md-3">Owner's Remark</div>
												<div class="col-md-1">:</div>
												<div><p><?php echo $gears[0]->owners_remark; ?></p></div>
											</div>
											<div class="row">
												<div class="col-md-3">Listing Date</div>
												<div class="col-md-1">:</div>
												<div><p><?php if($gears[0]->gear_listing_date==NULL) echo "This product has not yet been listed."; else echo  $gears[0]->gear_listing_date;?></p></div>
											</div>
											<div class="row">
												<div class="col-md-3"><a href="<?php echo base_url(); ?>gear_desc">Read More</a></div><br>
											</div>
										<div class="clr"></div>
										</div>
									</div>
									<!-- /.mail-box-messages -->
								  </div>
								  <div class="clr"></div>
								  <div class="box-footer no-padding">
									<div class="mailbox-controls">
									  <!-- Check all button -->
									  <!-- /.btn-group -->
									  <!-- /.btn-group -->
									</div>
									<!-- /.pull-right -->
								 </div>
								 <div class="card-body">
                                <h4 class="card-title">Ratings</h4>
								<div class="box-header with-border">
								  </div>
								  
								  <div class="box-body no-padding">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<ul style="list-style: none;">
													<?php foreach($ratings as $r){ ?>
													<li>
														<div class="row"><span style="font-weight:bold;"><font><?php $sql1="SELECT app_username FROM ks_users WHERE app_user_id=".$r->app_user_id;$result1=$this->db->query($sql1);foreach($result1->result() as $row1){echo $row1->app_username; }?></font><b><?php echo "  ".date('d M, Y', strtotime($r->gear_star_rating_date)); ?></b></span></div>
														<div><?php star($r->gear_star_rating_value,5); ?><div>
													</li>
													<?php } ?>
												</ul>
											</div>
										<div class="clr"></div>
										</div>
									</div>
									<!-- /.mail-box-messages -->
								  </div>
								  
								</div>
								  <!-- /.box-tools -->
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
$( document ).ready(function() {

	var x= $('#ks_cust_gear_review_id').val();

	$.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>cust_gear_reviews/ajax",
            data:{data:x},
            success:function(response){
				$("#review").append(response);
            }
        });
});

</script>

<?php
	function star($i,$total){
		$i=($i/5)*$total;
		$j=floor($i);
		while($j>0){?>
			<i class="fa fa-star" style="color:#e6b800;"></i>
			<?php $j=$j-1; 
		}
		if(fmod($i,1)!=0){ ?>
			<i class="fa fa-star-half-empty"style="color:#e6b800;"></i>
		<?php } 
		$k=floor($total-$i);
		while($k>0){?>
			<i class="fa fa-star-o"></i>
			<?php $k=$k-1; 
		}
	}
?>
