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
      <h3 class="text-themecolor m-b-0 m-t-0">Log' List</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
        <li class="breadcrumb-item active">Log' List</li>
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
          <h4 class="card-title">Log' List</h4>
          <h6 class="card-subtitle">
           </h6>
			<?php echo $this->session->userdata('success'); ?>
          <div class="box-header with-border">
            <!-- <h3 class="box-title">Search by:</h3> -->
            <div class="panel-body">
              <div class="has-feedback">
                
                <!-- <div class="form-group col-md-4"> -->
                <!-- <div class="form-group col-md-8"> -->
                <!-- </div> -->
                <!-- </div> -->
              </div>
            </div>
            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
		  </br>
		   <div class="clearfix"></div>
		  
          <form name="bulk_action_form" action="<?php echo base_url();?>users/select_delete" method="post" onSubmit="return delete_confirm();"/>
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
               			<!-- <input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="Delete"/> -->
                 <div class="row pull-right">
		 <!--  <div class="col-md-12">
		  <strong>Per page view: </strong>
		   
			<select name="limit" id="limit">
				<option value="25" <?php if($limit==25) echo "selected=selected" ; ?>>25</option>
				<option value="50" <?php if($limit==50) echo "selected=selected" ; ?>>50</option>
				<option value="100" <?php if($limit==100) echo "selected=selected" ; ?>>100</option>
			</select> -->
		  </div>
		</div>
    <!-- div class="description">
			Showing <?php echo ($offset+1); ?> to <?php if($total_rows<($limit+$offset)) echo $total_rows ; else echo ($limit+$offset) ; ?> of <?php echo $total_rows; ?>
		</div> -->
		<div class="pull-right"> </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
				  <thead>
                  <tr>
				  	<!-- <th><input type="checkbox" name="select_all" id="select_all"  /></th> -->
					<th>SI No.</th>
					
				<!-- 	<?php
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
				  ?> -->
					 <th>Type</th>
					<th>Created Date</th>
					<th>Order Id</th>
					<th>status</th>
				
                  </tr>
                </thead>
                  <tbody>
				  <?php 
					$i=1;
				  if (!empty($result )) {
            
				  foreach($result as $row){ ?>
                  <tr>
                    <td class="mailbox-name"><?php echo $i;?></td>
          					<td class="mailbox-subject"><?php echo $row->type;?></td>
          					<td class="mailbox-subject"><?php echo $row->date_time;?></td>
          					<td class="mailbox-subject"><?php echo $row->order_id;?></td>
                    <?php 
                        if ($row->status =='2') {
                         $status = 'Reservation';
                        }elseif ($row->status =='3') {
                          $status = 'Contract';
                        }elseif ($row->status =='4') {
                          $status = 'Completed';
                        }elseif ($row->status =='5') {
                          $status = 'Cancelled';
                        }elseif ($row->status =='6') {
                          $status = 'Declined';
                        }elseif ($row->status =='7') {
                          $status = 'Archived';
                        }elseif ($row->status =='8') {
                          $status = 'Expired';
                        }elseif ($row->status =='mail_send') {
                          $status = 'Test Mail';
                        }
                        else{
                          $status = 'Quote';
                        }
                    ?>
          					<td class="mailbox-subject"><?php echo $status;?></td>
                  
                  </tr>
				  <?php $i++; } ?>
				  <?php } else {?>
				  <tr><td colspan="10" align="center">No record found</td> </tr>
				  <?php  }?>
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
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
  <script type="text/javascript" src="<?php echo base_url();?>dist/js/jquery.min.js"></script>
