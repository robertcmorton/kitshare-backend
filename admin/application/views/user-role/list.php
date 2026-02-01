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
    <h3 class="text-themecolor m-b-0 m-t-0">User Role List</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item active">User Role List</li>
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
        <h4 class="card-title">User Role List</h4>
        <h6 class="card-subtitle">(
          <?php if($total_rows>1){ echo $total_rows." User Roles"; }else{ echo $total_rows." User Role"; } ?>
          )</h6>
		  <?php echo $this->session->userdata('success'); ?>
        <div class="box-header with-border">
          <h3 class="box-title">Search by:</h3>
          <div class="panel-body">
            <div class="has-feedback">
              <form class="form-inline  example example50" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>user_role">
                <div class="col-md-8 no_padding">
                  <input type="text" class="" name="username" placeholder="Username" value="<?php echo $username; ?>">
                  <input type="text" class="" name="role" placeholder="Role" value="<?php echo $role; ?>">
                  <button type="submit"><i class="fa fa-search"></i></button>
                </div>
                <div class="form-group col-md-4"> <a href="<?php echo base_url();?>user_role/add" class="btn btn-primary pull-right add_userbtn">Add User Role&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></a> </div>
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
        <form name="bulk_action_form" action="<?php echo base_url();?>user_role/select_delete" method="post" onSubmit="return delete_confirm();"/>
		<div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
               			<!--<input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="Delete"/>-->
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
<div class="description">
			Showing <?php echo ($offset+1); ?> to <?php if($total_rows<($limit+$offset)) echo $total_rows ; else echo ($limit+$offset) ; ?> of <?php echo $total_rows; ?>
		</div>
		<div class="pull-right"> <?php echo $paginator;?> </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
        <div class="box-body no-padding">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/>
            <div class="pull-right"> <?php echo $paginator;?>
              <!-- /.btn-group -->
            </div>
            <!-- /.pull-right -->
          </div>
          <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">
              <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" id="select_all"  /></th>
                  <th>#</th>
                  <th>User</th>
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
				  <th><a href="<?php echo base_url() ?>user_role?field=app_role_name&order_by=<?php echo $order_by; ?>&limit=<?php echo $limit; ?>&per_page=<?php echo $offset; ?>">Role <?php if($total_rows>1) { ?><i class="fa fa-angle-<?php echo $arrow; ?>" style="font-size: 24px;"></i><?php } ?></th>
                  <th>Privilege Type</th>
                  <th>Created Date</th>
                  <th class="taC">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($result)>0){
				
				if($this->input->get('per_page')!='')
				{
					$i = $this->input->get('per_page')+1;
				}
				else
				{
					$i=1;
				}

				foreach($result as $row){ ?>
                <tr>
                  <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->user_role_id;?>"></td>
                  <td class="mailbox-name"><?php echo $i;?></td>
                  <td class="mailbox-subject"><?php echo $row->app_user_name;?></td>
                  <td class="mailbox-subject"><?php echo $row->app_role_name;?></td>
                  <td class="mailbox-subject"><?php $pri_type_id = $this->common_model->GetAllWhere(APP_ROLE_PRIV,array("app_role_id"=>$row->app_role_id));
																			
					foreach($pri_type_id->result() as $key=>$val){
						$app_priv_id = $val->app_priv_id;
						$pri_type = $this->common_model->GetAllWhere(M_APP_PRIVILEGE,array("app_priv_id"=>$app_priv_id));
						$str='';
						foreach($pri_type->result() as $k=>$v){
						
							$str.=ucfirst($v->privilege_type).' ';
						}
						
						echo $str;
					}  ?></td>
                  
                  <td class="mailbox-subject"><?php echo date('d M, Y', strtotime($row->created_date)); ?></td>
                  <td class="mailbox-date"><a href="<?php echo base_url();?>user_role/edit/<?php echo $row->user_role_id; ?>" title="Modify" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a><a onClick="return confirm('Would You Like To Delete This ?');" href="<?php echo base_url();?>user_role/delete_record/<?php echo $row->user_role_id; ?>" title="Delete" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a></td>
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
<script type="text/javascript" src="<?php echo base_url();?>assist/js/jquery.min.js"></script>
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
		window.location.href = "<?php echo base_url() ?>user_role/?limit="+limit;
		
		
    });
});
</script>
