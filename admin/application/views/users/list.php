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
      <h3 class="text-themecolor m-b-0 m-t-0">Users' List</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
        <li class="breadcrumb-item active">Users' List</li>
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
          <h4 class="card-title">Users' List</h4>
          <h6 class="card-subtitle">(
            <?php if($total_rows>1){ echo $total_rows." Users"; }else{ echo $total_rows." User"; } ?>
            )</h6>
			<?php echo $this->session->userdata('success'); ?>
          <div class="box-header with-border">
            <h3 class="box-title">Search by:</h3>
            <div class="panel-body">
              <div class="has-feedback">
                <form class="form-inline" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>users">
				  
				     
					<div class=" col-md-4">
					<input type="text" class="form-control input-sm" name="username" placeholder="Username" value="<?php echo $username; ?>">
					</div>
					<div class=" col-md-4">
					<button type="submit" class="btn btn-success"> <i class="fa fa-search"></i> Search </button>
					</div>
					<div class=" col-md-4">
					<a href="<?php echo base_url();?>users/add" class="btn btn-primary">Add User  <i class="fa fa-plus" aria-hidden="true"></i></a>
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
		  </br>
		   <div class="clearfix"></div>
		  
          <form name="bulk_action_form" action="<?php echo base_url();?>users/select_delete" method="post" onSubmit="return delete_confirm();"/>
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
               			<input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="Delete"/>
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
<div class="description">
			Showing <?php echo ($offset+1); ?> to <?php if($total_rows<($limit+$offset)) echo $total_rows ; else echo ($limit+$offset) ; ?> of <?php echo $total_rows; ?>
		</div>
		<div class="pull-right"> <?php echo $paginator;?> </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
				  <thead>
                  <tr>
				  	<th><input type="checkbox" name="select_all" id="select_all"  /></th>
					<th>SI No.</th>
					
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
					 <th><a href="<?php echo base_url() ?>users?order_by=<?php echo $order_by; ?>&limit=<?php echo $limit; ?>&per_page=<?php echo $offset; ?>">UserName <?php if($total_rows>1) { ?><i class="fa fa-angle-<?php echo $arrow; ?>" style="font-size: 24px;"></i><?php } ?></th>
					<th>Added By</th>
					<th>Add</th>
					<th>Edit</th>
					<th>View</th>
					<th>Delete</th>
					<th>Created Date</th>
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
                    <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->app_user_id;?>"></td>
                    <td class="mailbox-name"><?php echo $i;?></td>
					<td class="mailbox-subject"><?php echo $row->app_user_name;?></td>
					<td>
					<?php
					if($row->level >0){
					$record=$this->db->query('select app_user_name from m_app_user where app_user_id='.$row->level.'');
			        echo $name=$record->row()->app_user_name;	
					} else { echo 'Super Admin';}
					?>
					</td>
					<td class="mailbox-subject"><?php echo $row->pre_add;?></td>
					<td class="mailbox-subject"><?php echo $row->pre_edit;?></td>
					<td class="mailbox-subject"><?php echo $row->pre_view;?></td>
                    <td class="mailbox-attachment"><?php echo $row->pre_delete;?></td>
                    <td class="mailbox-date"><?php echo date('M d, Y', strtotime($row->created_date)); ?></td>
					<td class="mailbox-date">
					<a href="<?php echo base_url();?>users/edit/<?php echo $row->app_user_id; ?>" title="Modify"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					<a onClick="return confirm('Would You Like To Delete This Page ?');" href="<?php echo base_url();?>users/delete_record/<?php echo $row->app_user_id; ?>" title="Delete"><i class="fa fa-trash-o"></i></a></td>
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
<script type="text/javascript">
function delete_confirm(){
	var result = confirm("Are you sure to delete users?");
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
		window.location.href = "<?php echo base_url() ?>users/?limit="+limit;
		
		
    });
});
</script>