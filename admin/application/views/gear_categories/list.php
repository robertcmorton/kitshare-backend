<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from wrappixel.com/demos/admin-templates/material-pro/material/table-data-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Dec 2017 12:51:16 GMT -->
<head>
<!-- You can change the theme colors from here -->
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
    <h3 class="text-themecolor m-b-0 m-t-0">Gear Categories Details List</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <?php $id1=$this->uri->segment(3); 
							$this->load->helper('list_helper');
							if($id1==NULL){	echo list1($id1,0,0);	} else {	echo list1($id1,0,1);	}?>
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
        <h4 class="card-title">Gear Categories Details List</h4>
        <h6 class="card-subtitle">(
          <?php if($total_rows>1){ echo $total_rows." Categories"; }else{ echo $total_rows." Category"; }?>
          )</h6>
        <?php echo $this->session->userdata('success'); ?>
        <div class="box-header with-border">
          <h3 class="box-title">Search by:</h3>
          <div class="panel-body">
            <div class="has-feedback">
              <form class="form-inline  example" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>gear_categories">
                <div class="col-md-6 no_padding">
                  <input type="text" class="" name="gear_category_name" placeholder="Category Name" value="<?php echo $gear_category_name; ?>">
                  <button type="submit"><i class="fa fa-search"></i></button>
                </div>
                <div class="form-group col-md-3"> <a href="<?php echo base_url();?>gear_categories/add" class="btn btn-primary pull-right add_userbtn">Add Gear Category <i class="fa fa-plus" aria-hidden="true"></i></a> </div>
              </form>
            </div>
			<div class="pull-right">
				<a href="#" class="btn btn-primary add_userbtn" data-toggle="modal" data-target="#myModal">CSV Upload to Add Data&nbsp;<i class="fa fa-upload" aria-hidden="true"></i> </a>
				<a href="#" class="btn btn-primary add_userbtn" data-toggle="modal" data-target="#myModalModify">CSV Upload to Modify Data&nbsp;<i class="fa fa-upload" aria-hidden="true"></i> </a>
				<a href="<?php echo base_url();?>gear_categories/downloadallcsv" class="btn btn-primary add_userbtn">CSV Download&nbsp;<i class="fa fa-download" aria-hidden="true"></i> </a>
			</div>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <form name="bulk_action_form" action="<?php echo base_url();?>gear_categories/select_delete" method="post" onSubmit="return delete_confirm();"/>
        <div class="box-body no-padding">
          <div class="mailbox-controls">
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
                  <th>Category Name</th>
				  <th>Security Deposit</th>
				  <th>Average Value</th>
                  <th>Status</th>
                  <th>Created Date</th>
                  <th class="taC">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($gear)>0){ 
				if($this->input->get('per_page')!='')
				  {
					$i = $this->input->get('per_page')+1;
				  }
				  else
				  {
					$i=1;
				  }
				 foreach($gear as $row){ ?>
                <tr>
                  <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->gear_category_id;?>"></td>
                  <td class="mailbox-name"><?php echo $i;?></td>
                  <td class="mailbox-date"><a href="<?php echo base_url();?>gear_categories/index/<?php echo $row->gear_category_id; ?>"><?php echo $row->gear_category_name;?></a></td>
				  <td class="mailbox-date"><?php if($row->security_deposit=='Y') echo 'Yes'; else echo 'No' ;  ?></td>
				  <td class="mailbox-date"><?php echo $row->average_value ;  ?></td>
                  <td class="mailbox-date"><?php if($row->is_active=='Y') echo 'Yes'; else echo 'No' ;  ?></td>
                  <td class="mailbox-date"><?php echo date('M d, Y', strtotime($row->create_date)) ;  ?></td>
                  <td class="mailbox-date"><a href="<?php echo base_url();?>gear_categories/edit/<?php echo $row->gear_category_id; ?>" title="Modify" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a onClick="return confirm('Would You Like To Delete This ?');" href="<?php echo base_url();?>gear_categories/delete_record/<?php echo $row->gear_category_id; ?>" title="Delete" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a> </td>
                </tr>
                <?php $i++; }   ?>
                <?php } else {?>
                <tr>
                  <td colspan="10" align="center">No record found</td>
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
        <h4 class="modal-title">Upload CSV to Add Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form class="form-inline" data-toggle="validator" role="form" method="post" action="<?php echo base_url();?>gear_categories/import" enctype="multipart/form-data">
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
<div id="myModalModify" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload CSV to Modify Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form class="form-inline" data-toggle="validator" role="form" method="post" action="<?php echo base_url();?>gear_categories/import_to_modify" enctype="multipart/form-data">
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
</script>
